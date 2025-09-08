<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\LoanResource\Pages;
use App\Models\Import\Customer;
use App\Models\Import\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;


class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;
    protected static ?string $navigationIcon = 'fas-money-bill-transfer';
    protected static ?string $navigationGroup = 'حسابداری';
    protected static ?string $navigationLabel = 'قرضه ها';
    protected static ?string $modelLabel = 'قرض';
    protected static ?string $pluralModelLabel = 'قرضه ها و رسید';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Hidden::make('user_id')
            ->default(fn () => Auth::id()),

            Forms\Components\Select::make('customer_id')
            ->label('مشتری')
            ->options(function () {
                $userId = Auth::id();
                return Customer::where('user_id', $userId)->pluck('name', 'id');
            })
            ->searchable()
            ->required(),

            Forms\Components\Select::make('type')
                ->label('نوع')
                ->options(['بردگی' => 'بردگی', 'رسید' => 'رسید'])
                ->reactive()
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->label('مبلغ قرضه')
                ->numeric()
                ->visible(fn ($get) => $get('type') === 'بردگی'),

            Forms\Components\TextInput::make('loan_recipt')
                ->label('مبلغ رسید')
                ->numeric()
                ->visible(fn ($get) => $get('type') === 'رسید'),

            Forms\Components\DatePicker::make('date')
                ->label('تاریخ')
                ->default(now()),
        ]);
    }
   
    


public static function table(Table $table): Table
{
    return $table
    ->modifyQueryUsing(function ($query) {
        return $query
            ->selectRaw('
                MIN(id) as id,
                customer_id,
                SUM(CASE WHEN type = "بردگی" THEN amount ELSE 0 END) AS total_loan,
                SUM(CASE WHEN type = "رسید" THEN loan_recipt ELSE 0 END) AS total_receipt,
                (SUM(CASE WHEN type = "بردگی" THEN amount ELSE 0 END) -
                 SUM(CASE WHEN type = "رسید" THEN loan_recipt ELSE 0 END)) AS remaining
            ')
            ->groupBy('customer_id');
    })
    
        ->columns([
            Tables\Columns\TextColumn::make('customer.name')->label('نام مشتری'),
            Tables\Columns\TextColumn::make('total_loan')
                ->label('مجموع بردگی')
                ->formatStateUsing(fn ($state) => number_format($state)),
            Tables\Columns\TextColumn::make('total_receipt')
                ->label('مجموع رسید')
                ->formatStateUsing(fn ($state) => number_format($state)),
            Tables\Columns\TextColumn::make('remaining')
                ->label('باقی‌مانده')
                ->formatStateUsing(fn ($state) => number_format($state)),
        ])
        ->actions([])
        ->bulkActions([]);
}


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'view' => Pages\ViewLoan::route('/{record}'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
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
