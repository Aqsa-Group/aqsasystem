<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\BuyResource\Pages;
use App\Filament\Import\Resources\BuyResource\RelationManagers;
use App\Models\Import\Buy;
use App\Models\Import\Company;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use App\Models\Import\Warehouse;
use Morilog\Jalali\Jalalian;


class BuyResource extends Resource
{
    protected static ?string $model = Buy::class;

    protected static ?string $navigationIcon = 'tabler-basket-dollar';
    protected static ?string $navigationLabel = 'خرید جنس';
    protected static ?string $pluralModelLabel = 'خرید';
    protected static ?string $modelLabel = 'خرید';

    protected static ?string $navigationGroup = 'بخش خرید و فروش';
    protected static ?int $navigationSort = 9;

       public static function form(Form $form): Form
    {
        return $form
            ->schema([
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

                    $product = Warehouse::where('user_id', Auth::id())
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
                ->default(fn () => Auth::id()),

            Forms\Components\TextInput::make('name')
                ->label('نام جنس')
                ->required()
                ->live()
                ->debounce(500)
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!$state) return;

                    
                    $product = Warehouse::where('user_id', Auth::id())
                    ->where(function ($query) use ($state) {
                        $query->where('name', 'like', '%' . $state . '%')
                              ->orWhere('barcode', $state);
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
                ->default('دانه')
                ->reactive()
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

            Forms\Components\TextInput::make('price')
            ->label('قیمت خرید فی دانه')
            ->required()
            ->numeric()
            ->lazy()
            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                $unit = $get('unit');
                $quantity = $get('quantity') ?? 0;

                if (!in_array($unit, ['بسته', 'کارتن'])) {
                    $existNumber = $get('all_exist_number') ?? 0; 
                    $set('total_price', round($state * $existNumber, 2));
                } else {
                    $set('total_price', round($state * $quantity, 2));
                }
            })
            ->formatStateUsing(fn ($state) => number_format((float)$state, 2, '.', '')),




               Forms\Components\TextInput::make('total_price')
                ->label('قیمت مجموعه')
                ->required()
                ->numeric()
                ->disabled()
                ->reactive()
                ->dehydrated(true)
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    if ($get('currency') === 'USD') {
                        $set('amount', $state);
                    }
                    $paid = $get('paid') ?? 0;
                    $set('remaining', max(($get('amount') ?? 0) - $paid, 0));
                }),
                    

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

               Forms\Components\Select::make('company_id')
                    ->label('خرید از شرکت')
                    ->options(Company::pluck('name', 'id')->toArray())
                    ->required(),
            
      Forms\Components\Select::make('currency')
    ->label('خرید به ارز')
    ->options([
        'AFN' => 'افغانی',
        'USD' => 'دالر',
    ])
    ->reactive()
    ->afterStateHydrated(function ($state, callable $set, callable $get) {
        if ($state === 'USD') {
            $set('amount', $get('total_price') ?? 0);
        }
    })
    ->afterStateUpdated(function (callable $set, $state, callable $get) {
        $totalPrice = $get('total_price') ?? 0;

        if ($state === 'USD') {
            $set('amount', $totalPrice);
        } elseif ($state === 'AFN') {
            $exchangeRate = $get('exchange_rate') ?? 0;
            $set('amount', $totalPrice * $exchangeRate);
        }

        $paid = $get('paid') ?? 0;
        $amount = $get('amount') ?? 0;
        $set('remaining', max($amount - $paid, 0));
    }),

        Forms\Components\TextInput::make('exchange_rate')
            ->label('نرخ هر دالر به افغانی')
            ->numeric()
            ->lazy()
            ->visible(fn ($get) => $get('currency') === 'AFN')
            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                $totalPrice = $get('total_price') ?? 0;
                $set('amount', $totalPrice * ($state ?? 0));

                $paid = $get('paid') ?? 0;
                $amount = $get('amount') ?? 0;
                $set('remaining', max($amount - $paid, 0));
            }),

            Forms\Components\TextInput::make('amount')
                ->label('مبلغ خرید')
                ->numeric()
                ->dehydrated(true)
                ->lazy()
                ->reactive()
                ->afterStateHydrated(function ($state, callable $set, callable $get) {
                    if ($get('currency') === 'USD' && !$state) {
                        $set('amount', $get('total_price') ?? 0);
                    }
                })
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    $paid = $get('paid') ?? 0;
                    $set('remaining', max(($state ?? 0) - $paid, 0));
                }),

            Forms\Components\TextInput::make('paid')
                ->label('مبلغ رسید')
                ->numeric()
                ->dehydrated(true)
                ->reactive()
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    $amount = $get('amount') ?? 0;
                    $set('remaining', max($amount - ($state ?? 0), 0));
                }),

            Forms\Components\TextInput::make('remaining')
                ->label('باقی‌مانده')
                ->numeric()
                ->disabled()
                ->dehydrated(true)
                ->reactive(),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('barcode')->label('بارکد')->numeric()->sortable(),
            Tables\Columns\TextColumn::make('name')->label('نام جنس')->searchable(),
            Tables\Columns\TextColumn::make('unit')->label('نوع بسته بندی')->searchable(),
            Tables\Columns\TextColumn::make('price')->label('قیمت فی دانه')->numeric()->sortable(),
            Tables\Columns\TextColumn::make('all_exist_number')->label('تعداد خریده شده')->searchable(),
            Tables\Columns\TextColumn::make('big_whole_price')->label('قیمت عمده')->numeric()->sortable(),
             Tables\Columns\TextColumn::make('currency')
            ->label('نوع ارز')
            ->formatStateUsing(fn ($state) => match($state) {
                'USD' => 'دالر',
                'AFN' => 'افغانی',
                default => $state,
                 }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('مبلغ خرید')
                 ->formatStateUsing(fn ($state) => number_format($state, 2)),

                Tables\Columns\TextColumn::make('paid')
                    ->label('مبلغ رسید')
                    ->formatStateUsing(fn ($state) => number_format($state, 2)),

             Tables\Columns\TextColumn::make('remaining')
            ->label('مبلغ باقیمانده')
            ->formatStateUsing(fn ($state) => number_format($state, 2)),

            Tables\Columns\TextColumn::make('updated_at')->label('تاریخ بروزرسانی')->sortable()->toggleable(true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuys::route('/'),
            'create' => Pages\CreateBuy::route('/create'),
            'view' => Pages\ViewBuy::route('/{record}'),
            'edit' => Pages\EditBuy::route('/{record}/edit'),
        ];
    }

        private static function convertFarsiNumbersToEnglish(string $input): string
    {
        $farsiDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($farsiDigits, $englishDigits, $input);
    }

}