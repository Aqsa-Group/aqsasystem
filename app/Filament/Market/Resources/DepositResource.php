<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\DepositResource\Pages;
use App\Models\Market\Deposit;
use App\Models\Market\Market;
use App\Models\Market\Shop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;


class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;
    protected static ?string $navigationIcon = "heroicon-o-exclamation-triangle";
    protected static ?string $navigationGroup = 'بخش مالی';
    protected static ?string $navigationLabel = 'تسویه نشده';
    protected static ?string $pluralModelLabel = 'پرداختی‌ها';
    protected static ?string $modelLabel = 'پرداختی';

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'Financial Manager', 'admin']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('market')
                ->label('مارکت')
                ->disabled()
                ->formatStateUsing(fn($state, $record) => $record?->accounting?->market?->name),

            Forms\Components\TextInput::make('shop')
                ->label('شماره دوکان')
                ->visible(fn($record) => filled($record?->accounting?->shop))
                ->disabled()
                ->formatStateUsing(fn($state, $record) => $record?->accounting?->shop?->number),

            Forms\Components\TextInput::make('booth')
                ->label('شماره غرفه')
                ->visible(fn($record) => filled($record?->accounting?->booth))
                ->disabled()
                ->formatStateUsing(fn($state, $record) => $record?->accounting?->booth?->number),

            Forms\Components\TextInput::make('shopkeeper')
                ->label('دوکاندار')
                ->disabled()
                ->formatStateUsing(fn($state, $record) => $record?->accounting?->shopkeeper?->fullname),

            Forms\Components\TextInput::make('expanses_type')
                ->label('نوع مصارف')
                ->disabled()
                ->formatStateUsing(fn($state, $record) => $record?->accounting?->expanses_type),

            Forms\Components\TextInput::make('price')
                ->label('مبلغ کل')
                ->numeric()
                ->disabled()
                ->formatStateUsing(fn($state, $record) => $record?->accounting?->price),

            Forms\Components\Select::make('currency')
                ->label('واحد پول')
                ->options(['AFN' => 'افغانی', 'USD' => 'دالر'])
                ->disabled()
                ->formatStateUsing(fn($state, $record) => $record?->accounting?->currency),


            Forms\Components\TextInput::make('paid')
                ->label('رسید')
                ->numeric()
                ->required()
                ->debounce(500)
                ->default(fn() => null)
                ->afterStateUpdated(function ($get, $set, $state) {
                    $totalPrice = $get('price') ?? 0;
                    $lastPaid = $get('old_paid') ?? 0;
                    $totalPaid = $lastPaid + (int)$state;
                    $remaining = max($totalPrice - $totalPaid, 0);
                    $set('remained', $remaining);
                }),



            Forms\Components\Hidden::make('remained')
                ->dehydrated()
                ->default(fn($record) => $record->remained ?? 0),


            Forms\Components\DatePicker::make('paid_date')
                ->label('تاریخ')
                ->jalali()
                ->required(),

            Forms\Components\Hidden::make('old_paid')
                ->default(fn($record) => $record->paid ?? 0),



        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('accounting.market.name')->label('مارکت')->searchable(),
                Tables\Columns\TextColumn::make('accounting.shop.number')->label('دوکان')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('accounting.booth.number')->label('غرفه')->toggleable(),
                Tables\Columns\TextColumn::make('accounting.shopkeeper.fullname')->label('دوکاندار')->searchable(),
                Tables\Columns\TextColumn::make('accounting.expanses_type')->label('نوع هزینه')->searchable(),
                Tables\Columns\TextColumn::make('accounting.price')->label('مبلغ')->suffix('افغانی'),
                Tables\Columns\TextColumn::make('paid')->label('پرداخت'),
                Tables\Columns\TextColumn::make('remained')->label('باقی'),
                Tables\Columns\TextColumn::make('paid_date')
                ->label('تا تاریخ')
                ->formatStateUsing(fn($state) => Jalalian::fromDateTime($state)->format('Y/m/d')),
                Tables\Columns\TextColumn::make('created_at')
                ->label('زمان ثبت ')
                ->formatStateUsing(function ($state) {
                    $dt = Carbon::parse($state)->setTimezone('Asia/Kabul'); 
                    return $dt->format('g:i A'); 
                }),
                
            
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('market_id')
                    ->label('مارکت')
                    ->searchable()
                    ->options(fn() => Market::pluck('name', 'id')->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        if (blank($data['value'] ?? null)) return $query;
                        return $query->whereHas('accounting', fn($q) => $q->where('market_id', $data['value']));
                    }),

                Tables\Filters\SelectFilter::make('shop_id')
                    ->label('شماره دوکان')
                    ->searchable()
                    ->options(fn() => Shop::pluck('number', 'id')->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        if (blank($data['value'] ?? null)) return $query;
                        return $query->whereHas('accounting', fn($q) => $q->where('shop_id', $data['value']));
                    }),

                Tables\Filters\SelectFilter::make('expanses_type')
                    ->label('نوع مصرف')
                    ->searchable()
                    ->options(fn() => \App\Models\Market\Accounting::distinct()->pluck('expanses_type', 'expanses_type')->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        if (blank($data['value'] ?? null)) return $query;
                        return $query->whereHas('accounting', fn($q) => $q->where('expanses_type', $data['value']));
                    }),

                Tables\Filters\Filter::make('paid_date')
                    ->label('تاریخ پرداخت')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('از تاریخ'),
                        Forms\Components\DatePicker::make('to')->label('تا تاریخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn($q) => $q->whereDate('paid_date', '>=', $data['from']))
                            ->when($data['to'] ?? null, fn($q) => $q->whereDate('paid_date', '<=', $data['to']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('paid_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeposits::route('/'),
            'edit' => Pages\EditDeposit::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        $query = parent::getEloquentQuery()
            ->where('remained', '>', 0)
            ->with([
                'accounting',
                'accounting.market',
                'accounting.shop',
                'accounting.booth',
                'accounting.shopkeeper',
            ]);

        if ($user->role === 'superadmin') {
            return $query;
        }

        if ($user->role === 'admin') {
            return $query->where('admin_id', $user->id);
        }

        return $query->where('admin_id', $user->admin_id);
    }
}
