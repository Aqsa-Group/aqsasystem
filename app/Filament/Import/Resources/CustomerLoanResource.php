<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\CustomerLoanResource\Pages;
use App\Models\Import\CustomerLoan;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class CustomerLoanResource extends Resource
{
    protected static ?string $model = CustomerLoan::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'قرض مشتریان';

    protected static ?string $pluralModelLabel = 'قرض مشتریان';

    protected static ?string $modelLabel = 'قرض مشتری';

    protected static ?string $navigationGroup = 'حسابداری';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                    ->label('مشتری')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('type')
                    ->label('نوع')
                    ->options([
                        'رسید' => 'رسید',
                        'برد' => 'برد',
                    ])
                    ->required(),

                TextInput::make('amount')
                    ->label('مبلغ')
                    ->numeric()
                    ->required(),

                Select::make('currency')
                    ->label('ارز')
                    ->options([
                        'AFN' => 'افغانی',
                        'USD' => 'دلار',
                        'EUR' => 'یورو',
                        'PKR' => 'کلدار',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('date')
                    ->label('تاریخ')
                    ->jalali()
                    ->required(),

                Textarea::make('description')
                    ->label('توضیحات')
                    ->columnSpanFull(),

                Hidden::make('user_id')
                    ->default(fn() => Auth::guard('import')->id()),

                Hidden::make('admin_id')
                    ->default(function () {
                        $user = Auth::guard('import')->user();
                        return $user->admin_id ?? $user->id;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->label('مشتری')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('نوع')
                    ->badge()
                    ->color(fn(string $state) => $state === 'رسید'
                        ? 'success'
                        : 'danger'),

                TextColumn::make('amount')
                    ->label('مبلغ'),



                TextColumn::make('currency')
                    ->label('ارز')
                    ->badge(),
                TextColumn::make('description')
                    ->label('توضیحات')
                    ->searchable(),

                TextColumn::make('date')
                    ->label('تاریخ')
                    ->formatStateUsing(function ($state) {
                        return $state
                            ? Jalalian::fromDateTime($state)->format('Y/m/d')
                            : null;
                    }),


                Tables\Columns\TextColumn::make('created_at')->label('تاریخ ثبت')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Jalalian::fromDateTime($state)->format('%A %d %m %Y')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCustomerLoans::route('/'),
            'create' => Pages\CreateCustomerLoan::route('/create'),
            'edit' => Pages\EditCustomerLoan::route('/{record}/edit'),
        ];
    }
}
