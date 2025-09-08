<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\StaffResource\Pages;
use App\Models\Import\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;


class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

   
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'کارمندان';
    protected static ?string $navigationGroup = 'حسابداری';
    protected static ?string $pluralLabel = 'کارمندان';
    protected static ?string $label = 'کارمند';
    protected static ?int $navigationSort =8;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Hidden::make('user_id')
                ->default(fn () => Auth::id()),
            

                
                Forms\Components\TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('father_name')
                    ->label('نام پدر')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('grand-father')
                    ->label('نام پدرکلان')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('address')
                    ->label('آدرس')
                    ->maxLength(255)
                    ->default(null),

                Forms\Components\TextInput::make('phone')
                    ->label('شماره تماس')
                    ->tel()
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('salary')
                    ->label('معاش')
                    ->tel()
                    ->numeric()
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),

                Tables\Columns\TextColumn::make('father_name')
                    ->label('نام پدر')
                    ->searchable(),

                Tables\Columns\TextColumn::make('grand-father')
                    ->label('نام پدرکلان')
                    ->searchable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('آدرس')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('شماره تماس')
                    ->numeric()
                    ->sortable(),
               Tables\Columns\TextColumn::make('salary')
                    ->label('معاش')
                    ->sortable(),


                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاریخ بروزرسانی')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('نمایش'),
                Tables\Actions\EditAction::make()->label('ویرایش'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف انتخاب‌شده‌ها'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'view' => Pages\ViewStaff::route('/{record}'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
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
