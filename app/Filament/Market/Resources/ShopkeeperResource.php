<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\ShopkeeperResource\Pages;
use App\Filament\Market\Resources\ShopkeeperResource\RelationManagers;
use App\Models\Market\Shopkeeper;
use App\Models\Market\Shop;
use App\Models\Market\Booth;
use App\Models\Market\Market;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Morilog\Jalali\Jalalian;
use DateTime;

class ShopkeeperResource extends Resource
{
    protected static ?string $model = Shopkeeper::class;

    protected static ?string $navigationIcon = 'fluentui-people-audience-24-o';
    protected static ?string $navigationGroup = "اطلاعات مارکت";
    protected static ?string $navigationLabel = "دوکانداران";
    protected static ?string $modelLabel = "دوکاندار";
    protected static ?string $pluralModelLabel = "دوکانداران";

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['admin', 'superadmin', 'Customer Service']);
    }

     public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success'; 
    }

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;

        return $form->schema([
            Forms\Components\TextInput::make('fullname')->label('نام و نام فامیلی')->required(),
            Forms\Components\TextInput::make('father_name')->label('نام پدر')->required(),
            Forms\Components\TextInput::make('grand_father')->label('نام پدرکلان')->required(),
            Forms\Components\TextInput::make('username')->label('نام کاربری')->required(),
            Forms\Components\TextInput::make('password')->label('رمز حساب')->required(),
            Forms\Components\TextInput::make('address')->label('آدرس')->maxLength(600)->required(),
            Forms\Components\TextInput::make('phone')->label('شماره تلفن')->required()->numeric(),
            Forms\Components\TextInput::make('shop_activity')->label('نوع شغل دوکان')->required(),

            Forms\Components\TextInput::make('contract_number')
            ->label('نمبر قرارداد')
            ->required()
            ->numeric()
            ->default(function () {
                $user = Auth::user();
                $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;
        
                $maxContractNumber = Shopkeeper::where('admin_id', $adminId)->max('contract_number');
        
                return $maxContractNumber ? $maxContractNumber + 1 : 1;
            })
            ->readOnly(),
        
        
            Forms\Components\Select::make('market_id')->label('مارکت مربوطه')
                ->options(function () use ($adminId, $user) {
                    if ($user->role === 'superadmin') {
                        return Market::pluck('name', 'id');
                    }
                    return Market::where('admin_id', $adminId)->pluck('name', 'id');
                })
                ->required(),

            Forms\Components\DatePicker::make('contract_start')
                ->label('تاریخ شروع قرارداد')
                ->jalali()
                ->required()
                ->reactive()
                ->afterStateUpdated(function (callable $get, callable $set, $state) {
                    $set('contract_duration', self::calculateDuration(
                        self::convertPersianToEnglish($state),
                        self::convertPersianToEnglish($get('contract_end'))
                    ));
                })
                ->dehydrateStateUsing(fn($state) => self::convertPersianToEnglish($state)),

            Forms\Components\DatePicker::make('contract_end')
                ->label('تاریخ ختم قرارداد')
                ->jalali()
                ->required()
                ->reactive()
                ->afterStateUpdated(function (callable $get, callable $set, $state) {
                    $set('contract_duration', self::calculateDuration(
                        self::convertPersianToEnglish($get('contract_start')),
                        self::convertPersianToEnglish($state)
                    ));
                })
                ->dehydrateStateUsing(fn($state) => self::convertPersianToEnglish($state)),

            Forms\Components\TextInput::make('contract_duration')
                ->label('مدت قرارداد')
                ->disabled()
                ->dehydrated(true)
                ->reactive()
                ->afterStateHydrated(function (callable $set, $state, callable $get) {
                    $set('contract_duration', self::calculateDuration(
                        self::convertPersianToEnglish($get('contract_start')),
                        self::convertPersianToEnglish($get('contract_end'))
                    ));
                }),

            Forms\Components\TextInput::make('national_id')->label('نمبر تذکره')->required(),

            Forms\Components\FileUpload::make('warranty_document')
                ->label('ضمانت خط')
                ->image()
                ->optimize('webp')
                ->resize(50)
                ->directory('uploads/shopkeeper')
                ->visibility('public')
                ->required(),

            Forms\Components\FileUpload::make('shopkeeper_image')
                ->label('عکس دوکاندار')
                ->image()
                ->optimize('webp')
                ->resize(50)
                ->directory('uploads/shopkeeper/profile_image')
                ->visibility('public')
                ->required(),

            Forms\Components\FileUpload::make('id_image')
                ->label('عکس تذکره')
                ->image()
                ->optimize('webp')
                ->resize(50)
                ->directory('uploads/shopkeeper')
                ->visibility('public')
                ->required(),

            Forms\Components\Repeater::make('shops')
                ->label('دوکان‌ها')
                ->schema([
                    Forms\Components\Select::make('market_id')
                        ->label('مارکت')
                        ->options(function () use ($adminId, $user) {
                            if ($user->role === 'superadmin') {
                                return Market::pluck('name', 'id');
                            }
                            return Market::where('admin_id', $adminId)->pluck('name', 'id');
                        })
                        ->required()
                        ->reactive(),

                    Forms\Components\Select::make('shop_id')
                        ->label('شماره دوکان')
                        ->options(function (callable $get) use ($adminId) {
                            $marketId = $get('market_id');
                            if (!$marketId) return [];
                            return Shop::where('market_id', $marketId)
                                ->where('admin_id', $adminId)
                                ->pluck('number', 'id');
                        })
                        ->reactive()
                        ->required()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $shop = Shop::find($state);
                            if ($shop) {
                                $set('floor', $shop->floor);
                                $set('size', $shop->size);
                                $set('type', $shop->type);
                                $set('price', $shop->price);
                                $set('metar_serial', $shop->metar_serial);
                                $set('north', $shop->north);
                                $set('south', $shop->south);
                                $set('east', $shop->east);
                                $set('west', $shop->west);
                                $set('side', $shop->side);
                            }
                        }),

                    Forms\Components\TextInput::make('floor')->label('منزل')->disabled(),
                    Forms\Components\TextInput::make('size')->label('اندازه')->disabled(),
                    Forms\Components\TextInput::make('metar_serial')->label('شماره میتر')->disabled(),

                    Forms\Components\TextInput::make('price')
                        ->label('قیمت')
                        ->prefix('؋')
                        ->disabled()
                        ->visible(
                            fn(callable $get) =>
                            in_array($get('type'), ['کرایه', 'گروی'])
                        ),

                    Forms\Components\TextInput::make('north')->label('شمال')->disabled()
                        ->visible(fn(callable $get) => $get('type') === 'سرقفلی'),

                    Forms\Components\TextInput::make('south')->label('جنوب')->disabled()
                        ->visible(fn(callable $get) => $get('type') === 'سرقفلی'),

                    Forms\Components\TextInput::make('east')->label('شرق')->disabled()
                        ->visible(fn(callable $get) => $get('type') === 'سرقفلی'),

                    Forms\Components\TextInput::make('west')->label('غرب')->disabled()
                        ->visible(fn(callable $get) => $get('type') === 'سرقفلی'),

                    Forms\Components\TextInput::make('side')->label('سمت موقعیت')->disabled()
                        ->visible(fn(callable $get) => $get('type') === 'گروی'),

                    Forms\Components\TextInput::make('type')->label('نوع قرارداد')->disabled(),
                ])
                ->columns(2)
                ->createItemButtonLabel('افزودن دوکان'),

            Forms\Components\Repeater::make('booths')
                ->label('غرفه‌ها')
                ->schema([
                    Forms\Components\Select::make('market_id')
                        ->label('مارکت')
                        ->options(function () use ($adminId, $user) {
                            if ($user->role === 'superadmin') {
                                return Market::pluck('name', 'id');
                            }
                            return Market::where('admin_id', $adminId)->pluck('name', 'id');
                        })
                        ->required()
                        ->reactive(),

                    Forms\Components\Select::make('booth_id')
                        ->label('شماره غرفه')
                        ->reactive()
                        ->required()
                        ->options(function (callable $get) use ($adminId) {
                            $marketId = $get('market_id');
                            if (!$marketId) {
                                return [];
                            }
                            return Booth::where('market_id', $marketId)
                                ->where('admin_id', $adminId)
                                ->pluck('number', 'id');
                        })
                        ->afterStateUpdated(function ($state, callable $set) {
                            $booth = Booth::find($state);
                            if ($booth) {
                                $set('floor', $booth->floor);
                                $set('size', $booth->size);
                                $set('type', $booth->type);
                                $set('price', $booth->price);
                                $set('metar_serial', $booth->metar_serial);
                            }
                        }),

                    Forms\Components\TextInput::make('floor')->label('منزل')->disabled(),
                    Forms\Components\TextInput::make('size')->label('اندازه')->disabled(),
                    Forms\Components\TextInput::make('type')->label('نوعیت')->disabled(),
                    Forms\Components\TextInput::make('price')->label('قیمت')->disabled(),
                    Forms\Components\TextInput::make('metar_serial')->label('شماره میتر')->disabled(),
                ])
                ->columns(2)
                ->createItemButtonLabel('افزودن غرفه'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('fullname')->label('نام')->searchable(),
            Tables\Columns\TextColumn::make('phone')->label('شماره تلفن')->searchable(),
            Tables\Columns\TextColumn::make('shop_activity')->label('شغل')->searchable(),
            Tables\Columns\TextColumn::make('contract_number')->label('نمبر قرارداد'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('ایجاد شده')
                ->formatStateUsing(fn($state) => Jalalian::fromDateTime($state)->format('Y/m/d')),
        ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('printContract')
                    ->label('چاپ قرارداد')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('contract.print', $record->id))
                    ->openUrlInNewTab(),
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
            RelationManagers\ShopsRelationManager::class,
            RelationManagers\BoothRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShopkeepers::route('/'),
            'create' => Pages\CreateShopkeeper::route('/create'),
            'view' => Pages\ViewShopkeeper::route('/{record}'),
            'edit' => Pages\EditShopkeeper::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        if (!$user) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
    
        $query = parent::getEloquentQuery();
    
        if ($user->role !== 'superadmin') {
            $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;
            $query->where('admin_id', $adminId);
        }
    
        // جستجو در فیلدهای اصلی و رابطه shops
        if ($search = request()->input('tableFilters')['search'] ?? null) {
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('shop_activity', 'like', "%{$search}%")
                  ->orWhere('contract_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('shops', function ($q2) use ($search) {
                      $q2->where('number', 'like', "%{$search}%")
                         ->orWhere('type', 'like', "%{$search}%");
                  });
            });
        }
    
        return $query;
    }
    
    // متد کمکی تبدیل اعداد فارسی به انگلیسی
    private static function convertPersianToEnglish(?string $string): string
    {
        if (!$string) return '';
        return str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $string
        );
    }

    // محاسبه مدت قرارداد به صورت رشته با سال و ماه و روز
    private static function calculateDuration(string $start, string $end): string
    {
        try {
            $startDate = new DateTime($start);
            $endDate = new DateTime($end);
        } catch (\Exception $e) {
            return '';
        }

        if ($endDate < $startDate) {
            return 'تاریخ پایان قبل از شروع است';
        }

        $interval = $startDate->diff($endDate);
        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;

        $parts = [];
        if ($years > 0) $parts[] = $years . ' سال';
        if ($months > 0) $parts[] = $months . ' ماه';
        if ($days > 0) $parts[] = $days . ' روز';

        return implode(' و ', $parts);
    }
}
