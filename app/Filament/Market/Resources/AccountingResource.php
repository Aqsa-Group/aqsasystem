<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\AccountingResource\Pages;
use App\Models\Market\Accounting;
use App\Models\Market\Booth;
use App\Models\Market\Market;
use App\Models\Market\Shop;
use App\Models\Market\Deposit;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class AccountingResource extends Resource
{
    protected static ?string $model = Accounting::class;
    protected static ?string $navigationIcon = 'simpleline-calculator';
    protected static ?string $navigationGroup = 'بخش مالی';
    protected static ?string $navigationLabel = 'حسابداری';
    protected static ?string $pluralModelLabel = 'حسابداری';
    protected static ?string $modelLabel = 'هزینه';

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['admin', 'Cashier', 'superadmin']);
    }

    public static function form(Form $form): Form
    {
        $user = Auth::user();

        $calculateDates = function (callable $get, callable $set) {
            $price = (float) ($get('price') ?? 0);
            $expType = $get('expanses_type');
            $type = $get('type');

            if ($expType === 'کرایه') {
                $from = null;
                $monthlyRate = 0;

                if ($type === 'دوکان' && $shopId = $get('shop_id')) {
                    $shop = Shop::with('shopkeeper')->find($shopId);
                    $monthlyRate = $shop->price;

                    $lastExpiration = Accounting::where('shop_id', $shop->id)
                        ->where('expanses_type', 'کرایه')
                        ->latest('expiration_date')
                        ->value('expiration_date');


                    $from = $get('paid_date') ? Carbon::parse($get('paid_date')) : ($lastExpiration ? Carbon::parse($lastExpiration) : ($shop->shopkeeper?->contract_start ? Carbon::parse($shop->shopkeeper->contract_start) : now()));
                } elseif ($type === 'غرفه' && $boothId = $get('booth_id')) {
                    $booth = Booth::with('shopkeeper')->find($boothId);
                    $monthlyRate = $booth->price;

                    $lastExpiration = Accounting::where('booth_id', $booth->id)
                        ->where('expanses_type', 'کرایه')
                        ->latest('expiration_date')
                        ->value('expiration_date');

                    $from = $get('paid_date') ? Carbon::parse($get('paid_date')) : ($lastExpiration ? Carbon::parse($lastExpiration) : ($booth->shopkeeper?->contract_start ? Carbon::parse($booth->shopkeeper->contract_start) : now()));
                }

                if ($from && $monthlyRate > 0) {
                    $monthsWithFraction = $price / $monthlyRate;
                    $wholeMonths = floor($monthsWithFraction);
                    $fractionMonth = $monthsWithFraction - $wholeMonths;
                    $extraDays = $fractionMonth * 30;

                    $to = $from->copy()->addMonths($wholeMonths)->addDays($extraDays);

                    $set('paid_date', $from);
                    $set('expiration_date', $to);
                }
            }

            if ($expType === 'پول برق') {
                $from = null;

                if ($type === 'دوکان' && $shopId = $get('shop_id')) {
                    $shop = Shop::with('shopkeeper')->find($shopId);
                    $lastExpiration = Accounting::where('shop_id', $shop->id)
                        ->where('expanses_type', 'پول برق')
                        ->latest('expiration_date')
                        ->value('expiration_date');

                    $from = $get('paid_date') ? Carbon::parse($get('paid_date')) : ($lastExpiration ? Carbon::parse($lastExpiration) : ($shop->shopkeeper?->contract_start ? Carbon::parse($shop->shopkeeper->contract_start) : now()));
                } elseif ($type === 'غرفه' && $boothId = $get('booth_id')) {
                    $booth = Booth::with('shopkeeper')->find($boothId);
                    $lastExpiration = Accounting::where('booth_id', $booth->id)
                        ->where('expanses_type', 'پول برق')
                        ->latest('expiration_date')
                        ->value('expiration_date');

                    $from = $get('paid_date') ? Carbon::parse($get('paid_date')) : ($lastExpiration ? Carbon::parse($lastExpiration) : ($booth->shopkeeper?->contract_start ? Carbon::parse($booth->shopkeeper->contract_start) : now()));
                }

                if ($from) {
                    $to = $from->copy()->addMonths(2);
                    $set('paid_date', $from);
                    $set('expiration_date', $to);
                }
            }
        };


        $updateCalculatedPrice = function (callable $get, callable $set) {
            $current = $get('current_degree');
            $past = $get('past_degree');
            $unitPrice = $get('degree_price');

            if (is_numeric($current) && is_numeric($past) && is_numeric($unitPrice)) {
                $difference = max(0, $current - $past);
                $set('price', $difference * $unitPrice);
            }
        };

        return $form->schema([
            Forms\Components\Select::make('type')
                ->label('نوع')
                ->options(['دوکان' => 'دوکان', 'غرفه' => 'غرفه'])
                ->default('دوکان')
                ->searchable()
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('market_id', null);
                    $set('shop_id', null);
                    $set('booth_id', null);
                    $set('shopkeeper_id', null);
                    $set('price', null);
                    $set('meter_serial', null);
                    $set('past_degree', null);
                    $set('current_degree', null);
                    $set('paid_date', null);
                    $set('expiration_date', null);
                }),

            // comment added
            Forms\Components\Select::make('expanses_type')
                ->label('نوع مصرف')
                ->options(['کرایه' => 'کرایه', 'تحت الملکی' => 'تحت الملکی', 'پول برق' => 'پول برق', 'پول آب' => 'پول آب', 'صفایی' => 'صفایی'])
                ->default('پول برق')
                ->searchable()
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($get, $set) use ($calculateDates, $updateCalculatedPrice) {
                    if ($get('expanses_type') === 'کرایه') {
                        $calculateDates($get, $set);
                    }
                    if ($get('expanses_type') === 'پول برق') {
                        $updateCalculatedPrice($get, $set);
                        $calculateDates($get, $set);
                    }
                }),


            Forms\Components\Select::make('market_id')
                ->label('مارکت مربوطه')
                ->options(function () use ($user) {
                    return Market::when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
                        ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
                        ->pluck('name', 'id');
                })
                ->default(fn() => Market::where('name', 'فردوسی')->value('id'))
                ->searchable()
                ->reactive()
                ->visible(fn($get) => in_array($get('type'), ['دوکان', 'غرفه']))
                ->afterStateUpdated(function (callable $set) {
                    $set('shop_id', null);
                    $set('booth_id', null);
                    $set('shopkeeper_id', null);
                    $set('price', null);
                    $set('meter_serial', null);
                    $set('past_degree', null);
                    $set('current_degree', null);
                    $set('paid_date', null);
                    $set('expiration_date', null);
                }),

            Forms\Components\Select::make('shop_id')
                ->label('نمبر دوکان')
                ->options(
                    fn($get) =>
                    $get('market_id')
                        ? Shop::where('market_id', $get('market_id'))->pluck('number', 'id')
                        : []
                )
                ->visible(fn($get) => $get('type') === 'دوکان')
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) use ($calculateDates) {

                    $shop = Shop::with('shopkeeper')->find($state);

                    if ($shop) {
                        // فقط برای نمایش
                        $set('shopkeeper_name', $shop->shopkeeper?->fullname);

                        // منطق قبلی تو
                        $set('shopkeeper_id', $shop->shopkeeper_id);
                        $set('price', $get('expanses_type') === 'کرایه' ? $shop->price : $get('price'));
                        $set('meter_serial', $shop->metar_serial);

                        $last = Accounting::where('shop_id', $shop->id)
                            ->where('expanses_type', 'پول برق')
                            ->latest()
                            ->first();

                        $set('past_degree', $last?->current_degree ?? 0);
                    }

                    $calculateDates($get, $set);
                }),

            Forms\Components\TextInput::make('shopkeeper_name')
                ->label('نام دوکاندار')
                ->visible(fn($get) => filled($get('shop_id')))
                ->disabled()
                ->dehydrated(false),


            Forms\Components\Select::make('booth_id')
                ->label('نمبر غرفه')
                ->options(fn($get) => $get('market_id') ? Booth::where('market_id', $get('market_id'))->pluck('number', 'id') : [])
                ->visible(fn($get) => $get('type') === 'غرفه')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) use ($calculateDates) {
                    $booth = Booth::find($state);
                    $set('shopkeeper_id', $booth?->shopkeeper_id);

                    if ($booth) {
                        $set('price', $get('expanses_type') === 'کرایه' ? $booth->price : $get('price'));
                        $set('meter_serial', $booth->metar_serial);
                        $last = Deposit::where('booth_id', $booth->id)->where('expanses_type', 'پول برق')->latest()->first();
                        $set('past_degree', $last?->current_degree ?? 0);
                    }

                    $calculateDates($get, $set);
                }),


            Forms\Components\Hidden::make('shopkeeper_id'),


            Forms\Components\TextInput::make('meter_serial')
                ->label('سریال میتر')
                ->disabled()
                ->dehydrated(true)
                ->visible(fn($get) => $get('expanses_type') === 'پول برق'),

            Forms\Components\TextInput::make('past_degree')
                ->label('درجه قبلی')
                ->numeric()
                ->reactive()
                ->visible(fn($get) => $get('expanses_type') === 'پول برق')
                ->afterStateUpdated(fn($state, callable $set, callable $get) => $updateCalculatedPrice($get, $set)),

            Forms\Components\TextInput::make('current_degree')
                ->label('درجه فعلی')
                ->numeric()
                ->reactive()
                ->visible(fn($get) => $get('expanses_type') === 'پول برق')
                ->afterStateUpdated(fn($state, callable $set, callable $get) => $updateCalculatedPrice($get, $set)),

            Forms\Components\TextInput::make('degree_price')
                ->label('قیمت هر کیلوات')
                ->numeric()
                ->dehydrated(true)
                ->debounce(1000)
                ->visible(fn($get) => $get('expanses_type') === 'پول برق')
                ->afterStateUpdated(fn($state, callable $set, callable $get) => $updateCalculatedPrice($get, $set)),

            Forms\Components\TextInput::make('price')
                ->label('مبلغ')
                ->numeric()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) use ($calculateDates, $updateCalculatedPrice) {
                    $calculateDates($get, $set);
                    $updateCalculatedPrice($get, $set);
                }),


            Forms\Components\Select::make('currency')
                ->label('واحد پول')
                ->options(['AFN' => 'افغانی', 'USD' => 'دالر', 'TOMAN' => 'تومان', 'EUR' => 'یورو'])
                ->default('AFN')
                ->dehydrated(true)
                ->required(),

            Forms\Components\DatePicker::make('paid_date')
                ->label('از تاریخ')
                ->jalali()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) use ($calculateDates) {
                    $calculateDates($get, $set);
                }),

            Forms\Components\DatePicker::make('expiration_date')
                ->label('تا تاریخ')
                ->jalali()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();

        /** دریافت مارکت‌های قابل مشاهده توسط کاربر */
        $markets = $user->role === 'superadmin'
            ? Market::pluck('id')
            : Market::where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id)
            ->pluck('id');

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('نوع'),
                Tables\Columns\TextColumn::make('market.name')->label('مارکت')->searchable(),
                Tables\Columns\TextColumn::make('shop.number')

                    ->label('نمبر دوکان')
                    ->toggleable(true)
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('shop', function ($q) use ($search) {
                            $q->where('number', $search);
                        });
                    }),
                Tables\Columns\TextColumn::make('booth.number')
                    ->label('نمبر غرفه')
                    ->toggleable(true)
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('booth', function ($q) use ($search) {
                            $q->where('number', $search);
                        });
                    }),
                Tables\Columns\TextColumn::make('shopkeeper_name')
                    ->label('نام دوکاندار')
                    ->getStateUsing(function ($record) {
                        return
                            $record->shop?->shopkeeper?->fullname
                            ?? $record->booth?->shopkeeper?->fullname
                            ?? '—';
                    }),

                Tables\Columns\TextColumn::make('shopkeeper.fullname')->label('نام دوکاندار')->searchable(),
                Tables\Columns\TextColumn::make('expanses_type')->label('نوع مصرف')->searchable(),
                Tables\Columns\TextColumn::make('price')->label('مبلغ')->suffix(' افغانی'),
                Tables\Columns\TextColumn::make('currency')->label('واحد پول'),

                Tables\Columns\TextColumn::make('paid')
                    ->label('پرداخت شده')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('remained')
                    ->label('باقی مانده')
                    ->badge()
                    ->color('danger'),

                Tables\Columns\IconColumn::make('cleared')
                    ->boolean()
                    ->label('پرداخت کامل؟'),

                Tables\Columns\TextColumn::make('paid_date')
                    ->label('از تاریخ')
                    ->formatStateUsing(
                        fn($state) =>
                        $state ? Jalalian::fromDateTime($state)->format('Y/m/d') : '—'
                    ),

                Tables\Columns\TextColumn::make('expiration_date')
                    ->label('تا تاریخ')
                    ->formatStateUsing(
                        fn($state) =>
                        $state ? Jalalian::fromDateTime($state)->format('Y/m/d') : '—'
                    ),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('زمان ثبت')
                    ->formatStateUsing(
                        fn($state) =>
                        Carbon::parse($state)->setTimezone('Asia/Kabul')->format('g:i A')
                    ),
            ])

            ->filters([

                /* ********************* فیلتر مارکت ********************* */
                SelectFilter::make('market_id')
                    ->label('مارکت')
                    ->options(fn() => Market::whereIn('id', $markets)->pluck('name', 'id'))
                    ->searchable(),

                /* ********************* فیلتر نوع ********************* */
                SelectFilter::make('type')
                    ->label('نوع')
                    ->options([
                        'دوکان' => 'دوکان',
                        'غرفه' => 'غرفه',
                    ]),

                /* ********************* فیلتر نمبر دوکان ********************* */
                SelectFilter::make('shop_id')
                    ->label('نمبر دوکان')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) use ($markets) {
                        return Shop::whereIn('market_id', $markets)
                            ->where('number', $search)
                            ->pluck('number', 'id');
                    })
                    ->getOptionLabelUsing(
                        fn($value) =>
                        Shop::find($value)?->number
                    ),

                /* ********************* فیلتر نمبر غرفه ********************* */
                SelectFilter::make('booth_id')
                    ->label('نمبر غرفه')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) use ($markets) {
                        return Booth::whereIn('market_id', $markets)
                            ->where('number', $search) // فقط دقیقاً همون نمبر
                            ->pluck('number', 'id');
                    })
                    ->getOptionLabelUsing(
                        fn($value) =>
                        Booth::find($value)?->number
                    ),


                /* ********************* فیلتر نوع مصرف ********************* */
                SelectFilter::make('expanses_type')
                    ->label('نوع مصرف')
                    ->options([
                        'کرایه' => 'کرایه',
                        'تحت الملکی' => 'تحت الملکی',
                        'پول برق' => 'پول برق',
                        'پول آب' => 'پول آب',
                        'صفایی' => 'صفایی',
                    ]),


                SelectFilter::make('floor')
                    ->label('طبق')
                    ->options(function () use ($markets) {
                        $shopFloors = Shop::whereIn('market_id', $markets)->pluck('floor')->unique()->filter()->toArray();
                        $boothFloors = Booth::whereIn('market_id', $markets)->pluck('floor')->unique()->filter()->toArray();
                        $floors = array_unique(array_merge($shopFloors, $boothFloors));
                        sort($floors);

                        return array_combine($floors, $floors);
                    })
                    ->query(function (Builder $query, array $data) {
                        $floor = $data['value'] ?? null;
                        if ($floor) {
                            $query->whereHas('shop', fn($q) => $q->where('floor', $floor))
                                ->orWhereHas('booth', fn($q) => $q->where('floor', $floor));
                        }
                    }),







                /* ********************* فیلتر تاریخ پرداخت ********************* */
                Filter::make('paid_date')
                    ->label('تاریخ پرداخت')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('از تاریخ')->jalali(),
                        Forms\Components\DatePicker::make('until')->label('تا تاریخ')->jalali(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn($q, $date) =>
                                $q->whereDate('paid_date', '>=', Jalalian::fromFormat('Y-m-d H:i:s', $date)->toCarbon())
                            )
                            ->when(
                                $data['until'],
                                fn($q, $date) =>
                                $q->whereDate('paid_date', '<=', Jalalian::fromFormat('Y-m-d H:i:s', $date)->toCarbon())
                            );
                    }),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('چاپ')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('accounting.print.view', $record))
                    ->openUrlInNewTab(),
            ])

            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),

                // چاپ انتخابی - فقط آی‌دی‌های انتخاب شده
                Tables\Actions\BulkAction::make('printSelected')
                    ->label('چاپ انتخابی')
                    ->icon('heroicon-o-printer')
                    ->action(function ($records) {
                        $ids = $records->pluck('id')->join(',');
                        // ارسال به route مناسب برای bulk print
                        return redirect()->route('accounting.print.bulk', ['ids' => $ids]);
                    })
                    ->requiresConfirmation()
                    ->color('primary'),

                // چاپ فیلتر شده - کل رکوردهای فیلتر شده
                Tables\Actions\BulkAction::make('printFilteredBulk')
                    ->label('چاپ فیلتر شده')
                    ->icon('heroicon-o-printer')
                    ->action(function ($records, $livewire) {
                        // گرفتن فیلترهای اعمال شده
                        $filters = $livewire->tableFilters;

                        // ساخت پارامترهای فیلتر
                        $params = [];

                        if (!empty($filters['market_id'])) {
                            $params['market_id'] = $filters['market_id'];
                        }

                        if (!empty($filters['type'])) {
                            $params['type'] = $filters['type'];
                        }

                        if (!empty($filters['shop_id'])) {
                            $params['shop_id'] = $filters['shop_id'];
                        }

                        if (!empty($filters['booth_id'])) {
                            $params['booth_id'] = $filters['booth_id'];
                        }

                        if (!empty($filters['expanses_type'])) {
                            $params['expanses_type'] = $filters['expanses_type'];
                        }

                        if (!empty($filters['floor'])) {
                            $params['floor'] = $filters['floor'];
                        }

                        // فیلتر تاریخ
                        if (!empty($filters['paid_date']['from'])) {
                            $params['paid_date_from'] = $filters['paid_date']['from'];
                        }

                        if (!empty($filters['paid_date']['until'])) {
                            $params['paid_date_until'] = $filters['paid_date']['until'];
                        }

                        // هدایت به صفحه چاپ با پارامترهای فیلتر
                        $url = route('accounting.print.filtered', $params);

                        // باز کردن در تب جدید
                        return redirect()->away($url);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('چاپ فیلتر شده')
                    ->modalSubheading('آیا می‌خواهید تمام رکوردهای فیلتر شده چاپ شوند؟')
                    ->modalButton('بله، چاپ کن')
                    ->color('success'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountings::route('/'),
            'create' => Pages\CreateAccounting::route('/create'),
            'view' => Pages\ViewAccounting::route('/{record}'),
            'edit' => Pages\EditAccounting::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        $query = parent::getEloquentQuery();

        if ($user->role === 'superadmin') {
            return $query; // همه رکوردها
        }

        // فقط admin یا کارمند محدود
        return $query->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
    }

    
}