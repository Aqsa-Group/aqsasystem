<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\WarehouseResource\Pages;
use App\Models\Import\Inventory;
use App\Models\Import\Warehouse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'polaris-inventory-icon';
    protected static ?string $navigationGroup = 'گدام ها';
    protected static ?string $navigationLabel = 'موجودی دوکان';
    protected static ?string $modelLabel = 'موجودی دوکان';
    protected static ?string $pluralModelLabel = 'موجودی';
    protected static ?int $navigationSort = 4;

    // پرچم جلوگیری از حلقه بازگشتی
    protected static bool $updating = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('barcode')
                ->label('بارکد جنس')
                ->live()
                ->debounce(500)
                ->extraAttributes([
                    'onkeydown' => "if(event.key === 'Enter'){ event.preventDefault(); return false; }",
                ])
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!$state) return;

                    $state = self::convertFarsiNumbersToEnglish($state);
                    $set('barcode', $state);

                    $product = Inventory::where('user_id', Auth::id())
                        ->where(function ($query) use ($state) {
                            $query->where('barcode', $state)
                                ->orWhere('name', 'like', '%' . $state . '%');
                        })
                        ->first();

                    if ($product) {
                        $set('name', $product->name);
                        $set('unit', $product->unit);
                        $set('brand', $product->brand);
                        $set('price', number_format((float)$product->price, 2, '.', ''));
                        $set('big_unit_price', number_format((float)$product->big_unit_price, 2, '.', ''));
                        $set('retail_price', number_format((float)$product->retail_price, 2, '.', ''));
                        $set('big_whole_price', number_format((float)$product->big_whole_price, 2, '.', ''));
                        $set('big_quantity', $product->big_quantity);
                        $set('import_date', $product->import_date);
                        $set('product_image', $product->product_image ? [$product->product_image] : null);
                    } else {
                        $set('name', '');
                        $set('unit', '');
                        $set('brand', '');
                        $set('price', null);
                        $set('big_unit_price', null);
                        $set('big_quantity', null);
                        $set('retail_price', null);
                        $set('big_whole_price', null);
                        $set('import_date', null);
                        $set('product_image', null);
                    }
                }),

            Forms\Components\Hidden::make('user_id')
                ->default(fn() => Auth::id()),

      Forms\Components\TextInput::make('name')
    ->label('نام جنس')
    ->required()
    ->live()
    ->debounce(500)
    ->afterStateUpdated(function ($state, callable $set, $record) {

        // در صفحه ویرایش هیچ کاری انجام نده
        if ($record) {
            return;
        }

        if (blank($state)) {
            return;
        }

        $product = Inventory::where('user_id', Auth::id())
            ->where(function ($query) use ($state) {
                $query->where('name', 'like', '%' . $state . '%')
                    ->orWhere('barcode', $state);
            })
            ->first();

        if ($product) {
            $set('barcode', $product->barcode);
            $set('unit', $product->unit);
            $set('brand', $product->brand);
            $set('price', $product->price);
            $set('big_unit_price', $product->big_unit_price);
            $set('retail_price', $product->retail_price);
            $set('big_whole_price', $product->big_whole_price);
            $set('big_quantity', $product->big_quantity);
            $set('import_date', $product->import_date);
            $set('product_image', $product->product_image ? [$product->product_image] : null);
        }
    }),

            Forms\Components\Select::make('unit')
                ->label('واحد')
                ->required()
                ->options([
                    'دانه' => 'دانه',
                    'بسته' => 'بسته',
                    'کارتن' => 'کارتن',
                ])
                ->live()
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    if (static::$updating) return;
                    static::$updating = true;

                    $quantity = $get('quantity') ?? 0;
                    $allExistNumber = $get('all_exist_number') ?? 0;
                    $bigQuantity = $get('big_quantity') ?? 1;
                    $bigUnitPrice = $get('big_unit_price') ?? 0;
                    $price = $get('price') ?? 0;

                    if (in_array($state, ['بسته', 'کارتن'])) {
                        if ($quantity > 0) {
                            $set('all_exist_number', $quantity * $bigQuantity);
                            $set('total_price', $bigUnitPrice * $quantity);
                        } elseif ($allExistNumber > 0) {
                            $newQuantity = intdiv($allExistNumber, $bigQuantity);
                            $set('quantity', $newQuantity);
                            $set('all_exist_number', $newQuantity * $bigQuantity);
                            $set('total_price', $bigUnitPrice * $newQuantity);
                        }
                        if ($bigQuantity != 0 && $bigUnitPrice > 0) {
                            $set('price', round($bigUnitPrice / $bigQuantity, 2));
                        }
                    } else {
                        if ($quantity > 0) {
                            $set('all_exist_number', $quantity);
                            $set('total_price', $price * $quantity);
                        } elseif ($allExistNumber > 0) {
                            $set('quantity', $allExistNumber);
                            $set('total_price', $price * $allExistNumber);
                        }
                    }

                    static::$updating = false;
                }),

            Forms\Components\TextInput::make('quantity')
                ->label('تعداد برداشت از گدام بر حسب بسته یا کارتن')
                ->required()
                ->numeric()
                ->live()
                ->debounce(500)
                ->visible(fn($get) => in_array($get('unit'), ['بسته', 'کارتن']))
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    // جلوگیری از حلقه بازگشتی
                    if (static::$updating) return;
                    
                    // بررسی اینکه واحد مجاز باشد
                    $unit = $get('unit');
                    if (!in_array($unit, ['بسته', 'کارتن'])) {
                        return;
                    }
                    
                    // اعتبارسنجی مقدار
                    if ($state === null || $state === '' || !is_numeric($state)) {
                        return;
                    }
                    
                    static::$updating = true;
                    
                    $bigQuantity = (int)($get('big_quantity') ?? 1);
                    $bigUnitPrice = (float)($get('big_unit_price') ?? 0);
                    $state = (float)$state;
                    
                    // محاسبه all_exist_number بر اساس تعداد بسته/کارتن
                    $set('all_exist_number', $state * $bigQuantity);
                    $set('total_price', $bigUnitPrice * $state);
                    
                    static::$updating = false;
                }),

            Forms\Components\TextInput::make('all_exist_number')
                ->label('تعداد برداشت از گدام (به عدد)')
                ->required()
                ->numeric()
                ->live()
                ->debounce(500)
                ->visible(fn($get) => $get('unit') == 'دانه')
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    // جلوگیری از حلقه بازگشتی
                    if (static::$updating) return;
                    
                    // بررسی اینکه واحد دانه باشد
                    $unit = $get('unit');
                    if ($unit != 'دانه') {
                        return;
                    }
                    
                    // اعتبارسنجی مقدار
                    if ($state === null || $state === '' || !is_numeric($state)) {
                        return;
                    }
                    
                    static::$updating = true;
                    
                    $price = (float)($get('price') ?? 0);
                    $state = (float)$state;
                    
                    // همگام‌سازی با quantity و محاسبه total_price
                    $set('quantity', $state);
                    $set('total_price', $price * $state);
                    
                    static::$updating = false;
                }),

            Forms\Components\TextInput::make('big_quantity')
                ->label('تعداد هر بسته یا کارتن (به عدد)')
                ->required()
                ->numeric()
                ->visible(fn($get) => in_array($get('unit'), ['بسته', 'کارتن']))
                ->live()
                ->debounce(500)
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    if (static::$updating) return;
                    static::$updating = true;

                    $quantity = (float)($get('quantity') ?? 0);
                    $bigUnitPrice = (float)($get('big_unit_price') ?? 0);
                    $state = (int)$state;

                    $set('all_exist_number', $quantity * $state);
                    if ($state != 0 && $bigUnitPrice > 0) {
                        $set('price', round($bigUnitPrice / $state, 2));
                    }

                    static::$updating = false;
                }),

            Forms\Components\TextInput::make('big_unit_price')
                ->label('قیمت کل بسته یا کارتن')
                ->required()
                ->visible(fn($get) => in_array($get('unit'), ['بسته', 'کارتن']))
                ->numeric()
                ->live()
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    if (static::$updating) return;
                    static::$updating = true;

                    $quantity = (float)($get('quantity') ?? 0);
                    $bigQuantity = (int)($get('big_quantity') ?? 1);
                    $state = (float)$state;

                    if ($bigQuantity != 0) {
                        $set('price', round($state / $bigQuantity, 2));
                    }
                    $set('total_price', $state * $quantity);
                    $set('all_exist_number', $quantity * $bigQuantity);

                    static::$updating = false;
                }),

            Forms\Components\TextInput::make('price')
                ->label('قیمت خرید فی دانه')
                ->required()
                ->numeric()
                ->live()
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    if (static::$updating) return;
                    static::$updating = true;

                    $unit = $get('unit');
                    $quantity = (float)($get('quantity') ?? 0);
                    $allExistNumber = (float)($get('all_exist_number') ?? 0);
                    $state = (float)$state;

                    if ($unit == 'دانه') {
                        $set('total_price', $state * $quantity);
                        if ($quantity == 0 && $allExistNumber > 0) {
                            $set('total_price', $state * $allExistNumber);
                        }
                    } elseif (in_array($unit, ['بسته', 'کارتن'])) {
                        $bigQuantity = (int)($get('big_quantity') ?? 1);
                        $set('big_unit_price', $state * $bigQuantity);
                        $set('total_price', ($state * $bigQuantity) * $quantity);
                        $set('all_exist_number', $quantity * $bigQuantity);
                    }

                    static::$updating = false;
                }),

            Forms\Components\TextInput::make('total_price')
                ->label('قیمت مجموعه')
                ->required()
                ->numeric()
                ->disabled()
                ->dehydrated(),

            Forms\Components\TextInput::make('retail_price')
                ->label('قیمت فروش پرچون')
                ->required()
                ->numeric(),

            Forms\Components\TextInput::make('big_whole_price')
                ->label('قیمت فروش عمده')
                ->required()
                ->numeric(),

            Forms\Components\TextInput::make('brand')
                ->label('ساخت کشور')
                ->required(),

            Forms\Components\DatePicker::make('import_date')
                ->label('تاریخ وارد جنس')
                ->jalali(),

            Forms\Components\FileUpload::make('product_image')
                ->label('عکس محصول')
                ->image()
                ->directory('uploads/Product_image')
                ->visibility('public')
                ->optimize('webp')
                ->resize(50),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('barcode')->label('بارکد')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('name')->label('نام جنس')->searchable(),
            Tables\Columns\TextColumn::make('unit')->label('نوع بسته بندی')->searchable(),
            Tables\Columns\TextColumn::make('price')->label('قیمت فی دانه')->sortable(),
            Tables\Columns\TextColumn::make('all_exist_number')->label('موجودی به دانه')->searchable(),
            Tables\Columns\TextColumn::make('total_price')->label('قیمت مجموعه')->sortable(),
            Tables\Columns\TextColumn::make('retail_price')->label('قیمت پرچون')->sortable(),
            Tables\Columns\TextColumn::make('big_whole_price')->label('قیمت عمده')->sortable(),
            Tables\Columns\TextColumn::make('brand')->label('ساخت کشور')->searchable(),
            Tables\Columns\ImageColumn::make('product_image')->label('عکس محصول'),
            Tables\Columns\TextColumn::make('created_at')->label('تاریخ ثبت')
                ->sortable()
                ->formatStateUsing(fn($state) => Jalalian::fromDateTime($state)->format('%A %d %m %Y')),
            Tables\Columns\TextColumn::make('updated_at')->label('تاریخ بروزرسانی')->sortable()->toggleable(true),
        ])
            ->headerActions([
                Tables\Actions\Action::make('print')
                    ->label('چاپ اجناس موجود دوکان')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn() => route('warehouse.print', [
                        'user_id' => Auth::id()
                    ]))
                    ->openUrlInNewTab()
                    ->extraAttributes(['class' => 'ml-auto']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'view' => Pages\ViewWarehouse::route('/{record}'),
            'edit' => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }

    private static function convertFarsiNumbersToEnglish(string $input): string
    {
        $farsiDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($farsiDigits, $englishDigits, $input);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()?->role !== 'superadmin') {
            $query->where('user_id', Auth::id());
        }

        return $query->orderByRaw('CASE WHEN all_exist_number = 0 THEN 1 ELSE 0 END')
            ->orderByDesc('all_exist_number');
    }
}