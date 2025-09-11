<?php

namespace App\Filament\Market\Resources\ShopkeeperResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;


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
    Tables\Actions\Action::make('assignBooth')
        ->label('افزودن غرفه')
        ->form([
            Forms\Components\Grid::make([
                'default' => 1,
                'md' => 2,
            ])->schema([
                Forms\Components\Select::make('market_id')
                    ->label('مارکت')
                    ->options(\App\Models\Market\Market::pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->columnSpan(1),

                Forms\Components\Select::make('booth_id')
                    ->label('شماره غرفه')
                    ->options(function (callable $get) {
                        $marketId = $get('market_id');
                        if (!$marketId) return [];

                        return \App\Models\Market\Booth::where('market_id', $marketId)
                            ->whereNull('shopkeeper_id') 
                            ->pluck('number', 'id');
                    })
                    ->required()
                    ->columnSpan(1),
            ]),
        ])
        ->action(function (array $data) {
            $booth = \App\Models\Market\Booth::find($data['booth_id']);

            if ($booth) {
                $booth->update([
                    'shopkeeper_id' => $this->getOwnerRecord()->id,
                    'admin_id'       => Auth::id(),
                ]);
            }
        })
        ->color('success')
        ->icon('heroicon-o-plus'),
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
