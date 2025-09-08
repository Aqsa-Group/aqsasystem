<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\BuyResource\Pages;
use App\Models\Import\Buy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;


class BuyResource extends Resource
{
    protected static ?string $model = Buy::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'بخش خرید و فروش';
    protected static ?string $navigationLabel = 'خرید جنس';

    protected static ?string $modelLabel = 'خرید';
    protected static ?string $pluralModelLabel = 'خرید';

    protected static ?int $navigationSort =3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Hidden::make('user_id')
                ->default(fn () => Auth::id()),
    
                Forms\Components\TextInput::make('seller')
                    ->label('فروشنده')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('product_name')
                    ->label('نام جنس')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->label('تعداد')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('unit')
                    ->label('واحد')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->label('قیمت کل')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $get) {
                        $set('reminded', max(0, ($get('price') ?? 0) - ($get('paid') ?? 0)));
                    })
                    ->required(),

                Forms\Components\TextInput::make('paid')
                    ->label('پرداخت شده')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $get) {
                        $set('reminded', max(0, ($get('price') ?? 0) - ($get('paid') ?? 0)));
                    })
                    ->required(),

                Forms\Components\TextInput::make('reminded')
                    ->label('باقیمانده')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('seller')->label('فروشنده')->sortable(),
                Tables\Columns\TextColumn::make('product_name')->label('نام جنس')->sortable(),
                Tables\Columns\TextColumn::make('quantity')->label('تعداد')->sortable(),
                Tables\Columns\TextColumn::make('unit')->label('واحد')->sortable(),
                Tables\Columns\TextColumn::make('price')->label('قیمت کل')->sortable(),
                Tables\Columns\TextColumn::make('paid')->label('پرداخت شده')->sortable(),
                Tables\Columns\TextColumn::make('reminded')->label('باقیمانده')->sortable(),
            ])
            ->actions([
                Action::make('receipt')
                ->label('رسید')
                ->icon('heroicon-o-currency-dollar')
                ->color('success')
                ->button()
                ->form([
                    Forms\Components\TextInput::make('amount')
                        ->label('مبلغ رسید')
                        ->numeric()
                        ->required()
                        ->suffix('افغانی')
                        ->prefixIcon('heroicon-o-banknotes'),
                ])
                ->action(function (Model $record, array $data) {

                    $record->paid += $data['amount'];

                    $record->reminded = max(0, $record->price - $record->paid);

                    $record->save();
                })
                ->modalHeading('ثبت رسید پرداختی')
                ->modalButton('ثبت رسید')
                ->modalWidth('sm'),
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
        return [];
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
    
        if (Auth::user()?->role === 'superadmin') {
            return $query; 
        }
    
       
        return $query->where('user_id', Auth::id());
    }
}
