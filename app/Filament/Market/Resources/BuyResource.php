<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\BuyResource\Pages;
use App\Models\Market\Buy;
use App\Models\Market\Customer;
use App\Models\Market\Market;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BuyResource extends Resource
{
    protected static ?string $model = Buy::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'معاملات املاک';
    protected static ?string $navigationLabel = 'خریدها';
    protected static ?string $modelLabel = 'خرید';
    protected static ?string $pluralLabel = 'خرید';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'Financial Manager', 'admin']);
    }


    public static function form(Form $form): Form
    {

        $user = Auth::user();
        $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;

        return $form
            ->schema([


                Forms\Components\Select::make('market_id')
                ->label('مارکت')
                ->options(Market::where('admin_id', $adminId)->pluck('name', 'id'))
                ->required()
                ->reactive(),

                Forms\Components\Select::make('customer_id')
                ->label('فروشنده')
                ->placeholder('انتخاب نام فروشنده')
                ->options(fn($get) => $get('market_id') ? Customer::where('market_id', $get('market_id'))->pluck('fullname', 'id') : [])
                ->reactive()
                ->required(),
          

                Forms\Components\TextInput::make('property')
                    ->label('نوع خرید')
                    ->placeholder('خانه، زمین ، دوکان....')
                    ->required()
                    ->maxLength(500),

                Forms\Components\TextInput::make('price')
                    ->label('قیمت خرید')
                    ->placeholder('مقدار قیمت')
                    ->numeric()
                    ->required(),


                Forms\Components\Select::make('currency')
                    ->label('واحد پول')
                    ->options([
                        'AFN' => 'افغانی',
                        'USD' => 'دالر',
                        'EUR' => 'یورو',
                        'IRR' => 'تومان',
                    ])
                    ->required(),


                // Forms\Components\Select::make('reduce_from')
                //     ->label('برداشت از صندوق')
                //     ->options(
                //         fn() =>
                //         DB::connection('market')->table('accountings')
                //             ->whereNotNull('expanses_type')
                //             ->distinct()
                //             ->pluck('expanses_type', 'expanses_type')
                //             ->toArray()
                //     )
                //     ->required(),

                Forms\Components\TextInput::make('width')
                    ->label('عرض')
                    ->placeholder('به سانتی‌متر یا متر')
                    ->numeric(),

                Forms\Components\TextInput::make('hight')
                    ->label('طول')
                    ->placeholder('به سانتی‌متر یا متر')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('شناسه')
                    ->sortable(),

                Tables\Columns\TextColumn::make('property')
                    ->label('نوع خرید')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('قیمت خرید')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency')
                    ->label('واحد پول')
                    ->sortable(),

                Tables\Columns\TextColumn::make('width')
                    ->label('عرض')
                    ->numeric(),

                Tables\Columns\TextColumn::make('hight')
                    ->label('طول')
                    ->numeric(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('currency')
                    ->label('واحد پول')
                    ->options([
                        'AFN' => 'افغانی',
                        'USD' => 'دالر',
                    ]),

                Tables\Filters\Filter::make('recent')
                    ->label('خریدهای اخیر')
                    ->query(fn($query) => $query->where('created_at', '>=', now()->subDays(7))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            // روابط مرتبط اگر داشتی اینجا اضافه کن
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuys::route('/'),
            'create' => Pages\CreateBuy::route('/create'),
            'view' => Pages\ViewBuy::route('/{record}'),
            'edit' => Pages\EditBuy::route('/{record}/edit'),
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
}
