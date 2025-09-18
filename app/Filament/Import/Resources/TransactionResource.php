<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\TransactionResource\Pages;
use App\Filament\Import\Resources\TransactionResource\RelationManagers;
use App\Models\Import\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Import\Sarafi;
use App\Models\Import\Customer;
use App\Models\Import\Staff;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;





class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'grommet-transaction';
    protected static ?string $navigationLabel= 'ترانزکشن ها';
    protected static ?string $navigationGroup = 'بخش صرافی';
    protected static ?string $modelLabel= 'ترانزکشن';
    protected static ?string $pluralModelLabel= 'ترانزکشن ها';


 public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin']);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('sarafi_id')
                    ->default(null),
                Forms\Components\Hidden::make('safe_id')
                    ->default(null),
             
                Forms\Components\Select::make('type')->label('نوع ترانزکشن')
                    ->options([
                        'رسید' => "رسید",
                        'برداشت' => "برداشت"
                    ])
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('sarafi_id')->label('انتخاب صرافی')
                    ->required()
                    ->options(Sarafi::pluck('name' , 'id')),

                Forms\Components\Select::make('person')->label('از حساب')
                    ->required()
                    ->options([
                        'دوکان' => 'دوکان',
                        'مشتری' => 'مشتری',
                        'کارمند' => 'کارمند',
                        'متفرقه' => 'متفرقه',
                    ])
                    ->visible(fn($get)=>$get('type')=='رسید')
                    ->reactive(),

                 Forms\Components\Select::make('person')->label('به حساب')
                    ->required()
                    ->options([
                        'چین' => 'چین',
                        'دوکان' => 'دوکان',
                        'مشتری' => 'مشتری',
                        'کارمند' => 'کارمند',
                        'متفرقه' => 'متفرقه',
                    ])
                    ->visible(fn($get)=>$get('type')=='برداشت')
                    ->reactive(),

                Forms\Components\Select::make('customer_id')->label('انتخاب مشتری')
                    ->options(Customer::pluck('name', 'id'))
                     ->visible(fn($get)=>$get('person')=='مشتری' )
                    ->default(null),

                Forms\Components\Select::make('staff_id')->label('انتخاب کارمند')
                    ->visible(fn($get)=>$get('person')=='کارمند' )
                    ->options(Staff::pluck('name', 'id'))
                    ->default(null),
                Forms\Components\TextInput::make('name_others')->label('نام')
                     ->visible(fn($get)=>$get('person')=='متفرقه')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('currency')->label('ارز')
                    ->options([
                        'AFN'=>'افغانی',
                        'USD'=>'دالر',
                        'CNY'=>'ین چین',
                        'EUR'=>'یورو',
                        'IRR'=>'تومان',
                        'PRK'=>'کلدار',
                    ])
                    ->required(),
            
                Forms\Components\TextInput::make('transaction_number')->label('نمبر حواله')
                    ->maxLength(255)
                    ->visible(fn($get)=> $get('type')=='رسید' && $get('person')=='مشتری' || $get('person')=='چین' )
                    ->default(null),
                Forms\Components\TextInput::make('amount')->label('مبلغ')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date')->label('تاریخ')->jalali(),
                Forms\Components\Textarea::make('description')->label('توضیحات'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('type')->label('نوع ترانزکشن')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sarafi.name')->label('نام صرافی')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('person')->label('حساب')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')->label('نام مشتری')
                    ->numeric()
                    ->searchable()
                    ->default('---')
                    ->sortable(),
                Tables\Columns\TextColumn::make('staff.name')->label('نام کارمند')
                    ->default('---')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')->label('ارز')
                 ->badge()
                    ->color(fn($state) => match ($state) {
                        'AFN' => 'primary',
                        'USD' => 'success',
                        'CNY' => 'warning',
                        'EUR' => 'info',
                        'IRR' => 'danger',
                        'PRK' => 'gray',
                        default => $state,

                        
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'AFٔN'=>'افغانی',
                        'USD'=>'دالر',
                        'CNY'=>'ین چین',
                        'EUR'=>'یورو',
                        'IRR'=>'تومان',
                        'PRK'=>'کلدار',
                        default => $state,
                    })
                    ->searchable(),
               
                Tables\Columns\TextColumn::make('amount')->label('مبلغ')
                        ->sortable(),

                Tables\Columns\TextColumn::make('transaction_number')->label('نمبر حواله')
                    ->default('---')
                    ->searchable(),
                 Tables\Columns\TextColumn::make('date')
                    ->searchable()
                    ->label('تاریخ')
                  ->formatStateUsing(fn($state) => $state ? Jalalian::fromDateTime($state)->format('Y/m/d') : '—'),
                Tables\Columns\TextColumn::make('description')->label('توصیحات')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
