<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\MarketResource\Pages;
use App\Filament\Market\Resources\MarketResource\RelationManagers;
use App\Models\Market\Market;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
                


class MarketResource extends Resource
{
    protected static ?string $model = Market::class;

    protected static ?string $navigationIcon = 'lineawesome-hotel-solid';
    protected static ?string $navigationGroup ="اطلاعات مارکت";
    protected static ?string $navigationLabel = "ثبت مارکت";
    protected static ?string $modelLabel = "مارکت";
    protected static ?string $pluralModelLabel = "مارکت ها";

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['admin', 'superadmin' , 'Customer Service']);
    }


  public static function getNavigationBadge(): ?string
{
    $user = Auth::user();

    $query = static::getModel()::query();

    if ($user->role !== 'superadmin') {
        $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;
        $query->where('admin_id', $adminId);
    }

    return (string) $query->count();
}

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info'; 
    }

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              
                // name
                Forms\Components\TextInput::make('name')
                ->label('نام مارکت')
                ->required()
                ->maxLength(100),           
                Forms\Components\TextInput::make('location')->label('موقعیت مارکت')
                    ->required()
                    ->maxLength(400),
                Forms\Components\TextInput::make('total_shop')->label('تعداد دوکان ها')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('floor')->label('تعداد طبقات')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('booth')->label('غرفه')
                ->placeholder("آیا غرفه دارد یا خیر؟")
                    ->options([
                       'دارد' => "دارد",
                       'ندارد' => "ندارد"
                     ])
                     ->reactive()
                    ->required(),
                Forms\Components\TextInput::make('booth_number')->label('تعداد غرفه ها')
                    ->visible(fn ($get) => $get('booth')==="دارد")
                    ->numeric(),
                Forms\Components\Select::make('stock')->label('گدام')
                ->placeholder("آیا گدام دارد یا خیر؟")
                ->options([
                   'دارد' => "دارد",
                   'ندارد' => "ندارد"
                 ])
                    ->required(),
                Forms\Components\Select::make('parking')->label('پارکینگ')
                ->placeholder("آیا پارکینگ دارد یا خیر؟")
                 ->options([
                    'دارد' => "دارد",
                    'ندارد' => "ندارد"
                  ])
                  ->required(),
                    
                 Forms\Components\FileUpload::make('market_owner')
                  ->label('عکس صاحب مارکت')
                  ->image()
                  ->optimize('webp')
                  ->resize(50)
                  ->directory('uploads/market_owner/original')
                  ->visibility('public')
                  ->required(),
              
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('نام مارکت')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')->label('موقعیت مارکت')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_shop')->label('تعداد دوکان ها')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('floor')->label('تعداد طبقات')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booth')->label('غرفه')
                    ->searchable(),
                Tables\Columns\TextColumn::make('booth_number')->label('تعداد غرفه ها')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')->label('گدام')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parking')->label('پارکینگ')
                    ->searchable(),
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


    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
    
        if ($user->role === 'superadmin') {
            return parent::getEloquentQuery();
        }
    
        return parent::getEloquentQuery()
            ->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
    }

    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarkets::route('/'),
            'create' => Pages\CreateMarket::route('/create'),
            'view' => Pages\ViewMarket::route('/{record}'),
            'edit' => Pages\EditMarket::route('/{record}/edit'),
        ];
    }
}
