<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\ExchangeResource\Pages;
use App\Filament\Market\Resources\ExchangeResource\RelationManagers;
use App\Models\Market\Accounting;
use App\Models\Market\Exchange;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ExchangeResource extends Resource
{
    protected static ?string $model = Exchange::class;

    protected static ?string $navigationIcon = "heroicon-o-exclamation-triangle";
    protected static ?string $navigationGroup = 'بخش مالی';
    protected static ?string $navigationLabel = 'تسویه نشده';
    protected static ?string $pluralModelLabel = 'تبادله';
    protected static ?string $modelLabel = 'تبدیل';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('from_type')
                    ->label('از')
                    ->options(function () {
                        return Accounting::query()
                            ->where('admin_id', Auth::id())
                            ->whereNotNull('expanses_type')
                            ->where('expanses_type', '!=', '')
                            ->distinct()
                            ->orderBy('expanses_type')
                            ->pluck('expanses_type', 'expanses_type')
                            ->toArray();
                    })
                    ->required(),

                Forms\Components\Select::make('to_type')
                    ->label('به')
                    ->options(function () {
                        return Accounting::query()
                            ->where('admin_id', Auth::id())
                            ->whereNotNull('expanses_type')
                            ->where('expanses_type', '!=', '')
                            ->distinct()
                            ->orderBy('expanses_type')
                            ->pluck('expanses_type', 'expanses_type')
                            ->toArray();
                    })
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('مقدار')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('currency')
                    ->label('واحد پول')
                    ->options([
                        'AFN' => 'افغانی',
                        'USD' => 'دالر',
                        'TOMAN' => 'تومان',
                        'EUR' => 'یورو',
                    ])
                    ->default('AFN')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('توضیحات')
            ])
            ->columns(2);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('from_type')
                    ->label('از')
                    ->searchable(),

                Tables\Columns\TextColumn::make('to_type')
                    ->label('به')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('مقدار')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency')
                    ->label('واحد پول')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListExchanges::route('/'),
            'create' => Pages\CreateExchange::route('/create'),
            'edit' => Pages\EditExchange::route('/{record}/edit'),
        ];
    }
}
