<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\AdvertismentResource\Pages;
use App\Models\Market\Advertisment;
use App\Models\Market\Booth;
use App\Models\Market\Market;
use App\Models\Market\Shop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class AdvertismentResource extends Resource
{
    protected static ?string $model = Advertisment::class;

    protected static ?string $navigationIcon = 'gmdi-real-estate-agent-o';
    protected static ?string $navigationLabel = "ثبت ملک";
    protected static ?string $modelLabel = "ملک";
    protected static ?string $navigationGroup = 'معاملات املاک';
    protected static ?string $pluralModelLabel = "املاک";

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin' , 'admin' , 'Financial Manager']);
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('property')
                ->label("نوع ملک")
                ->options([
                    'دوکان' => "دوکان",
                    'غرفه' => "غرفه",
                    'خانه' => "خانه",
                    'زمین' => "زمین",
                ])
                ->required()
                ->reactive(),
    
            Forms\Components\Select::make('for')
                ->label('برای')
                ->options([
                    'کرایه' => 'کرایه',
                    'فروش' => 'فروش',
                    'گروی' => 'گروی',
                ])
                ->default(null)
                ->reactive(),
    
            Forms\Components\Select::make('market_id')
                ->label('نام مارکت')
                ->placeholder('انتخاب نام مارکت')
                ->options(function () {
                    $user = Auth::user();
                    return $user->role === 'superadmin'
                        ? Market::pluck('name', 'id')
                        : Market::where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id)
                            ->pluck('name', 'id');
                })
                ->reactive()
                ->visible(fn($get) => in_array($get('property'), ['دوکان', 'غرفه'])),
    
            Forms\Components\Select::make('shop_id')
                ->label('نمبر دوکان')
                ->options(function ($get) {
                    if ($get('market_id') && $get('property') === 'دوکان' && $get('for') === 'فروش') {
                     
                        return Shop::where('market_id', $get('market_id'))
                            ->where('is_sell', 0)
                            ->pluck('number', 'id');
                    }
                   
                    return $get('market_id')
                        ? Shop::where('market_id', $get('market_id'))->pluck('number', 'id')
                        : [];
                })
                ->searchable()
                ->reactive()
                ->visible(fn($get) => $get('property') === 'دوکان')
                ->required(fn($get) => $get('property') === 'دوکان'),
    
            Forms\Components\Select::make('booth_id')
                ->label('نمبر غرفه')
                ->options(
                    fn($get) =>
                    $get('market_id')
                        ? Booth::where('market_id', $get('market_id'))->pluck('number', 'id')
                        : []
                )
                ->searchable()
                ->reactive()
                ->visible(fn($get) => $get('property') === 'غرفه')
                ->required(fn($get) => $get('property') === 'غرفه'),
    
            Forms\Components\TextInput::make('address')
                ->label('آدرس')
                ->maxLength(255)
                ->visible(fn($get) => in_array($get('property'), ['خانه', 'زمین'])),
    
            Forms\Components\TextInput::make('width')
                ->label('عرض')
                ->maxLength(255)
                ->numeric()
                ->required(),
    
            Forms\Components\TextInput::make('hight')
                ->label('طول')
                ->maxLength(255)
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
                ->reactive()
                ->required(),
    
            Forms\Components\TextInput::make('price')
                ->label('قیمت')
                ->numeric()
                ->prefix(fn($get) => match ($get('currency')) {
                    'AFN' => '؋',
                    'USD' => '$',
                    'EUR' => '€',
                    'TOMAN' => '﷼',
                    default => '',
                })
                ->required(),
    
            Forms\Components\FileUpload::make('image')
                ->label('عکس ملک')
                ->multiple()
                ->optimize('webp')
                ->resize(50)
                ->reorderable()
                ->directory('uploads/property_image')
                ->visibility('public')
                ->required(),
        ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('market.name')
                ->label('مارکت'),

            Tables\Columns\TextColumn::make('shop.number')
                ->label('دوکان'),

            Tables\Columns\TextColumn::make('booth.number')
                ->label('غرفه'),

            Tables\Columns\TextColumn::make('property')
                ->label('نوع ملک')
                ->searchable(),

            Tables\Columns\TextColumn::make('address')
                ->label('آدرس')
                ->searchable(),

            Tables\Columns\TextColumn::make('width')
                ->label('عرض'),

            Tables\Columns\TextColumn::make('hight')
                ->label('طول'),

            Tables\Columns\TextColumn::make('price')
                ->label('قیمت')
                ->formatStateUsing(fn($record) => match ($record->currency) {
                    'AFN' => '؋ ' . number_format($record->price),
                    'USD' => '$ ' . number_format($record->price),
                    'EUR' => '€ ' . number_format($record->price),
                    'TOMAN' => '﷼ ' . number_format($record->price),
                    default => number_format($record->price),
                })
                ->sortable(),
         
            Tables\Columns\TextColumn::make('created_at')
                ->label('ایجاد شده')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('آپدیت شده')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAdvertisments::route('/'),
            'create' => Pages\CreateAdvertisment::route('/create'),
            'view' => Pages\ViewAdvertisment::route('/{record}'),
            'edit' => Pages\EditAdvertisment::route('/{record}/edit'),
        ];
    }
}
