<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\SarafiResource\Pages;
use App\Filament\Import\Resources\SarafiResource\RelationManagers;
use App\Models\Import\Sarafi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;


class SarafiResource extends Resource
{
    protected static ?string $model = Sarafi::class;

    protected static ?string $navigationIcon = 'emoji-bank';
    protected static ?string $navigationLabel= 'صرافی';
    protected static ?string $navigationGroup= 'بخش صرافی';
    protected static ?string $modelLabel= 'صرافی';
    protected static ?string $pluralModelLabel= 'صرافی';


    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin']);
    }

   public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('نام صرافی')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')->label('آدرس صرافی')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('phone')->label('شماره تماس صرافی')
                    ->tel()
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('AFN')->label(' افغانی')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('USD')->label('دالر')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('CNY')->label('ین چین')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('EUR')->label('یورو')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('IRR')->label('تومان')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('PKR')->label('کلدار')
                    ->numeric()
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('نام صرافی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')->label('آدرس صرافی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('شماره تماس صرافی')
                    ->sortable(),
                Tables\Columns\TextColumn::make('AFN')->label(' افغانی')
                    ->sortable(),
                Tables\Columns\TextColumn::make('USD')->label('دالر')
                    ->sortable(),
                Tables\Columns\TextColumn::make('CNY')->label('ین چین')
                    ->sortable(),
                Tables\Columns\TextColumn::make('EUR')->label('یورو')
                    ->sortable(),
                Tables\Columns\TextColumn::make('IRR')->label('تومان')
                    ->sortable(),
                Tables\Columns\TextColumn::make('PKR')->label('کلدار')
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
            'index' => Pages\ListSarafis::route('/'),
            'create' => Pages\CreateSarafi::route('/create'),
            'view' => Pages\ViewSarafi::route('/{record}'),
            'edit' => Pages\EditSarafi::route('/{record}/edit'),
        ];
    }
}
