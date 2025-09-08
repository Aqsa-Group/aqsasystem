<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\ShopResource\Pages;
use App\Models\Market\Shop;
use App\Models\Market\Market;
use App\Models\Market\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use DateTime;

class ShopResource extends Resource
{
    protected static ?string $model = Shop::class;
    protected static ?string $navigationIcon = 'fas-shop';
    protected static ?string $navigationGroup = "اطلاعات مارکت";
    protected static ?string $navigationLabel = "دوکان ها";
    protected static ?string $modelLabel = "دوکان";
    protected static ?string $pluralModelLabel = "دوکان";

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['admin', 'superadmin', 'Customer Service']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            BelongsToSelect::make('market_id')
                ->label('مارکت')
                ->relationship('market', 'name')
                ->required()
                ->options(function () {
                    $user = Auth::user();
                    return $user->role === 'superadmin'
                        ? Market::pluck('name', 'id')
                        : Market::where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id)->pluck('name', 'id');
                }),

            Forms\Components\TextInput::make('number')
                ->label('نمبر دوکان')
                ->required()
                ->numeric()
                ->rules([
                    function (callable $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $marketId = $get('market_id');
                            $shopId = $get('id');

                            $exists = Shop::where('market_id', $marketId)
                                ->where('number', $value)
                                ->when($shopId, fn($q) => $q->where('id', '!=', $shopId))
                                ->exists();

                            if ($exists) {
                                $fail('این نمبر دوکان قبلاً در این مارکت ثبت شده است.');
                            }
                        };
                    }
                ]),

            Forms\Components\Select::make('floor')
                ->label('کدام طبق')
                ->options([
                    'زیرزمینی یک' => 'زیرزمینی یک',
                    'زیرزمینی دو' => 'زیرزمینی دو',
                    'یک' => 'یک',
                    'دو' => 'دو',
                    'دو' => 'دو',
                    'سه' => 'سه',
                    'چهار' => 'چهار',
                    'پنج' => 'پنج',

                ])
                ->required(),
            Forms\Components\TextInput::make('size')->label('اندازه دوکان (مترمربع)')->required()->suffix('مترمربع'),
            Forms\Components\TextInput::make('metar_serial')->label('نمبر میتر')->required()->maxLength(255),
            Forms\Components\Select::make('type')
                ->label('نوع قرارداد')
                ->placeholder('نوع قرارداد را انتخاب کنید')
                ->options(function (callable $get) {
                    $shopId = $get('id');
                    $shop = $shopId ? Shop::find($shopId) : null;
                    $options = [
                        'کرایه' => 'کرایه',
                    ];
                    if ($shop && $shop->is_sell == 1) {
                        unset($options['سرقفلی']);
                    }
                    return $options;
                })
                ->reactive()
                ->required(),


            Forms\Components\Select::make('sarqofli')->label('آیا سرقفلی است؟')
                ->options([
                    'بلی' => 'بلی',
                    'نخیر' => 'نخیر'
                ])
                ->reactive(),


            Forms\Components\Select::make('sarqofli_time')->label('زمان سرقفلی')
                ->options([
                    'past' => 'گذشته',
                    'now' => 'فعلا'
                ])
                ->visible(fn(callable $get) => $get('sarqofli') == 'بلی'),


            Forms\Components\Select::make('rent')
                ->label('آیا گروی است؟')
                ->options([
                    'بلی' => 'بلی',
                    'نخیر' => 'نخیر'
                ])
                ->reactive()
                ->visible(fn(callable $get) => $get('sarqofli') !== 'بلی'),


            Forms\Components\Select::make('rent_time')->label('زمان گروی')
                ->options([
                    'past' => 'گذشته',
                    'now' => 'جدید'
                ])
                ->visible(fn(callable $get) => $get('rent') == 'بلی'),



            BelongsToSelect::make('customer_id')
                ->label('نام صاحب دوکان سرقفلی یا گروی')
                ->relationship('customer', 'fullname')
                ->required()
                ->searchable()
                ->options(function () {
                    $user = Auth::user();

                    if ($user->role === 'superadmin') {
                        return Customer::pluck('fullname', 'id');
                    }

                    if ($user->role === 'admin') {
                        return Customer::where('admin_id', $user->id)->pluck('fullname', 'id');
                    }

                    return Customer::where('admin_id', $user->admin_id)->pluck('fullname', 'id');
                })
                ->visible(fn(callable $get) => $get('sarqofli') == 'بلی' || $get('rent') == 'بلی')
                ->reactive(),



            Forms\Components\TextInput::make('sarqofli_price')
                ->label('قیمت سرقفلی')
                ->numeric()
                ->prefix('؋')
                ->visible(fn(callable $get) => $get('sarqofli') == 'بلی')
                ->debounce(500)
                ->afterStateUpdated(function ($state, callable $set) {
                    if (is_numeric($state)) {
                        $set('sarqofli_half_price', intval($state / 2));
                        $set('sarqofli_fa_price', ShopResource::numToFarsiWords($state));
                    } else {
                        $set('sarqofli_half_price', null);
                        $set('sarqofli_fa_price', null);
                    }
                })
                ->extraInputAttributes(['onblur' => 'this.dispatchEvent(new Event("input"))']),

                Forms\Components\Select::make('currency')->label('ارز')
         ->options([
    "AFN" => "افغانی",
    "USD" => "دالر",
         ])
    ->visible(fn(callable $get) => $get('sarqofli') == 'بلی' || $get('rent') == 'بلی'),


            Forms\Components\TextInput::make('sarqofli_fa_price')->label('قیمت سرقفلی به حروف حروف')
                ->dehydrated()
                ->readOnly()
                ->visible(fn(callable $get) => $get('sarqofli') == 'بلی')
                ->maxLength(255),
            Forms\Components\TextInput::make('sarqofli_half_price')->label('مناصفه سرقفلی')
                ->numeric()
                ->visible(fn(callable $get) => $get('sarqofli') == 'بلی')
                ->prefix('؋')
                ->dehydrated()
                ->readOnly(),


            Forms\Components\TextInput::make('rent_price')
                ->label('قیمت گروی')
                ->numeric()
                ->prefix('؋')
                ->visible(fn($get) => $get('rent') == 'بلی')
                ->debounce(500)
                ->afterStateUpdated(function ($state, callable $set) {
                    if (is_numeric($state)) {
                        $set('rent_half_price', intval($state / 2));
                        $set('rent_fa_price', ShopResource::numToFarsiWords($state));
                    } else {
                        $set('rent_half_price', null);
                        $set('rent_fa_price', null);
                    }
                }),

            Forms\Components\TextInput::make('rent_fa_price')->label('قیمت گروی حروف')
                ->dehydrated()
                ->readOnly()
                ->visible(fn(callable $get) => $get('rent') == 'بلی')
                ->maxLength(255),
            Forms\Components\TextInput::make('rent_half_price')->label('مناصفه گروی ')
                ->numeric()
                ->prefix('؋')
                ->dehydrated()
                ->visible(fn(callable $get) => $get('type') == 'گروی')
                ->readOnly(),
            Forms\Components\DatePicker::make('contract_start')
                ->label('تاریخ شروع قرارداد گروی')
                ->jalali()
                ->visible(fn(callable $get) => $get('rent') == 'بلی')
                ->reactive()
                ->afterStateUpdated(function (callable $get, callable $set, $state) {
                    $set('contract_duration', self::calculateDuration(
                        self::convertPersianToEnglish($state),
                        self::convertPersianToEnglish($get('contract_end'))
                    ));
                })
                ->dehydrateStateUsing(fn($state) => self::convertPersianToEnglish($state)),

            Forms\Components\DatePicker::make('contract_end')
                ->label('تاریخ ختم قرارداد گروی')
                ->jalali()
                ->reactive()
                ->visible(fn(callable $get) => $get('rent') == 'بلی')
                ->afterStateUpdated(function (callable $get, callable $set, $state) {
                    $set('contract_duration', self::calculateDuration(
                        self::convertPersianToEnglish($get('contract_start')),
                        self::convertPersianToEnglish($state)
                    ));
                })
                ->dehydrateStateUsing(fn($state) => self::convertPersianToEnglish($state)),
            Forms\Components\TextInput::make('contract_duration')
                ->label('مدت قرارداد گروی')
                ->disabled()
                ->visible(fn(callable $get) => $get('rent') == 'بلی')
                ->dehydrated(true)
                ->reactive()
                ->afterStateHydrated(function (callable $set, $state, callable $get) {
                    $set('contract_duration', self::calculateDuration(
                        self::convertPersianToEnglish($get('contract_start')),
                        self::convertPersianToEnglish($get('contract_end'))
                    ));
                }),


            Forms\Components\TextInput::make('price')
                ->label('قیمت کرایه')
                ->numeric()
                ->prefix('؋')
                ->visible(fn($get) => $get('type') == 'کرایه' || $get('rent') == 'بلی' || $get('sarqofli') == 'بلی')
                ->debounce(500)
                ->afterStateUpdated(function ($state, callable $set) {
                    if (is_numeric($state)) {
                        $set('half_price', intval($state / 2));
                        $set('fa_price', ShopResource::numToFarsiWords($state));
                    } else {
                        $set('half_price', null);
                        $set('fa_price', null);
                    }
                })
                ->extraInputAttributes(['onblur' => 'this.dispatchEvent(new Event("input"))']),
            Forms\Components\Select::make('collect')->label('مسوول جمع آوری کرایه')->options([
                'market' => 'مارکت',
                'person' => 'صاحب دوکان '
            ])
                ->visible(fn(callable $get) => $get('sarqofli') == 'بلی' || $get('rent') == 'بلی'),



            Forms\Components\TextInput::make('fa_price')->label('قیمت کرایه حروف')->dehydrated()->readOnly()->maxLength(255),
            Forms\Components\TextInput::make('half_price')->label('مناصفه کرایه')->numeric()->prefix('؋')->dehydrated()->readOnly(),


            Forms\Components\TextInput::make('north')->label('شمال')->nullable()->visible(fn($get) => $get('sarqofli') === 'بلی'),
            Forms\Components\TextInput::make('east')->label('شرق')->nullable()->visible(fn($get) => $get('sarqofli') === 'بلی'),
            Forms\Components\TextInput::make('south')->label('جنوب')->nullable()->visible(fn($get) => $get('sarqofli') === 'بلی'),
            Forms\Components\TextInput::make('west')->label('غرب')->nullable()->visible(fn($get) => $get('sarqofli') === 'بلی'),
            Forms\Components\TextInput::make('side')->label('طرف')->nullable()->visible(fn($get) => $get('sarqofli') === 'بلی' || $get('rent') == "بلی")
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('market.name')
                ->label('مارکت')
                ->url(fn(Shop $record) => Auth::user()->role === 'superadmin' || $record->market?->admin_id === Auth::id()
                    ? route('filament.market.resources.markets.edit', ['record' => $record->market_id]) : null)
                ->openUrlInNewTab()
                ->badge()
                ->color('success')
                ->icon('heroicon-o-building-storefront')
                ->sortable(),

            TextColumn::make('shopkeeper_id')
                ->label('آیدی دوکاندار')
                ->formatStateUsing(fn($state) => $state ?? '—')
                ->url(fn(Shop $record) => $record->shopkeeper_id
                    ? route('filament.market.resources.shopkeepers.edit', ['record' => $record->shopkeeper_id]) : null)
                ->openUrlInNewTab()
                ->badge()
                ->color('danger')
                ->icon('heroicon-o-user')
                ->numeric()
                ->sortable(),

            TextColumn::make('number')->label("نمبر دوکان")->numeric()->sortable()->searchable(),
            TextColumn::make('floor')->label('طبق')->sortable(),
            Tables\Columns\TextColumn::make('customer_id')
            ->label('وضعیت دوکان')
            ->getStateUsing(function ($record) {
                if ($record->sarqofli === 'بلی') {
                    return 'فروخته شده';
                }
        
                if ($record->rent === 'بلی') {
                    return 'گروی شده';
                }
        
                return 'آزاد';
            })
            ->color(function ($state, $record) {
                if ($record->sarqofli === 'بلی') {
                    return 'danger';
                }
        
                if ($record->rent === 'بلی') {
                    return 'warning';
                }
        
                return 'success';
            }),
        

            TextColumn::make('type')->label('نوع قرارداد')->searchable(),
            TextColumn::make('price')->label('قیمت')->suffix('؋')->sortable(),
        ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('printContract')
                    ->label('چاپ قرارداد')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('contract.print2', $record->id))
                    ->openUrlInNewTab()
                    ->visible(fn($record) => !is_null($record->customer_id)), // فقط وقتی customer_id پر است
            
                Tables\Actions\EditAction::make(),
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $query = parent::getEloquentQuery();
    
        if ($user->role !== 'superadmin') {
            $query->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
        }
    
        // اگر جستجو وجود داشت، نتایج شماره دوکان اول نمایش داده شوند
        if ($search = request('tableFilters')['search'] ?? null) {
            $query->orderByRaw("CASE 
                WHEN number LIKE ? THEN 1
                ELSE 2
                END", ["%{$search}%"]);
        }
    
        return $query;
    }
    
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShops::route('/'),
            'create' => Pages\CreateShop::route('/create'),
            'view' => Pages\ViewShop::route('/{record}'),
            'edit' => Pages\EditShop::route('/{record}/edit'),
        ];
    }

    public static function numToFarsiWords($num): string
    {
        if (!is_numeric($num)) return '';
        $formatter = new \NumberFormatter('fa', \NumberFormatter::SPELLOUT);
        $word = $formatter->format($num);
        $word = str_replace([
            'دویست',
            'سیصد',
            'چهارصد',
            'پانصد',
            'ششصد',
            'هفتصد',
            'هشتصد',
            'نهصد',
        ], [
            'دو صد',
            'سه صد',
            'چهار صد',
            'پنج صد',
            'شش صد',
            'هفت صد',
            'هشت صد',
            'نه صد',
        ], $word);
        return $word;
    }

    private static function convertPersianToEnglish(?string $string): string
    {
        if (!$string) return '';
        return str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $string
        );
    }


    private static function calculateDuration(string $start, string $end): string
    {
        try {
            $startDate = new DateTime($start);
            $endDate = new DateTime($end);
        } catch (\Exception $e) {
            return '';
        }

        if ($endDate < $startDate) {
            return 'تاریخ پایان قبل از شروع است';
        }

        $interval = $startDate->diff($endDate);
        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;

        $parts = [];
        if ($years > 0) $parts[] = $years . ' سال';
        if ($months > 0) $parts[] = $months . ' ماه';
        if ($days > 0) $parts[] = $days . ' روز';

        return implode(' و ', $parts);
    }
}
