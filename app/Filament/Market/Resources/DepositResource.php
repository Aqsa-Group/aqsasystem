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
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use App\Models\Market\DepositLog;



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
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'Financial Manager', 'Cashier', 'admin']);
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        $query = static::getModel()::query();

        if ($user->role !== 'superadmin') {
            $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;
            $query->where('admin_id', $adminId);
        }

        return (string) $query->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
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
                ->options(['AFN' => 'افغانی', 'USD' => 'دالر', 'TOMAN' => 'تومان', 'EUR' => 'یورو'])

                ->disabled()
                ->formatStateUsing(fn($state, $record) => $record?->accounting?->currency),


            Forms\Components\TextInput::make('paid')
                ->label('رسید')
                ->numeric()
                ->required()
                ->debounce(500)
                ->formatStateUsing(fn() => null) // همیشه خالی باشد
                ->rules([
                    fn($record) => function ($attribute, $value, $fail) use ($record) {

                        if (! $record) {
                            return;
                        }

                        $newPayment = (int) $value;

                        if ($newPayment <= 0) {
                            $fail('مبلغ پرداختی باید بیشتر از صفر باشد.');
                            return;
                        }

                        $totalPrice = (int) $record->price;
                        $currentPaid = (int) $record->paid;

                        $remaining = $totalPrice - $currentPaid;

                        if ($newPayment > $remaining) {
                            $fail("مبلغ پرداختی نمی‌تواند از مقدار باقیمانده ({$remaining}) بیشتر باشد.");
                        }
                    },
                ])
                ->afterStateUpdated(function ($set, $state, $record) {

                    $totalPrice = (int) ($record->price ?? 0);
                    $currentPaid = (int) ($record->paid ?? 0);

                    $newPayment = (int) $state;

                    $remaining = max(
                        $totalPrice - ($currentPaid + $newPayment),
                        0
                    );

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
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('accounting.market.name')->label('مارکت')->searchable(),
                Tables\Columns\TextColumn::make('accounting.shop.number')->label('دوکان')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('accounting.booth.number')->label('غرفه')->toggleable(),
                Tables\Columns\TextColumn::make('accounting.shopkeeper.fullname')->label('دوکاندار')->searchable(),
                Tables\Columns\TextColumn::make('accounting.expanses_type')->label('نوع هزینه')->searchable(),
                Tables\Columns\TextColumn::make('accounting.price')->label('مبلغ')->suffix('افغانی'),
                Tables\Columns\TextColumn::make('paid')->label('پرداخت')
                    ->badge()
                    ->color('success')
                    ->extraAttributes(['class' => 'text-white']),
                Tables\Columns\TextColumn::make('remained')->label('باقی')
                    ->badge()
                    ->color('danger')
                    ->extraAttributes(['class' => 'text-white']),
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
                        Forms\Components\DatePicker::make('from')->label('از تاریخ')->jalali(),
                        Forms\Components\DatePicker::make('to')->label('تا تاریخ')->jalali(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn($q) => $q->whereDate('paid_date', '>=', $data['from']))
                            ->when($data['to'] ?? null, fn($q) => $q->whereDate('paid_date', '<=', $data['to']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('correct')
                    ->label('تصحیح')
                    ->icon('heroicon-o-pencil')
                    ->modalHeading('تصحیح مبلغ پرداختی')
                    ->modalButton('ذخیره تصحیح')
                    ->modalWidth('xl')

                    ->form([
                        Forms\Components\TextInput::make('paid')
                            ->label('مبلغ کل پرداختی')
                            ->numeric()
                            ->required()
                            ->default(fn($record) => $record->paid)

                            ->rules([
                                fn($record) => function ($attribute, $value, $fail) use ($record) {

                                    if ($value > $record->price) {
                                        $fail("مبلغ پرداختی نمی‌تواند از کل بدهی ({$record->price}) بیشتر باشد.");
                                    }

                                    if ($value < 0) {
                                        $fail('مبلغ پرداختی نمی‌تواند منفی باشد.');
                                    }
                                }
                            ]),
                    ])

                    ->action(function ($record, array $data) {

                        $newPaid = (int) $data['paid'];
                        $price   = (int) $record->price;

                        $accounting = $record->accounting;

                        /* ===================== GET LAST STATE (FIXED LOGIC) ===================== */
                        $lastLog = \App\Models\Market\DepositLog::where('deposit_id', $record->id)
                            ->latest('id')
                            ->first();

                        $oldPaid = $lastLog?->new_paid ?? 0;
                        $oldRemained = $lastLog?->new_remained ?? ($price - $oldPaid);

                        /* ===================== VALIDATION ===================== */
                        if ($newPaid > $price) {
                            Notification::make()
                                ->danger()
                                ->title('خطا')
                                ->body('مبلغ پرداختی بیشتر از کل بدهی است')
                                ->send();

                            return;
                        }

                        $newRemained = max($price - $newPaid, 0);

                        /* ===================== 1. UPDATE DEPOSIT ===================== */
                        $record->update([
                            'paid'     => $newPaid,
                            'remained' => $newRemained,
                        ]);

                        /* ===================== 2. SYNC ACCOUNTING ===================== */
                        if ($accounting) {
                            $accounting->update([
                                'paid'     => $newPaid,
                                'remained' => $newRemained,
                                'cleared'  => $newRemained <= 0,
                            ]);
                        }

                        /* ===================== 3. RECALCULATE ELECTRICITY CHAIN ===================== */
                        if ($accounting && $accounting->expanses_type === 'پول برق') {

                            $items = \App\Models\Market\Accounting::query()
                                ->where('expanses_type', 'پول برق')
                                ->when($accounting->shop_id, fn($q) => $q->where('shop_id', $accounting->shop_id))
                                ->when($accounting->booth_id, fn($q) => $q->where('booth_id', $accounting->booth_id))
                                ->orderBy('id')
                                ->get();

                            $running = 0;

                            foreach ($items as $item) {

                                $running = ($running + $item->price) - ($item->paid ?? 0);

                                $item->update([
                                    'remained' => $running,
                                    'cleared'  => $running <= 0,
                                ]);

                                if ($item->deposit) {
                                    $item->deposit->update([
                                        'paid'     => $item->paid,
                                        'remained' => $running,
                                    ]);
                                }
                            }
                        }

                        \App\Models\Market\DepositLog::updateOrCreate(
                            [
                                'deposit_id' => $record->id,
                            ],
                            [
                                'user_id'         => auth()->id(),
                                'admin_id'        => $record->admin_id,
                                'expanses_type'   => $accounting?->expanses_type,
                                'market_id'       => $record->market_id,
                                'shop_id'         => $record->shop_id,
                                'shopkeeper_id'   => $record->shopkeeper_id,

                                'market_name'     => $accounting?->market?->name,
                                'shop_number'     => $accounting?->shop?->number,
                                'shopkeeper_name' => $accounting?->shopkeeper?->fullname,

                                'old_paid'        => $oldPaid,
                                'old_remained'    => $oldRemained,

                                'new_paid'        => $newPaid,
                                'new_remained'    => $newRemained,
                            ]
                        );
                        /* ===================== SUCCESS ===================== */
                        Notification::make()
                            ->success()
                            ->title('تصحیح با موفقیت انجام شد')
                            ->send();
                    })
                    ->visible(fn($record) => true),
            ])
            ->bulkActions([]);
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
            ->where(function ($q) {
                $q->where('remained', '>', 0)
                    ->orWhereNull('remained');
            })
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
