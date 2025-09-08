<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\BoothResource\Pages;
use App\Models\Market\Booth;
use App\Models\Market\Market;
use App\Models\Market\Shopkeeper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class BoothResource extends Resource
{
    protected static ?string $model = Booth::class;

    protected static ?string $navigationIcon = 'iconpark-booth';
    protected static ?string $navigationGroup = "اطلاعات مارکت";
    protected static ?string $navigationLabel = "غرفه‌ها";
    protected static ?string $modelLabel = "غرفه";
    protected static ?string $pluralModelLabel = "غرفه‌ها";
    
    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['admin', 'superadmin' , 'Customer Service']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('market_id')
                ->label('مارکت')
                ->options(function () {
                    $user = Auth::user();

                    return $user->role === 'superadmin'
                        ? Market::pluck('name', 'id')
                        : Market::where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id)
                            ->pluck('name', 'id');
                })
                ->required(),

            Forms\Components\TextInput::make('number')->label('نمبر غرفه')
                ->required()
                ->numeric(),

            Forms\Components\TextInput::make('floor')->label('کدام طبق')
                ->required()
                ->numeric(),

            Forms\Components\TextInput::make('size')->label('اندازه غرفه')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('metar_serial')->label('نمبر میتر')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('type')->label('نوع قرارداد')
                ->placeholder('نوع قرارداد را انتخاب کنید')
                ->options([
                    'کرایه' => 'کرایه',
                    'گروی' => 'گروی',
                    'فروش' => 'فروش',
                    'خرید' => 'خرید',
                ])
                ->required(),

            Forms\Components\TextInput::make('price')->label('قیمت')
                ->required()
                ->numeric()
                ->debounce(500)
                ->prefix('؋')
                ->afterStateUpdated(function ($state, callable $set) {
                    if (is_numeric($state)) {
                        $set('fa_price', BoothResource::numToFarsiWords($state));
                    } else {
                        $set('fa_price', null);
                    }
                }),
                Forms\Components\TextInput::make('fa_price')->label('قیمت کرایه حروف')->dehydrated()->readOnly()->maxLength(255),

                
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('market.name')
                ->label('مارکت')
                ->searchable()
                ->sortable()
                ->icon('heroicon-o-building-storefront')
                ->color('primary')
                ->badge(),

            Tables\Columns\TextColumn::make('shopkeeper.fullname')
                ->label('دوکاندار')
                ->default('—')
                ->searchable()
                ->sortable()
                ->icon('heroicon-o-user')
                ->color('warning')
                ->badge(),

            Tables\Columns\TextColumn::make('number')->label('نمبر غرفه')
                ->numeric()
                ->sortable(),

            Tables\Columns\TextColumn::make('floor')->label('طبق')
                ->numeric()
                ->sortable(),

            Tables\Columns\TextColumn::make('size')->label('سایز غرفه')
                ->searchable(),

            Tables\Columns\TextColumn::make('type')->label('نوع معامله')
                ->searchable(),

            Tables\Columns\TextColumn::make('price')->label('قیمت')
                ->suffix('؋')
                ->sortable(),
               



            Tables\Columns\TextColumn::make('metar_serial')->label('نمبر میتر')
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('تاریخ ثبت')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('آخرین تغییر')
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
            'index' => Pages\ListBooths::route('/'),
            'create' => Pages\CreateBooth::route('/create'),
            'view' => Pages\ViewBooth::route('/{record}'),
            'edit' => Pages\EditBooth::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()->whereHas('market', function ($query) use ($user) {
            $query->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
        });
    }

    public static function numToFarsiWords($num): string
    {
        if (!is_numeric($num)) return '';
        $formatter = new \NumberFormatter('fa', \NumberFormatter::SPELLOUT);
        $word = $formatter->format($num);
        $word = str_replace([
            'دویست',
            'سیصد',
            'چهارصد',
            'پانصد',
            'ششصد',
            'هفتصد',
            'هشتصد',
            'نهصد',
        ], [
            'دو صد',
            'سه صد',
            'چهار صد',
            'پنج صد',
            'شش صد',
            'هفت صد',
            'هشت صد',
            'نه صد',
        ], $word);
        return $word;
    }

    private static function convertPersianToEnglish(?string $string): string
    {
        if (!$string) return '';
        return str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $string
        );
    }


}
