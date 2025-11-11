<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\SellResource\Pages;
use App\Models\Market\Advertisment;
use App\Models\Market\Booth;
use App\Models\Market\Customer;
use App\Models\Market\Market;
use App\Models\Market\Sell;
use App\Models\Market\Shop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellResource extends Resource
{
    protected static ?string $model = Sell::class;

    protected static ?string $navigationIcon = 'gameicon-sell-card';
    protected static ?string $navigationLabel = 'فروش ملک';
    protected static ?string $navigationGroup = 'معاملات املاک';
    protected static ?string $modelLabel = 'فروش';
    protected static ?string $pluralModelLabel = 'فروشات';

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'Financial Manager', 'admin']);
    }

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;

        return $form->schema([




            Forms\Components\Select::make('property')
                ->label('ملک فروشی')
                ->placeholder('انتخاب نوع ملک')
                ->options([
                    'دوکان' => 'دوکان',
                    'غرفه' => 'غرفه',
                    'زمین' => 'زمین',
                    'خانه' => 'خانه',
                ])
                ->reactive()
                ->required(),

            Forms\Components\Select::make('market_id')
                ->label('نام مارکت')
                ->placeholder('انتخاب نام مارکت')
                ->options(function () {
                    $user = Auth::user();
                    return $user->role === 'superadmin'
                        ? Market::pluck('name', 'id')
                        : Market::where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id)
                        ->pluck('name', 'id');
                })
                ->reactive()
                ->visible(fn($get) => in_array($get('property'), ['دوکان', 'غرفه',]))
                ->required(),


            Forms\Components\Select::make('booth_id')
                ->label('شماره غرفه')
                ->placeholder('انتخاب نمبر غرفه')
                ->options(fn($get) => $get('market_id') ? Booth::where('market_id', $get('market_id'))->pluck('number', 'id') : [])
                ->reactive()
                ->visible(fn($get) => $get('property') === 'غرفه'),


            Forms\Components\Select::make('shop_id')
                ->label('شماره دوکان')
                ->placeholder('انتخاب نمبر دوکان')
                ->options(fn($get) => $get('market_id') ? Shop::where('market_id', $get('market_id'))->pluck('number', 'id') : [])
                ->reactive()
                ->visible(fn($get) => $get('property') === 'دوکان'),

            Forms\Components\Select::make('customer_id')
                ->label('خریدار')
                ->placeholder('انتخاب نام خریدار')
                ->options(fn($get) => $get('market_id') ? Customer::where('market_id', $get('market_id'))->pluck('fullname', 'id') : [])
                ->reactive()
                ->required(),

            Forms\Components\Select::make('advertisment_id')
                ->label('انتخاب ملک ثبت شده')
                ->placeholder('انتخاب ملک از قبل ثبت شده')
                ->options(fn($get) => $get('market_id') ? Advertisment::where('market_id', $get('market_id'))->pluck('property', 'id') : [])
                ->reactive()
                ->visible(fn($get) => in_array($get('property'), ['خانه', 'زمین'])),

            Forms\Components\Hidden::make('admin_id')->default(null),

            Forms\Components\Select::make('currency')
                ->label('ارز')
                ->placeholder('انتخاب نوع ارز')
                ->options([
                    'AFN' => 'افغانی',
                    'USD' => 'دالر',
                    'EUR' => 'یورو',
                    'IRR' => 'تومان',
                ])
                ->reactive()
                ->required(),


            Forms\Components\TextInput::make('price')
                ->label('قیمت فروش')
                ->placeholder('مبلغ فروش')
                ->numeric()
                ->required()
                ->reactive()
                ->prefix(fn($get) => match ($get('currency')) {
                    'AFN' => '؋',
                    'USD' => '$',
                    'EUR' => '€',
                    'IRR' => '﷼',
                    default => '',
                })
                ->afterStateUpdated(
                    fn($state, callable $set, callable $get) =>
                    $set('remining', max(($get('price') ?? 0) - ($get('amount') ?? 0), 0))
                ),

            Forms\Components\Select::make('payment_type')
                ->label('نوع پرداخت')
                ->options([
                    'پراخت کامل' => 'پراخت کامل',
                    'قسطی' => 'قسطی',
                ])
                ->reactive()
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->label('مبلغ پرداخت شده')
                ->placeholder('مبلغ پرداخت شده')
                ->numeric()
                ->lazy()
                ->required()
                ->visible(fn($get) => $get('payment_type') === 'قسطی')
                ->prefix(fn($get) => match ($get('currency')) {
                    'AFN' => '؋',
                    'USD' => '$',
                    'EUR' => '€',
                    'IRR' => '﷼',
                    default => '',
                })
                ->afterStateUpdated(
                    fn($state, callable $set, callable $get) =>
                    $set('remining', max(($get('price') ?? 0) - ($get('amount') ?? 0), 0))
                ),

            Forms\Components\TextInput::make('remining')
                ->label('مبلغ باقیمانده')
                ->placeholder('مبلغ باقیمانده')
                ->numeric()
                ->disabled()
                ->dehydrated(true)
                ->visible(fn($get) => $get('payment_type') === 'قسطی')
                ->prefix(fn($get) => match ($get('currency')) {
                    'AFN' => '؋',
                    'USD' => '$',
                    'EUR' => '€',
                    'IRR' => '﷼',
                    default => '',
                })
                ->reactive(),




            Forms\Components\DatePicker::make('date')
                ->label('تاریخ ')
                ->jalali()
                ->required(),

            Forms\Components\Textarea::make('details')
                ->label('توضیحات')
                ->rows(3)
                ->placeholder('توضیحات اختیاری')
                ->nullable(),
        ]);
    }

  public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('property')->label('نوع ملک')->searchable(),
        Tables\Columns\TextColumn::make('market.name')->label('مارکت'),
        Tables\Columns\TextColumn::make('booth.number')->label('شماره غرفه')->searchable(),
        Tables\Columns\TextColumn::make('shop.number')->label('شماره دوکان')->searchable(),
        Tables\Columns\TextColumn::make('customer.fullname')->label('خریدار')->searchable(),
        Tables\Columns\TextColumn::make('advertisment.property')->label('ملک ثبت شده'),
        Tables\Columns\TextColumn::make('price')
            ->label('قیمت')
            ->formatStateUsing(function ($state, $record) {
                $prefix = match ($record->currency) {
                    'AFN' => '؋',
                    'USD' => '$',
                    'EUR' => '€',
                    'IRR' => '﷼',
                    default => '',
                };
                return $prefix . number_format($state);
            })
            ->sortable(),

        Tables\Columns\TextColumn::make('remining')
            ->label('مبلغ باقیمانده')
            ->formatStateUsing(function ($state, $record) {
                $prefix = match ($record->currency) {
                    'AFN' => '؋',
                    'USD' => '$',
                    'EUR' => '€',
                    'IRR' => '﷼',
                    default => '',
                };
                return $prefix . number_format($state ?? 0);
            })
            ->sortable(),

        Tables\Columns\TextColumn::make('currency')->label('ارز'),
        Tables\Columns\TextColumn::make('created_at')->label('تاریخ ایجاد')->dateTime()->sortable(),
    ])
    ->filters([])
    ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('receipt')
             ->button()
            ->label('ثبت رسید')
            ->icon('heroicon-o-receipt-refund')
            ->color('success')
             ->modalWidth('lg') 
            ->visible(fn($record) => $record->remining > 0)
            ->form([
                Forms\Components\TextInput::make('amount')
                    ->label('مبلغ رسید شده')
                    ->numeric()
                    ->required()
                    ->prefix(fn($record) => match ($record->currency) {
                        'AFN' => '؋',
                        'USD' => '$',
                        'EUR' => '€',
                        'IRR' => '﷼',
                        default => '',
                    }),
             
            ])
            ->action(function (array $data, $record): void {
                $amount = (float) $data['amount'];

                DB::connection('market')->table('accountings')->insert([
                    'expanses_type' => 'رسید پرداخت قسط فروش',
                    'currency'      => $record->currency,
                    'paid'          => $amount,
                    'type'          => 'Sell-Receipt',
                    'market_id'     => $record->market_id,
                    'admin_id'      => $record->admin_id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                $newRemining = max(($record->remining ?? 0) - $amount, 0);

                $record->update([
                    'remining' => $newRemining,
                ]);

                \Filament\Notifications\Notification::make()
                    ->title('رسید با موفقیت ثبت شد')
                    ->body('مبلغ ' . number_format($amount) . ' از قسط پرداخت شد.')
                    ->success()
                    ->send();
            }),
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
            'index' => Pages\ListSells::route('/'),
            'create' => Pages\CreateSell::route('/create'),
            'view' => Pages\ViewSell::route('/{record}'),
            'edit' => Pages\EditSell::route('/{record}/edit'),
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
