<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\LoanResource\Pages;
use App\Models\Market\Loan;
use App\Models\Market\Market;
use App\Models\Market\Customer;
use App\Models\Market\Shopkeeper;
use App\Models\Market\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Builder; 
use Carbon\Carbon;



class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;
    protected static ?string $navigationIcon = 'hugeicons-money-remove-02';
    protected static ?string $navigationGroup = "بخش مالی";
    protected static ?string $navigationLabel = "بردگی";
    protected static ?string $modelLabel = "بردگی";
    protected static ?string $pluralModelLabel = "بردگی ها";

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
                ->label('مارکت')
                ->options(Market::where('admin_id', $adminId)->pluck('name', 'id'))
                ->required()
                ->reactive(),

            Forms\Components\Select::make('person')
                ->label('نوع شخص')
                ->options([
                    'مشتری' => 'مشتری',
                    'دوکاندار' => 'دوکاندار',
                    'کارمند' => 'کارمند',
                ])
                ->required()
                ->reactive(),

            Forms\Components\Select::make('customer_id')
                ->label('انتخاب مشتری')
                ->options(function (callable $get) use ($adminId) {
                    $marketId = $get('market_id');
                    if ($get('person') !== 'مشتری' || !$marketId) return [];
                    return Customer::where('market_id', $marketId)
                        ->where('admin_id', $adminId)
                        ->pluck('fullname', 'id');
                })
                ->visible(fn(callable $get) => $get('person') === 'مشتری')
                ->required(fn(callable $get) => $get('person') === 'مشتری')
                ->searchable()
                ->reactive(),

            Forms\Components\Select::make('shopkeeper_id')
                ->label('انتخاب دوکاندار')
                ->options(function (callable $get) use ($adminId) {
                    $marketId = $get('market_id');
                    if ($get('person') !== 'دوکاندار' || !$marketId) return [];
                    return Shopkeeper::where('market_id', $marketId)
                        ->where('admin_id', $adminId)
                        ->pluck('fullname', 'id');
                })
                ->visible(fn(callable $get) => $get('person') === 'دوکاندار')
                ->required(fn(callable $get) => $get('person') === 'دوکاندار')
                ->searchable()
                ->reactive(),

            Forms\Components\Select::make('staff_id')
                ->label('انتخاب کارمند')
                ->options(function (callable $get) use ($adminId) {
                    $marketId = $get('market_id');
                    if ($get('person') !== 'کارمند' || !$marketId) return [];
                    return Staff::where('market_id', $marketId)
                        ->where('admin_id', $adminId)
                        ->pluck('fullname', 'id');
                })
                ->visible(fn(callable $get) => $get('person') === 'کارمند')
                ->required(fn(callable $get) => $get('person') === 'کارمند')
                ->searchable()
                ->reactive(),

            Forms\Components\Hidden::make('admin_id')
                ->default($adminId),

            Forms\Components\Select::make('type')
                ->label('برداشت از')
                ->options(
                    DB::connection('market')->table('accountings')
                        ->distinct()
                        ->pluck('expanses_type', 'expanses_type')
                        ->toArray()
                )
                ->required(),

            Forms\Components\Select::make('currency')
                ->label('ارز')
                ->options([
                    'AFN' => 'افغانی',
                    'USD' => 'دالر',
                    'EUR' => 'یورو',
                    'IRR' => 'تومان',
                ])
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->label('مقدار برداشت')
                ->numeric()
                ->required(),

            Forms\Components\DatePicker::make('date')
                ->label('تاریخ')
                ->jalali()
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('توضیحات')
                ->rows(3)
                ->placeholder('دلیل برداشت را وارد کنید...')
                ->nullable(),

        ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('market.name')->label('مارکت'),
                Tables\Columns\TextColumn::make('shopkeeper.fullname')->label('دوکاندار'),
                Tables\Columns\TextColumn::make('customer.fullname')->label('مشتری'),
                Tables\Columns\TextColumn::make('staff.fullname')->label('کارمند'),
                Tables\Columns\TextColumn::make('type')->label('نوع صندوق'),
                Tables\Columns\TextColumn::make('currency')->label('ارز'),
                Tables\Columns\TextColumn::make('amount')->label('مبلغ اصلی')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('total_paid')
                    ->label('پرداخت‌شده')
                    ->state(fn($record) => $record->totalPaid())
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_amount')
                    ->label('باقیمانده')
                    ->state(fn($record) => $record->remainingAmount())
                    ->sortable()
                    ->color(fn($record) => $record->remainingAmount() === 0 ? 'success' : 'warning'),
                    Tables\Columns\TextColumn::make('date')
                    ->label('تاریخ')
                    ->formatStateUsing(function ($state) {
                        $dt = Carbon::parse($state)->setTimezone('Asia/Kabul');
                        return Jalalian::fromDateTime($dt)->format('Y/m/d');
                    }),
                

                Tables\Columns\TextColumn::make('created_at')
                    ->label('زمان ثبت')
                    ->formatStateUsing(function ($state) {
                        $dt = Carbon::parse($state)->setTimezone('Asia/Kabul'); 
                        return $dt->format('g:i A');  // فقط زمان به فرمت ۱۲ ساعته
                    }),
                                
                    
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('printContract')
                ->label('چاپ بردگی')
                ->icon('heroicon-o-printer')
                ->url(fn($record) => route('loan.print', $record->id))
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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'view' => Pages\ViewLoan::route('/{record}'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
    }

}
