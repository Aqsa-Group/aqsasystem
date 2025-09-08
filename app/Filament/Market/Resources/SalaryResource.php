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

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;
    protected static ?string $navigationIcon = 'fluentui-people-money-24';
    protected static ?string $navigationGroup = 'بخش مالی';
    protected static ?string $navigationLabel = 'پرداخت معاش کارمندان';
    protected static ?string $modelLabel = 'پرداخت  ';
 
    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin' , 'Financial Manager' ,'admin']);
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
                    $staffId = $get('staff_id');
                    if ($staffId) {
                        $lastRemained = Salary::where('staff_id', $staffId)
                            ->where('market_id', $state)
                            ->where(function ($query) {
                                $query->whereNull('is_reduce')->orWhere('is_reduce', false);
                            })
                            ->latest()
                            ->value('remained') ?? 0;

                        $set('last_remained', $lastRemained);
                    }
                }),

            Forms\Components\Select::make('staff_id')
                ->label('نام کارمند')
                ->options(fn(callable $get) =>
                    Staff::where('market_id', $get('market_id'))
                        ->where('admin_id', $adminId)
                        ->pluck('fullname', 'id')
                )
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $staff = Staff::find($state);
                    if ($staff) {
                        $set('salary', $staff->salary);

                        $loan = Loan::where('staff_id', $state)
                            ->where('market_id', $get('market_id'))
                            ->latest()
                            ->first();

                        if ($loan) {
                            $set('loan_id', $loan->id);
                            $set('loan', $loan->remainingAmount());
                        } else {
                            $set('loan_id', null);
                            $set('loan', 0);
                        }

                        $lastRemained = Salary::where('staff_id', $state)
                            ->where('market_id', $get('market_id'))
                            ->where(function ($query) {
                                $query->whereNull('is_reduce')->orWhere('is_reduce', false);
                            })
                            ->latest()
                            ->value('remained') ?? 0;

                        $set('last_remained', $lastRemained);
                    }
                }),

            Forms\Components\TextInput::make('salary')->label('معاش')->numeric()->disabled()->dehydrated(),
            Forms\Components\TextInput::make('loan')->label('میزان قرض فعلی')->numeric()->disabled(),
            Forms\Components\TextInput::make('last_remained')->label('باقی‌مانده معاش قبلی')->numeric()->disabled()->dehydrated(),

            Forms\Components\Toggle::make('is_reduce')
                ->label('آیا قرضه رسید شود؟')
                ->reactive()
                ->default(false)
                ->visible(fn (callable $get) => $get('loan') > 0),

            Forms\Components\TextInput::make('reduce_loan')
                ->label('مقدار رسید قرضه')
                ->numeric()
                ->debounce(500)
                ->visible(fn(callable $get) => $get('is_reduce'))
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $loan = $get('loan') ?? 0;
                    $salary = $get('salary') ?? 0;
                    $set('new_loan', $loan - $state);
                    $set('paid', $state);
                    $set('remained', 0); // باقی معاش در حالت قرضه ذخیره نمی‌شود
                    $set('final_remained', $salary - $state); // فقط برای نمایش
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
                ->debounce(500)
                ->visible(fn(callable $get) => !$get('is_reduce'))
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $salary = $get('salary') ?? 0;
                    $lastRemained = $get('last_remained') ?? 0;
                    $set('remained', ($salary - $state) + $lastRemained);
                }),

            Forms\Components\TextInput::make('final_remained')
                ->label('باقیمانده نهایی')
                ->numeric()
                ->disabled()
                ->dehydrated(false)
                ->visible(fn(callable $get) => $get('is_reduce')),

            Forms\Components\TextInput::make('remained')->label('باقیمانده معاش')->numeric()->disabled()->dehydrated(),

            Forms\Components\Select::make('reduce_from')
                ->label('برداشت از صندوق')
                ->options(fn() =>
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

            Forms\Components\DatePicker::make('paid_date')->jalali()->label('تاریخ پرداخت'),

            Forms\Components\Hidden::make('loan_id'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('market.name')->label('مارکت'),
            Tables\Columns\TextColumn::make('staff.fullname')->label('کارمند'),
            Tables\Columns\TextColumn::make('salary')->label('معاش'),
            Tables\Columns\TextColumn::make('reduce_loan')->label('رسید قرض'),
            Tables\Columns\TextColumn::make('remained')->label('باقی معاش'),
            Tables\Columns\TextColumn::make('reduce_from')->label('برداشت از'),
            Tables\Columns\TextColumn::make('paid_date')
                ->label('تاریخ پرداخت')
                ->formatStateUsing(fn($state) =>
                    \Morilog\Jalali\Jalalian::fromDateTime($state)->format('Y/m/d') .
                    ' - ' .
                    date('g:i A', strtotime($state))
                ),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('print')
                ->label('چاپ')
                ->icon('heroicon-o-printer')
                ->url(fn ($record) => route('salary.print', $record))
                ->openUrlInNewTab(),
        ])
        
        ->bulkActions([
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
