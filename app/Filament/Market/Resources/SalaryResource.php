<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\SalaryResource\Pages;
use App\Models\Market\Market;
use App\Models\Market\Salary;
use App\Models\Market\Loan;
use App\Models\Market\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;
    protected static ?string $navigationIcon = 'fluentui-people-money-24';
    protected static ?string $navigationGroup = 'بخش مالی';
    protected static ?string $navigationLabel = 'پرداخت معاش کارمندان';
    protected static ?string $modelLabel = 'پرداخت';
    protected static ?string $pluralLabel = 'صفحه ثبت معاش کارمندان';

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'Financial Manager', 'admin']);
    }

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;

        return $form->schema([
            Forms\Components\Select::make('market_id')
                ->label('نام مارکت')
                ->options(Market::where('admin_id', $adminId)->pluck('name', 'id'))
                ->default(fn() => Market::where('admin_id', $adminId)
                    ->where('name', 'فردوسی')
                    ->value('id'))
                ->reactive()
                ->searchable()
                ->required()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    self::calculateSalaryPayment($state, $get, $set);
                }),

            Forms\Components\Select::make('staff_id')
                ->label('نام کارمند')
                ->options(
                    fn(callable $get) =>
                    Staff::where('market_id', $get('market_id'))
                        ->where('admin_id', $adminId)
                        ->pluck('fullname', 'id')
                )
                ->searchable()
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    self::calculateSalaryPayment($get('market_id'), $get, $set);
                }),

            Forms\Components\TextInput::make('salary')
                ->label('معاش ماهانه')
                ->numeric()
                ->disabled()
                ->dehydrated(),

            Forms\Components\TextInput::make('daily_salary')
                ->label('معاش روزانه')
                ->numeric()
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('loan')
                ->label('میزان قرض فعلی')
                ->numeric()
                ->disabled(),

            Forms\Components\TextInput::make('last_remained')
                ->label('باقی‌مانده معاش قبلی')
                ->numeric()
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('unpaid_days')
                ->label('روزهای پرداخت نشده')
                ->numeric()
                ->disabled()
                ->dehydrated(false),

            Forms\Components\Toggle::make('is_reduce')
                ->label('آیا قرضه رسید شود؟')
                ->reactive()
                ->default(false)
                ->visible(fn(callable $get) => $get('loan') > 0),

            Forms\Components\TextInput::make('reduce_loan')
                ->label('مقدار رسید قرضه')
                ->numeric()
                ->debounce(500)
                ->minValue(0)
                ->visible(fn(callable $get) => $get('is_reduce'))
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $loan = $get('loan') ?? 0;
                    $dailySalary = $get('daily_salary') ?? 0;
                    $unpaidDays = $get('unpaid_days') ?? 0;
                    $lastRemained = $get('last_remained') ?? 0;

                    $calculatedSalary = ($dailySalary * $unpaidDays) + $lastRemained;
                    $set('new_loan', max($loan - $state, 0));
                    $remainingSalary = max($calculatedSalary - $state, 0);
                    $set('paid', $state);
                    $set('remained', $remainingSalary);
                }),

            Forms\Components\TextInput::make('new_loan')
                ->label('باقیمانده قرض')
                ->numeric()
                ->disabled()
                ->dehydrated(false)
                ->visible(fn(callable $get) => $get('is_reduce')),

            Forms\Components\Hidden::make('salary_id')
                ->default(fn($record) => $record?->id)
                ->dehydrated(true),

            Forms\Components\TextInput::make('paid')
                ->label('مبلغ پرداختی')
                ->numeric()
                ->required()
                ->minValue(0)
                ->lazy()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    self::updateRemained($get, $set);
                }),

            Forms\Components\TextInput::make('remained')
                ->label('باقیمانده معاش')
                ->numeric()
                ->disabled()
                ->dehydrated(true),

            Forms\Components\Select::make('reduce_from')
                ->label('برداشت از صندوق')
                ->options(
                    fn() =>
                    DB::connection('market')->table('accountings')
                        ->whereNotNull('expanses_type')
                        ->distinct()
                        ->pluck('expanses_type', 'expanses_type')
                        ->toArray()
                )
                ->default('کرایه')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('currency')
                ->label('واحد پول')
                ->options([
                    'AFN' => 'افغانی',
                    'USD' => 'دالر',
                    'EUR' => 'یورو',
                    'IRR' => 'تومان',
                ])
                ->default('AFN')
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('توضیحات')
                ->nullable(),

            Forms\Components\Hidden::make('paid_date')
                ->label('تاریخ پرداخت')
                ->default(now())
                ->reactive()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    self::calculateSalaryPayment($get('market_id'), $get, $set);
                }),

            Forms\Components\Hidden::make('loan_id'),
        ]);
    }

    /**
     * متد جدید برای بروزرسانی باقیمانده
     */
    private static function updateRemained(callable $get, callable $set)
    {
        $staffId = $get('staff_id');
        $marketId = $get('market_id');
        $salaryId = $get('salary_id');
        $paid = (float) $get('paid') ?: 0;

        if (!$staffId || !$marketId) {
            return;
        }

        $staff = Staff::find($staffId);
        if (!$staff) {
            return;
        }

        // محاسبه حقوق روزانه
        $dailySalary = $staff->salary / 30;

        // دریافت آخرین پرداخت قبلی (به جز رکورد جاری)
        $lastSalary = Salary::where('staff_id', $staffId)
            ->where('market_id', $marketId)
            ->when($salaryId, function ($query) use ($salaryId) {
                $query->where('id', '!=', $salaryId);
            })
            ->orderBy('paid_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        // باقیمانده قبلی
        $lastRemained = $lastSalary?->remained ?? 0;

        // محاسبه روزهای پرداخت نشده
        $unpaidDays = 0;
        if ($lastSalary) {
            $lastDate = Jalalian::fromDateTime($lastSalary->paid_date);
            $currentDate = Jalalian::fromDateTime(now());
            $unpaidDays = self::calculateJalaliDaysDifference($lastDate, $currentDate);
        } else {
            // اولین پرداخت
            if ($staff->contract_start) {
                $startDate = Carbon::parse($staff->contract_start);
                if (!$startDate->isFuture()) {
                    $startJalali = Jalalian::fromDateTime($startDate);
                    $currentDate = Jalalian::fromDateTime(now());
                    $unpaidDays = self::calculateJalaliDaysDifference($startJalali, $currentDate);
                    $unpaidDays = max(1, $unpaidDays);
                }
            } else {
                // اگر تاریخ قرارداد ندارد، از اول ماه محاسبه کن
                $currentDate = Jalalian::fromDateTime(now());
                $firstDayOfMonth = new Jalalian(
                    $currentDate->getYear(),
                    $currentDate->getMonth(),
                    1
                );
                $unpaidDays = self::calculateJalaliDaysDifference($firstDayOfMonth, $currentDate);
                $unpaidDays = max(1, $unpaidDays + 1);
            }
        }

        // کل مبلغ قابل پرداخت
        $totalSalary = ($dailySalary * max(0, $unpaidDays)) + max(0, $lastRemained);

        // محاسبه باقیمانده
        $remained = max(0, $totalSalary - $paid);

        // بروزرسانی فیلدها
        $set('unpaid_days', max(0, $unpaidDays));
        $set('daily_salary', round($dailySalary, 2));
        $set('last_remained', round($lastRemained, 2));
        $set('remained', round($remained, 2));

        // بروزرسانی قرض
        $loan = Loan::where('staff_id', $staffId)
            ->where('market_id', $marketId)
            ->latest()
            ->first();

        if ($loan && $loan->remainingAmount() > 0) {
            $set('loan_id', $loan->id);
            $set('loan', $loan->remainingAmount());
        } else {
            $set('loan_id', null);
            $set('loan', 0);
        }
    }

    private static function calculateSalaryPayment($marketId, callable $get, callable $set)
    {
        $staffId = $get('staff_id');
        $paidDate = $get('paid_date') ?: now();

        if (!$staffId || !$marketId) {
            return;
        }

        $staff = Staff::find($staffId);
        if (!$staff) {
            return;
        }

        // تنظیم حقوق
        $set('salary', $staff->salary);
        $dailySalary = $staff->salary / 30;
        $set('daily_salary', round($dailySalary, 2));

        // دریافت آخرین پرداخت
        $query = Salary::where('staff_id', $staffId)
            ->where('market_id', $marketId);

        if ($get('salary_id')) {
            $query->where('id', '!=', $get('salary_id'));
        }

        $lastSalary = $query
            ->orderBy('paid_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        // محاسبه روزهای پرداخت نشده
        $currentJalali = Jalalian::fromDateTime($paidDate);
        $unpaidDays = 0;

        if ($lastSalary) {
            $lastJalali = Jalalian::fromDateTime($lastSalary->paid_date);
            $unpaidDays = self::calculateJalaliDaysDifference($lastJalali, $currentJalali);
            $unpaidDays = max(0, $unpaidDays);
        } else {
            $unpaidDays = self::calculateFirstPaymentDays($staff, $currentJalali);
        }

        $set('unpaid_days', $unpaidDays);

        // باقیمانده قبلی
        $lastRemainedRecord = Salary::where('staff_id', $staffId)
            ->where('market_id', $marketId)
            ->when($get('salary_id'), function ($query) use ($get) {
                $query->where('id', '!=', $get('salary_id'));
            })
            ->orderBy('paid_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $lastRemained = $lastRemainedRecord && $lastRemainedRecord->remained > 0
            ? (float) $lastRemainedRecord->remained
            : 0;
        $set('last_remained', round($lastRemained, 2));

        // کل مبلغ قابل پرداخت
        $totalSalary = ($dailySalary * $unpaidDays) + $lastRemained;

        // مقدار paid فعلی
        $currentPaid = (float) $get('paid') ?: 0;

        // اگر paid خالی یا صفر است، مقدار پیش‌فرض را تنظیم کن
        if ($currentPaid == 0 && !$get('is_reduce')) {
            $set('paid', round($totalSalary, 2));
            $currentPaid = $totalSalary;
        }

        // محاسبه باقیمانده
        $remained = max(0, $totalSalary - $currentPaid);
        $set('remained', round($remained, 2));

        // دریافت قرض
        $loan = Loan::where('staff_id', $staffId)
            ->where('market_id', $marketId)
            ->latest()
            ->first();

        if ($loan && $loan->remainingAmount() > 0) {
            $set('loan_id', $loan->id);
            $set('loan', $loan->remainingAmount());
        } else {
            $set('loan_id', null);
            $set('loan', 0);
        }
    }

    private static function calculateFirstPaymentDays(Staff $staff, Jalalian $currentJalali): int
    {
        if ($staff->contract_start) {
            $startDate = Carbon::parse($staff->contract_start);
            if ($startDate->isFuture()) {
                return 0;
            }

            $startJalali = Jalalian::fromDateTime($startDate);
            $daysDifference = self::calculateJalaliDaysDifference($startJalali, $currentJalali);
            return max(1, $daysDifference);
        }

        $firstDayOfMonth = new Jalalian(
            $currentJalali->getYear(),
            $currentJalali->getMonth(),
            1
        );

        $daysDifference = self::calculateJalaliDaysDifference($firstDayOfMonth, $currentJalali);
        return max(1, $daysDifference + 1);
    }

    private static function calculateJalaliDaysDifference(Jalalian $startDate, Jalalian $endDate): int
    {
        try {
            $startCarbon = $startDate->toCarbon();
            $endCarbon = $endDate->toCarbon();
            return (int) $startCarbon->diffInDays($endCarbon);
        } catch (\Exception $e) {
            return (int) abs($endDate->getTimestamp() - $startDate->getTimestamp()) / (60 * 60 * 24);
        }
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('market.name')->label('مارکت'),
            Tables\Columns\TextColumn::make('staff.fullname')->label('کارمند')->searchable(),
            Tables\Columns\TextColumn::make('salary')->label('معاش ماهانه'),
            Tables\Columns\TextColumn::make('paid')->label('مبلغ پرداختی'),
            Tables\Columns\TextColumn::make('reduce_loan')->label('رسید قرض'),
            Tables\Columns\TextColumn::make('remained')->label('باقیمانده'),
            Tables\Columns\TextColumn::make('reduce_from')->label('برداشت از'),
            Tables\Columns\TextColumn::make('description')->label('توضیحات'),
            Tables\Columns\TextColumn::make('paid_date')
                ->label('تاریخ پرداخت')
                ->formatStateUsing(
                    fn($state) =>
                    Jalalian::fromDateTime($state)->format('Y/m/d') .
                        ' - ' .
                        date('g:i A', strtotime($state))
                ),
        ])->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('چاپ')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('salary.print', $record))
                    ->openUrlInNewTab(),
            ])->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalaries::route('/'),
            'create' => Pages\CreateSalary::route('/create'),
            'view' => Pages\ViewSalary::route('/{record}'),
            'edit' => Pages\EditSalary::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        return $user->role === 'superadmin'
            ? parent::getEloquentQuery()
            : parent::getEloquentQuery()->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
    }
}