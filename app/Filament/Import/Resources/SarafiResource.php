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
                Forms\Components\Hidden::make('AFN')->label(' افغانی')
                    ->default(null),
                Forms\Components\Hidden::make('USD')->label('دالر')
                    ->default(null),
                Forms\Components\Hidden::make('CNY')->label('ین چین')
                    ->default(null),
                Forms\Components\Hidden::make('EUR')->label('یورو')
                    ->default(null),
                Forms\Components\Hidden::make('IRR')->label('تومان')
                    ->default(null),
                Forms\Components\Hidden::make('PKR')->label('کلدار')
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
                    ->default('0')
                    ->sortable(),
                Tables\Columns\TextColumn::make('USD')->label('دالر')
                    ->default('0')
                    ->sortable(),
                Tables\Columns\TextColumn::make('CNY')->label('ین چین')
                    ->default('0')
                    ->sortable(),
                Tables\Columns\TextColumn::make('EUR')->label('یورو')
                    ->default('0')
                    ->sortable(),
                Tables\Columns\TextColumn::make('IRR')->label('تومان')
                    ->default('0')
                    ->sortable(),
                Tables\Columns\TextColumn::make('PKR')->label('کلدار')
                    ->default('0')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->default('0')
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
