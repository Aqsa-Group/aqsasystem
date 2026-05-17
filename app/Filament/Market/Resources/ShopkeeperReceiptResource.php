<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\ShopkeeperReceiptResource\Pages;
use App\Models\Market\Market;
use App\Models\Market\Shop;
use App\Models\Market\Booth;
use App\Models\Market\ShopkeeperReceipt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class ShopkeeperReceiptResource extends Resource
{
    protected static ?string $model = ShopkeeperReceipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'بخش مالی';
    protected static ?string $navigationLabel = 'رسید دوکانداران';
    protected static ?string $pluralModelLabel = 'رسید دوکانداران';
    protected static ?string $modelLabel = 'رسید';

    /* =================================================================
     *  فرم
     * ================================================================= */
    public static function form(Form $form): Form
    {
        $user = Auth::user();

        return $form->schema([
            Forms\Components\Select::make('type')
                ->label('نوع')
                ->options(['دوکان' => 'دوکان', 'غرفه' => 'غرفه'])
                ->reactive()
                ->required()
                ->afterStateUpdated(function (callable $set) {
                    $set('market_id', null);
                    $set('shop_id', null);
                    $set('booth_id', null);
                    $set('shopkeeper_id', null);
                    $set('shopkeeper_name', null);
                }),

            Forms\Components\Select::make('expanses_type')
                ->label('نوع مصرف')
                ->options([
                    'کرایه'      => 'کرایه',
                    'تحت الملکی' => 'تحت الملکی',
                    'پول برق'    => 'پول برق',
                    'پول آب'     => 'پول آب',
                    'صفایی'      => 'صفایی',
                ])
                ->required(),

            Forms\Components\Select::make('market_id')
                ->label('مارکت')
                ->options(function () use ($user) {
                    return Market::when(
                        $user->role === 'admin',
                        fn($q) => $q->where('admin_id', $user->id)
                    )
                    ->when(
                        $user->role !== 'superadmin' && $user->role !== 'admin',
                        fn($q) => $q->where('admin_id', $user->admin_id)
                    )
                    ->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->reactive()
                ->required()
                ->visible(fn(callable $get) => in_array($get('type'), ['دوکان', 'غرفه']))
                ->afterStateUpdated(function (callable $set) {
                    $set('shop_id', null);
                    $set('booth_id', null);
                    $set('shopkeeper_id', null);
                    $set('shopkeeper_name', null);
                }),

            Forms\Components\Select::make('shop_id')
                ->label('نمبر دوکان')
                ->options(function (callable $get) {
                    if (!$get('market_id')) return [];
                    return Shop::where('market_id', $get('market_id'))->pluck('number', 'id');
                })
                ->searchable()
                ->preload()
                ->reactive()
                ->visible(fn(callable $get) => $get('type') === 'دوکان')
                ->afterStateUpdated(function ($state, callable $set) {
                    $shop = Shop::with('shopkeeper')->find($state);
                    if ($shop) {
                        $set('shopkeeper_id', $shop->shopkeeper_id);
                        $set('shopkeeper_name', $shop->shopkeeper?->fullname);
                    } else {
                        $set('shopkeeper_id', null);
                        $set('shopkeeper_name', null);
                    }
                }),

            Forms\Components\Select::make('booth_id')
                ->label('نمبر غرفه')
                ->options(function (callable $get) {
                    if (!$get('market_id')) return [];
                    return Booth::where('market_id', $get('market_id'))->pluck('number', 'id');
                })
                ->searchable()
                ->preload()
                ->reactive()
                ->visible(fn(callable $get) => $get('type') === 'غرفه')
                ->afterStateUpdated(function ($state, callable $set) {
                    $booth = Booth::with('shopkeeper')->find($state);
                    if ($booth) {
                        $set('shopkeeper_id', $booth->shopkeeper_id);
                        $set('shopkeeper_name', $booth->shopkeeper?->fullname);
                    } else {
                        $set('shopkeeper_id', null);
                        $set('shopkeeper_name', null);
                    }
                }),

            Forms\Components\TextInput::make('shopkeeper_name')
                ->label('دوکاندار')
                ->disabled()
                ->dehydrated(false)
                ->visible(fn(callable $get) => filled($get('shop_id')) || filled($get('booth_id'))),

            Forms\Components\Hidden::make('shopkeeper_id'),

            Forms\Components\TextInput::make('amount')
                ->label('مبلغ')
                ->numeric()
                ->required(),

            Forms\Components\Select::make('currency')
                ->label('واحد پول')
                ->options(['AFN' => 'افغانی', 'USD' => 'دالر', 'EUR' => 'یورو', 'IRR' => 'ریال'])
                ->default('AFN')
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('توضیحات')
                ->nullable(),

            Forms\Components\DatePicker::make('date')
                ->label('تاریخ')
                ->jalali()
                ->required(),

            Forms\Components\Hidden::make('admin_id')
                ->default(fn() => auth()->id()),
        ]);
    }

    /* =================================================================
     *  جدول
     * ================================================================= */
    public static function table(Table $table): Table
    {
        $user = Auth::user();

        // تعریف مارکت‌های مجاز (دقیقاً مانند AccountingResource)
        $markets = $user->role === 'superadmin'
            ? Market::pluck('id')
            : Market::where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id)->pluck('id');

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('نوع')->sortable(),
                Tables\Columns\TextColumn::make('expanses_type')->label('نوع مصرف')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('market.name')->label('مارکت')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('shop.number')
                    ->label('نمبر دوکان')
                    ->visible(fn($record) => $record?->type === 'دوکان')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booth.number')
                    ->label('نمبر غرفه')
                    ->visible(fn($record) => $record?->type === 'غرفه')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shopkeeper.fullname')
                    ->label('دوکاندار')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('مبلغ')
                    ->sortable()
                    ->suffix(' افغانی'),
                Tables\Columns\TextColumn::make('currency')
                    ->label('ارز')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'AFN' => 'افغانی', 'USD' => 'دالر', 'EUR' => 'یورو', 'IRR' => 'ریال',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('date')
                    ->label('تاریخ')
                    ->formatStateUsing(fn($state) => $state ? Jalalian::fromDateTime($state)->format('Y/m/d') : '—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('زمان ثبت')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                // فیلتر مارکت
                SelectFilter::make('market_id')
                    ->label('مارکت')
                    ->options(fn() => Market::whereIn('id', $markets)->pluck('name', 'id'))
                    ->searchable(),

                // فیلتر نوع (دوکان/غرفه)
                SelectFilter::make('type')
                    ->label('نوع')
                    ->options(['دوکان' => 'دوکان', 'غرفه' => 'غرفه']),

                // فیلتر نمبر دوکان (فقط در دیتابیس رسیدها، با محدودیت مارکت‌های مجاز)
                SelectFilter::make('shop_id')
                    ->label('نمبر دوکان')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) use ($markets) {
                        return Shop::whereIn('market_id', $markets)
                            ->where('number', 'like', "%{$search}%")
                            ->pluck('number', 'id');
                    })
                    ->getOptionLabelUsing(fn($value) => Shop::find($value)?->number),

                // فیلتر نمبر غرفه
                SelectFilter::make('booth_id')
                    ->label('نمبر غرفه')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) use ($markets) {
                        return Booth::whereIn('market_id', $markets)
                            ->where('number', 'like', "%{$search}%")
                            ->pluck('number', 'id');
                    })
                    ->getOptionLabelUsing(fn($value) => Booth::find($value)?->number),

                // فیلتر نوع مصرف
                SelectFilter::make('expanses_type')
                    ->label('نوع مصرف')
                    ->options([
                        'کرایه' => 'کرایه', 'تحت الملکی' => 'تحت الملکی',
                        'پول برق' => 'پول برق', 'پول آب' => 'پول آب', 'صفایی' => 'صفایی',
                    ]),

                // فیلتر طبق (بر اساس shop یا booth)
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
                            $query->where(function ($q) use ($floor) {
                                $q->whereHas('shop', fn($q) => $q->where('floor', $floor))
                                  ->orWhereHas('booth', fn($q) => $q->where('floor', $floor));
                            });
                        }
                    }),

                // فیلتر تاریخ (بر اساس فیلد date)
                Filter::make('date')
                    ->label('تاریخ')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('از تاریخ')->jalali(),
                        Forms\Components\DatePicker::make('until')->label('تا تاریخ')->jalali(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn($q, $date) => $q->whereDate('date', '>=', Jalalian::fromFormat('Y-m-d', $date)->toCarbon())
                            )
                            ->when(
                                $data['until'],
                                fn($q, $date) => $q->whereDate('date', '<=', Jalalian::fromFormat('Y-m-d', $date)->toCarbon())
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // چاپ تکی
                Tables\Actions\Action::make('print')
                    ->label('چاپ')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('shopkeeper-receipt.print.view', ['record' => $record]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),

                // چاپ انتخابی
                Tables\Actions\BulkAction::make('printSelected')
                    ->label('چاپ انتخابی')
                    ->icon('heroicon-o-printer')
                    ->action(function ($records) {
                        $ids = $records->pluck('id')->join(',');
                        return redirect()->route('shopkeeper-receipt.print.bulk', ['ids' => $ids]);
                    })
                    ->requiresConfirmation()
                    ->color('primary'),

                // چاپ فیلتر شده
                Tables\Actions\BulkAction::make('printFilteredBulk')
                    ->label('چاپ فیلتر شده')
                    ->icon('heroicon-o-printer')
                    ->action(function ($records, $livewire) {
                        $filters = $livewire->tableFilters;
                        $params = [];

                        if (!empty($filters['market_id']))        $params['market_id'] = $filters['market_id'];
                        if (!empty($filters['type']))             $params['type'] = $filters['type'];
                        if (!empty($filters['shop_id']))          $params['shop_id'] = $filters['shop_id'];
                        if (!empty($filters['booth_id']))         $params['booth_id'] = $filters['booth_id'];
                        if (!empty($filters['expanses_type']))    $params['expanses_type'] = $filters['expanses_type'];
                        if (!empty($filters['floor']))            $params['floor'] = $filters['floor'];
                        if (!empty($filters['date']['from']))     $params['date_from'] = $filters['date']['from'];
                        if (!empty($filters['date']['until']))    $params['date_until'] = $filters['date']['until'];

                        $url = route('shopkeeper-receipt.print.filtered', $params);
                        return redirect()->away($url);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('چاپ فیلتر شده')
                    ->modalSubheading('آیا تمام رکوردهای فیلتر شده چاپ شوند؟')
                    ->modalButton('بله، چاپ کن')
                    ->color('success'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $query = parent::getEloquentQuery();
        if ($user->role === 'superadmin') return $query;
        return $query->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListShopkeeperReceipts::route('/'),
            'create' => Pages\CreateShopkeeperReceipt::route('/create'),
            'edit'   => Pages\EditShopkeeperReceipt::route('/{record}/edit'),
        ];
    }
}