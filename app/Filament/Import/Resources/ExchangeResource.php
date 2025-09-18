<?php  

namespace App\Filament\Import\Resources;  

use App\Filament\Import\Resources\ExchangeResource\Pages;  
use App\Filament\Import\Resources\ExchangeResource\RelationManagers;  
use App\Models\Import\Exchange;  
use Filament\Forms;  
use Filament\Forms\Form;  
use Filament\Resources\Resource;  
use Filament\Tables;  
use Filament\Tables\Table;  
use App\Models\Import\Sarafi;
use App\Models\Import\Customer;
use App\Models\Import\Staff;

class ExchangeResource extends Resource 
{
    protected static ?string $model = Exchange::class;  
    protected static ?string $navigationIcon = 'hugeicons-exchange-03';  
    protected static ?string $navigationLabel= 'تبدیل ارز';
    protected static ?string $navigationGroup = 'بخش صرافی';
    protected static ?string $modelLabel= 'تبدیل ارز';
    protected static ?string $pluralModelLabel= 'تبدیل ارز';

    private static function calculateTotal($amount, $todayPrice, $from, $to) {
        if (!$amount || !$todayPrice || !$from || !$to) return 0;
        $from = str_replace(['ٔ','‌',' '], '', $from);
        $to = str_replace(['ٔ','‌',' '], '', $to);

        if ($from === $to) return $amount;

        $afnCurrencies = ['AFN', 'AFN']; 

        if (in_array($from, $afnCurrencies)) {
            return $amount / $todayPrice;
        } elseif (in_array($to, $afnCurrencies)) {
            return $amount * $todayPrice;
        } else {
            return $amount; 
        }
    }

    public static function form(Form $form): Form     
    {         
        return $form
            ->schema([  
                Forms\Components\Select::make('type')
                    ->label('نوع تبادله')
                    ->options([
                        'تبدیل ارز در صرافی' => "تبدیل ارز در صرافی",
                        'تبدیل ارز  دوکان' => "تبدیل ارز  دوکان",
                        'تبدیل ارز در حساب مشتری' => 'تبدیل ارز در حساب مشتری',
                        'تبدیل ارز در حساب کارمند' => 'تبدیل ارز در حساب کارمند',
                        'تبدیل ارز در حساب متفرقه' => 'تبدیل ارز در حساب متفرقه',
                    ])
                    ->reactive()
                    ->required(),

                Forms\Components\TextInput::make('person')
                    ->label('شخص')
                    ->maxLength(255)
                    ->visible(fn($get)=>$get('type')=='تبدیل ارز در حساب متفرقه')
                    ->default(null),

                Forms\Components\TextInput::make('name_others')
                    ->label('نام دیگران')
                    ->visible(fn($get)=>$get('type')=='تبدیل ارز در حساب متفرقه')
                    ->maxLength(255)
                    ->default(null),

                Forms\Components\Select::make('sarafi_id')
                    ->label('صرافی')
                    ->options(Sarafi::pluck('name','id'))
                    ->visible(fn($get)=>$get('type')=='تبدیل ارز در صرافی')
                    ->default(null),

                Forms\Components\Select::make('customer_id')
                    ->label('انتخاب مشتری')
                    ->options(Customer::pluck('name','id'))
                    ->visible(fn($get)=>$get('type')=='تبدیل ارز در حساب مشتری')
                    ->default(null),

                Forms\Components\Hidden::make('safe_id')
                    ->default(null),

                Forms\Components\Select::make('staff_id')
                    ->label('انتخاب کارمند')
                    ->options(Staff::pluck('name','id'))
                    ->visible(fn($get)=>$get('type')=='تبدیل ارز در حساب کارمند')
                    ->default(null),

                Forms\Components\Select::make('from')
                    ->label('از ارز')
                    ->options([
                        'AFN'=>'افغانی',
                        'USD'=>'دالر',
                        'CNY'=>'ین چین',
                        'EUR'=>'یورو',
                        'IRR'=>'تومان',
                        'PRK'=>'کلدار',
                    ])
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('to')
                    ->label('به ارز')
                    ->options([
                        'AFN'=>'افغانی',
                        'USD'=>'دالر',
                        'CNY'=>'ین چین',
                        'EUR'=>'یورو',
                        'IRR'=>'تومان',
                        'PRK'=>'کلدار',
                    ])
                    ->reactive()
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('مبلغ')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $set('total', self::calculateTotal($state, $get('today_price')??0, $get('from'), $get('to')));
                    }),

                Forms\Components\TextInput::make('today_price')
                    ->label('قیمت روز')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $set('total', self::calculateTotal($get('amount')??0, $state, $get('from'), $get('to')));
                    }),

                Forms\Components\TextInput::make('total')
                    ->label('مجموع')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true)
                    ->default(0),
            ]);     
    }  

    public static function table(Table $table): Table     
    {         
        return $table
            ->columns([  
                Tables\Columns\TextColumn::make('type')->label('نوع')->searchable(),
                Tables\Columns\TextColumn::make('sarafi.name')->label('صرافی')->numeric()->sortable()->default('---'),
                Tables\Columns\TextColumn::make('customer.name')->label('مشتری')->numeric()->sortable()->default('---'),
                Tables\Columns\TextColumn::make('staff.name')->label('کارمند')->numeric()->sortable()->default('---'),
                Tables\Columns\TextColumn::make('person')->label('شخص متفرقه')->searchable()->default('---'),
              Tables\Columns\TextColumn::make('from')
                ->label('از ارز')
                ->getStateUsing(fn ($record) => $record->from) 
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'AFN' => 'افغانی',
                        'USD' => 'دالر',
                        'CNY' => 'ین چین',
                        'EUR' => 'یورو',
                        'IRR' => 'تومان',
                        'PRK' => 'کلدار',
                        default => $state,
                    })
                    ->searchable(),

            Tables\Columns\TextColumn::make('to')
                ->label('به ارز')
                ->getStateUsing(fn ($record) => $record->to) 
                ->formatStateUsing(fn ($state) => match ($state) {
                    'AFN' => 'افغانی',
                    'USD' => 'دالر',
                    'CNY' => 'ین چین',
                    'EUR' => 'یورو',
                    'IRR' => 'تومان',
                    'PRK' => 'کلدار',
                    default => $state,
                })
                ->searchable(),

                Tables\Columns\TextColumn::make('amount')->label('مبلغ')->sortable(),
                Tables\Columns\TextColumn::make('today_price')->label('قیمت روز')->sortable(),
                Tables\Columns\TextColumn::make('total')->label('مجموع')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('تاریخ ایجاد')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('آخرین بروزرسانی')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])  
            ->filters([])  
            ->actions([  
                Tables\Actions\ViewAction::make()->label('مشاهده'),
                Tables\Actions\EditAction::make()->label('ویرایش'),
            ])  
            ->bulkActions([  
                Tables\Actions\BulkActionGroup::make([  
                    Tables\Actions\DeleteBulkAction::make()->label('حذف انتخاب شده‌ها'),
                ]),  
            ]);     
    }  

    public static function getRelations(): array     
    {         
        return [];     
    }  

    public static function getPages(): array     
    {         
        return [  
            'index' => Pages\ListExchanges::route('/'),  
            'create' => Pages\CreateExchange::route('/create'),  
            'view' => Pages\ViewExchange::route('/{record}'),  
            'edit' => Pages\EditExchange::route('/{record}/edit'),  
        ];     
    } 
}
