<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\InventoryResource\Pages;
use App\Models\Import\Inventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'maki-warehouse';
    protected static ?string $navigationGroup = 'گدام ها';
    protected static ?string $navigationLabel = 'گدام بیرونی';
    protected static ?string $modelLabel = 'گدام';
    protected static ?string $pluralModelLabel = 'گدام';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('barcode')
                ->label('بارکد یا نام جنس')
                ->live()
                ->lazy()
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
                        $set('price', $product->price);
                        $set('big_unit_price', $product->big_unit_price);
                        $set('big_quantity', $product->big_quantity);
                        $set('retail_price', $product->retail_price);
                        $set('big_whole_price', $product->big_whole_price);
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
                ->lazy()
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!$state) return;


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
                        $set('big_quantity', $product->big_quantity);
                        $set('retail_price', $product->retail_price);
                        $set('big_whole_price', $product->big_whole_price);
                        $set('import_date', $product->import_date);

                        $set('product_image', $product->product_image ? [$product->product_image] : null);
                    }
                }),

            Forms\Components\Select::make('unit')
                ->label('نوع بسته بندی')
                ->required()
                ->options([
                    'دانه' => 'دانه',
                    'بسته' => 'بسته',
                    'کارتن' => 'کارتن',
                ])
                ->live()
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    $quantity = $get('quantity') ?? 0;
                    $bigQuantity = $get('big_quantity') ?? 1;
                    $bigUnitPrice = $get('big_unit_price') ?? 0;
                    $price = $get('price') ?? 0;

                    if (in_array($state, ['بسته', 'کارتن'])) {
                        $set('all_exist_number', $quantity * $bigQuantity);
                        $set('total_price', $bigUnitPrice * $quantity);
                        if ($bigQuantity != 0) {
                            $set('price', round($bigUnitPrice / $bigQuantity, 2));
                        }
                    } else {
                        $set('all_exist_number', $quantity);
                        $set('total_price', $price * $quantity);
                    }
                }),

            Forms\Components\TextInput::make('quantity')
                ->label('تعداد بسته یا کارتن خریده شده')
                ->required()
                ->numeric()
                ->visible(fn($get) => $get('unit') == 'بسته' || $get('unit') == 'کارتن')
                ->lazy()
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    $unit = $get('unit');
                    $bigQuantity = $get('big_quantity') ?? 1;
                    $bigUnitPrice = $get('big_unit_price') ?? 0;
                    $price = $get('price') ?? 0;

                    if (in_array($unit, ['بسته', 'کارتن'])) {
                        $set('all_exist_number', $state * $bigQuantity);
                        $set('total_price', $bigUnitPrice * $state);
                    } else {
                        $set('all_exist_number', $state);
                        $set('total_price', $price * $state);
                    }
                }),

                Forms\Components\TextInput::make('all_exist_number')
                ->label('تعداد خریده شده')
                ->required()
                ->numeric()
                ->visible(fn($get) => $get('unit') == 'دانه')
                ->lazy()
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    $unit = $get('unit');
                    $bigQuantity = $get('big_quantity') ?? 1;
                    $bigUnitPrice = $get('big_unit_price') ?? 0;
                    $price = $get('price') ?? 0;

                    if (in_array($unit, ['بسته', 'کارتن'])) {
                        $set('all_exist_number', $state * $bigQuantity);
                        $set('total_price', $bigUnitPrice * $state);
                    } else {
                        $set('all_exist_number', $state);
                        $set('total_price', $price * $state);
                    }
                }),
        
            Forms\Components\TextInput::make('big_quantity')
                ->label('تعداد هر بسته یا کارتن (به عدد)')
                ->required()
                ->visible(fn($get) => in_array($get('unit'), ['بسته', 'کارتن']))
                ->lazy()
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    $quantity = $get('quantity') ?? 0;
                    $bigUnitPrice = $get('big_unit_price') ?? 0;

                    $set('all_exist_number', $quantity * $state);
                    if ($state != 0) {
                        $set('price', round($bigUnitPrice / $state, 2));
                    }
                }),

           Forms\Components\TextInput::make('total_price')
            ->label('قیمت مجموعه کل بسته یا کارتن ها')
            ->required()
            ->numeric()
            ->visible(fn($get) => $get('unit') == 'بسته' || $get('unit') == 'کارتن')
            ->disabled()
            ->dehydrated(),

            Forms\Components\Hidden::make('all_exist_number')
                ->label('موجودی به عدد')
                ->required()
                ->dehydrated(true),

            Forms\Components\TextInput::make('price')
            ->label('قیمت خرید فی دانه')
            ->required()
            ->numeric()
            ->lazy()
            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                $unit = $get('unit');
                $quantity = $get('quantity') ?? 0;

                if (!in_array($unit, ['بسته', 'کارتن'])) {
                    $existNumber = $get('all_exist_number') ?? 0; // 👈 استفاده از مقدار واقعی
                    $set('total_price', $state * $existNumber);
                } else {
                    $set('total_price', $state * $quantity);
                }
            }),


            Forms\Components\TextInput::make('total_price')
                ->label('قیمت مجموعه کل بسته یا کارتن ها')
                ->required()
                ->numeric()
                ->visible(fn($get) => $get('unit') == 'بسته' || $get('unit') == 'کارتن')
                ->disabled()
                ->dehydrated(),


            Forms\Components\TextInput::make('total_price')
                ->label('قیمت مجموعه')
                ->required()
                ->numeric()
                ->visible(fn($get) => $get('unit') == 'دانه')
                ->disabled()

                ->dehydrated(),

            Forms\Components\TextInput::make('retail_price')
                ->label('قیمت فروش پرچون ')
                ->required()
                ->numeric(),

            Forms\Components\TextInput::make('big_whole_price')
                ->label('قیمت فروش عمده ')
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
            Tables\Columns\TextColumn::make('barcode')->label('بارکد')->numeric()->sortable(),
            Tables\Columns\TextColumn::make('name')->label('نام جنس')->searchable(),
            Tables\Columns\TextColumn::make('unit')->label('نوع بسته بندی')->searchable(),
            Tables\Columns\TextColumn::make('price')->label('قیمت فی دانه')->numeric()->sortable(),
            Tables\Columns\TextColumn::make('all_exist_number')->label('موجودی به دانه')->searchable(),
            Tables\Columns\TextColumn::make('total_price')->label('قیمت مجموعه')->numeric()->sortable(),
            Tables\Columns\TextColumn::make('retail_price')->label('قیمت پرچون')->numeric()->sortable(),
            Tables\Columns\TextColumn::make('big_whole_price')->label('قیمت عمده')->numeric()->sortable(),
            Tables\Columns\TextColumn::make('brand')->label('ساخت کشور')->searchable(),
            Tables\Columns\ImageColumn::make('product_image')->label('عکس محصول'),
            Tables\Columns\TextColumn::make('created_at')->label('تاریخ ثبت')
                ->sortable()
                ->formatStateUsing(fn($state) => Jalalian::fromDateTime($state)->format('%A %d %m %Y')),
            Tables\Columns\TextColumn::make('updated_at')->label('تاریخ بروزرسانی')->sortable()->toggleable(true),
        ])
            ->headerActions([
                Tables\Actions\Action::make('print')
                    ->label('چاپ اجناس موجود گدام')
                    ->icon('heroicon-o-printer')
                    ->color('danger')
                    ->url(fn() => route('inventory.print', [
                        'user_id' => Auth::id()
                    ]))
                    ->openUrlInNewTab()
                    ->extraAttributes(['class' => 'ml-auto']),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'view' => Pages\ViewInventory::route('/{record}'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
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

        if (Auth::user()?->role === 'superadmin') {
            return $query;
        }

        return $query->where('user_id', Auth::id());
    }
}
