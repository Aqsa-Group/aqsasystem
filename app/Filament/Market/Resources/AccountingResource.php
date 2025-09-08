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
        
                    
                    $from = $get('paid_date') ? Carbon::parse($get('paid_date')) :
                        ($lastExpiration ? Carbon::parse($lastExpiration) :
                        ($shop->shopkeeper?->contract_start ? Carbon::parse($shop->shopkeeper->contract_start) : now()));
        
                } elseif ($type === 'غرفه' && $boothId = $get('booth_id')) {
                    $booth = Booth::with('shopkeeper')->find($boothId);
                    $monthlyRate = $booth->price;
        
                    $lastExpiration = Accounting::where('booth_id', $booth->id)
                        ->where('expanses_type', 'کرایه')
                        ->latest('expiration_date')
                        ->value('expiration_date');
        
                    $from = $get('paid_date') ? Carbon::parse($get('paid_date')) :
                        ($lastExpiration ? Carbon::parse($lastExpiration) :
                        ($booth->shopkeeper?->contract_start ? Carbon::parse($booth->shopkeeper->contract_start) : now()));
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
        
                    $from = $get('paid_date') ? Carbon::parse($get('paid_date')) :
                        ($lastExpiration ? Carbon::parse($lastExpiration) :
                        ($shop->shopkeeper?->contract_start ? Carbon::parse($shop->shopkeeper->contract_start) : now()));
                } elseif ($type === 'غرفه' && $boothId = $get('booth_id')) {
                    $booth = Booth::with('shopkeeper')->find($boothId);
                    $lastExpiration = Accounting::where('booth_id', $booth->id)
                        ->where('expanses_type', 'پول برق')
                        ->latest('expiration_date')
                        ->value('expiration_date');
        
                    $from = $get('paid_date') ? Carbon::parse($get('paid_date')) :
                        ($lastExpiration ? Carbon::parse($lastExpiration) :
                        ($booth->shopkeeper?->contract_start ? Carbon::parse($booth->shopkeeper->contract_start) : now()));
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

                Forms\Components\Select::make('expanses_type')
                ->label('نوع مصرف')
                ->options(['کرایه' => 'کرایه', 'تحت الملکی' => 'تحت الملکی', 'پول برق' => 'پول برق', 'پول آب' => 'پول آب', 'صفایی' => 'صفایی'])
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
                ->options(fn($get) => $get('market_id') ? Shop::where('market_id', $get('market_id'))->pluck('number', 'id') : [])
                ->visible(fn($get) => $get('type') === 'دوکان')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) use ($calculateDates) {
                    $shop = Shop::find($state);
                    $set('shopkeeper_id', $shop?->shopkeeper_id);

                    if ($shop) {
                        $set('price', $get('expanses_type') === 'کرایه' ? $shop->price : $get('price'));
                        $set('meter_serial', $shop->metar_serial);
                        $last = Deposit::where('shop_id', $shop->id)->where('expanses_type', 'پول برق')->latest()->first();
                        $set('past_degree', $last?->current_degree ?? 0);
                    }

                    $calculateDates($get, $set);
                }),

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
                ->reactive()
                ->visible(fn($get) => $get('expanses_type') === 'پول برق')
                ->afterStateUpdated(fn($state, callable $set, callable $get) => $updateCalculatedPrice($get, $set)),

            Forms\Components\TextInput::make('price')
                ->label('مبلغ')
                ->numeric()
                ->required()
                ->lazy()
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
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('نوع'),
                Tables\Columns\TextColumn::make('market.name')->label('مارکت'),
                Tables\Columns\TextColumn::make('shop.number')->label('نمبر دوکان')->toggleable(true),
                Tables\Columns\TextColumn::make('booth.number')->label('نمبر غرفه')->toggleable(true),
                Tables\Columns\TextColumn::make('shopkeeper.fullname')->label('نام دوکاندار'),
                Tables\Columns\TextColumn::make('expanses_type')->label('نوع مصرف'),
                Tables\Columns\TextColumn::make('price')->label('مبلغ')->suffix(' افغانی'),
                Tables\Columns\TextColumn::make('currency')->label('واحد پول'),
                Tables\Columns\TextColumn::make('paid')->label('پرداخت شده'),
                Tables\Columns\TextColumn::make('remained')->label('باقی مانده'),
                Tables\Columns\IconColumn::make('cleared')->boolean()->label('پرداخت کامل؟'),
                Tables\Columns\TextColumn::make('paid_date')->label('از تاریخ')->formatStateUsing(fn($state) => $state ? Jalalian::fromDateTime($state)->format('Y/m/d') : '—'),
                Tables\Columns\TextColumn::make('expiration_date')->label('تا تاریخ')->formatStateUsing(fn($state) => $state ? Jalalian::fromDateTime($state)->format('Y/m/d') : '—'),
                Tables\Columns\TextColumn::make('created_at')->label('زمان ثبت')->formatStateUsing(fn($state) => Carbon::parse($state)->setTimezone('Asia/Kabul')->format('g:i A')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('چاپ')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('accounting.print', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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

        if ($user->role !== 'superadmin') {
            $query = $query->whereHas('market', fn($q) => $q->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id));
        }

        return $query->orderBy('created_at', 'desc');
    }
}
