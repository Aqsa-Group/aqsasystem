<?php

namespace App\Filament\Market\Resources\ShopkeeperResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BoothRelationManager extends RelationManager
{


    protected static string $relationship = 'booth';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('number')
                ->label('شماره غرفه')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('floor')
                ->label('منزل')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('size')
                ->label('اندازه')
                ->required(),
            
            Forms\Components\TextInput::make('type')
                ->label('نوعیت')
                ->required(),
            Forms\Components\TextInput::make('price')
                ->label('قیمت')
                ->required()
                ->numeric(),
            Forms\Components\Select::make('market_id')
                ->label('مارکت')
                ->relationship('market', 'name')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->columns([
                Tables\Columns\TextColumn::make('number')->label('شماره'),
                Tables\Columns\TextColumn::make('floor')->label('منزل'),
                Tables\Columns\TextColumn::make('size')->label('اندازه'),
                Tables\Columns\TextColumn::make('metar_serial')->label('شماره میتر'),
                Tables\Columns\TextColumn::make('type')->label('نوعیت'),
                Tables\Columns\TextColumn::make('price')->label('قیمت')->money('AFN'),
                Tables\Columns\TextColumn::make('market.name')->label('مارکت'),
                Tables\Columns\TextColumn::make('created_at')->label('ایجاد شده')->dateTime()->since(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
