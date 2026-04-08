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
    protected static ?string $pluralLabel = 'صفحه  ثبت معاش کارمندان';


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
                ->reactive()
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
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    self::calculateSalaryPayment($get('market_id'), $get, $set);
                }),

            Forms\Components\TextInput::make('salary')->label('معاش ماهانه')->numeric()->disabled()->dehydrated(),
            Forms\Components\TextInput::make('daily_salary')->label('معاش روزانه')->numeric()->disabled()->dehydrated(false),
            Forms\Components\TextInput::make('loan')->label('میزان قرض فعلی')->numeric()->disabled(),
            Forms\Components\TextInput::make('last_remained')->label('باقی‌مانده معاش قبلی')->numeric()->disabled()->dehydrated(false),

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

                    // محاسبه معاش روزانه برای روزهای پرداخت نشده
                    $calculatedSalary = ($dailySalary * $unpaidDays) + $lastRemained;

                    $set('new_loan', max($loan - $state, 0));

                    // اگر رسید قرض از معاش کسر شود
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

            Forms\Components\TextInput::make('paid')
                ->label('مبلغ پرداختی')
                ->numeric()
                ->required()
                ->minValue(0)
                ->debounce(500)
                ->visible(fn(callable $get) => !$get('is_reduce'))
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $dailySalary = $get('daily_salary') ?? 0;
                    $unpaidDays = $get('unpaid_days') ?? 0;
                    $lastRemained = $get('last_remained') ?? 0;

                    $calculatedSalary = ($dailySalary * $unpaidDays) + $lastRemained;

                    $set(
                        'remained',
                        round(max($calculatedSalary - $state, 0), 2)
                    );
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
                ->required(),

            Forms\Components\Select::make('currency')
                ->label('واحد پول')
                ->options([
                    'AFN' => 'افغانی',
                    'USD' => 'دالر',
                    'EUR' => 'یورو',
                    'IRR' => 'تومان',
                ])
                ->required(),

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

        // تنظیم حقوق ماهانه و روزانه
        $set('salary', $staff->salary);
        $dailySalary = $staff->salary / 30;
        $set('daily_salary', round($dailySalary, 2));

        // دریافت آخرین پرداخت معاش
        $lastSalary = Salary::where('staff_id', $staffId)
            ->where('market_id', $marketId)
            ->orderBy('paid_date', 'desc')
            ->first();

        // تاریخ فعلی شمسی
        $currentJalali = Jalalian::fromDateTime($paidDate);

        // محاسبه روزهای پرداخت نشده
        $unpaidDays = 0;
        if ($lastSalary) {
            $lastPaymentDate = $lastSalary->paid_date;
            $lastJalali = Jalalian::fromDateTime($lastPaymentDate);

            $unpaidDays = self::calculateJalaliDaysDifference($lastJalali, $currentJalali);
            $unpaidDays = max(0, $unpaidDays); // اگر کمتر از صفر شد صفر شود
        } else {
            $unpaidDays = self::calculateFirstPaymentDays($staff, $currentJalali);
        }
        $set('unpaid_days', $unpaidDays);

        // دریافت باقیمانده واقعی آخرین پرداخت
        $lastRemainedRecord = Salary::where('staff_id', $staffId)
            ->where('market_id', $marketId)
            ->orderBy('paid_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $lastRemained = $lastRemainedRecord && $lastRemainedRecord->remained > 0
            ? (float) $lastRemainedRecord->remained
            : 0;
        $set('last_remained', round($lastRemained, 2));

        // محاسبه مبلغ قابل پرداخت
        $calculatedPayment = ($dailySalary * $unpaidDays) + $lastRemained;

        // مقدار فعلی paid
        $currentPaid = $get('paid');

        // اگر حالت رسید قرض فعال نیست
        if (!$get('is_reduce')) {
            if (empty($currentPaid) || $currentPaid == 0 || $currentPaid == $calculatedPayment) {
                $set('paid', round($calculatedPayment, 2));
                $currentPaid = $calculatedPayment;
            }

            $set('remained', round(max($calculatedPayment - $currentPaid, 0), 2));
        }

        // دریافت قرض فعلی
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


    /**
     * محاسبه روزهای پرداخت برای اولین پرداخت
     */
    private static function calculateFirstPaymentDays(Staff $staff, Jalalian $currentJalali): int
    {
        // اگر کارمند تاریخ شروع قرارداد دارد
        if ($staff->contract_start) {
            $startDate = Carbon::parse($staff->contract_start);

            // اگر تاریخ شروع قرارداد در آینده باشد
            if ($startDate->isFuture()) {
                return 0;
            }

            $startJalali = Jalalian::fromDateTime($startDate);

            // محاسبه تفاوت روزها
            $daysDifference = self::calculateJalaliDaysDifference($startJalali, $currentJalali);

            // حداقل یک روز
            return max(1, $daysDifference);
        }

        // اگر تاریخ شروع قرارداد ندارد، از اول ماه شمسی جاری شروع کن
        $firstDayOfMonth = new Jalalian(
            $currentJalali->getYear(),
            $currentJalali->getMonth(),
            1
        );

        $daysDifference = self::calculateJalaliDaysDifference($firstDayOfMonth, $currentJalali);

        // از اول ماه تا امروز + 1 (چون روز اول هم باید حساب شود)
        return max(1, $daysDifference + 1);
    }

    /**
     * محاسبه تفاوت روزها بین دو تاریخ شمسی
     */
    private static function calculateJalaliDaysDifference(Jalalian $startDate, Jalalian $endDate): int
    {
        try {
            $startCarbon = $startDate->toCarbon();
            $endCarbon = $endDate->toCarbon();

            // محاسبه تفاوت روزها
            return $startCarbon->diffInDays($endCarbon);
        } catch (\Exception $e) {
            // روش جایگزین
            return abs($endDate->getTimestamp() - $startDate->getTimestamp()) / (60 * 60 * 24);
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
