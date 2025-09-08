<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\UserResource\Pages;
use App\Models\Market\User;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'fas-users-gear';
    protected static ?string $modelLabel = 'کاربر';
    protected static ?string $pluralLabel = 'کاربران';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'admin']);
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'admin']);
    }

    public static function canEdit($record): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'admin']);
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('market_name')
                    ->label('نام مارکت')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('username')
                    ->label('نام کاربری')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->label('رمز عبور')
                    ->password()
                    ->required(fn(string $context) => $context === 'create')
                    ->dehydrated(fn($state) => filled($state))
                    ->maxLength(255),

                Forms\Components\Select::make('role')
                    ->label('نقش کاربر')
                    ->placeholder('وظیفه کارمند را انتخاب کنید')
                    ->options(function () {
                        $role = Auth::user()?->role;

                        if ($role === 'superadmin') {
                            return [
                                'superadmin' => 'مدیر کل سیستم',
                                'admin' => 'ادمین',

                            ];
                        }

                        return [
                            'Financial Manager' => 'مدیر مالی',
                            'Cashier' => 'حساب دار',
                            'Customer Service' => 'خدمه مشتریان'
                        ];
                    }),

                Forms\Components\TextInput::make('market_limit')
                    ->label('حداکثر ایجاد مارکت ')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->visible(fn() => Auth::user()?->role === 'superadmin'),




                Forms\Components\Hidden::make('admin_id')
                    ->default(fn() => Auth::user()?->role === 'admin' ? Auth::id() : null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('market_name')->label('نام مارکت')->searchable(),
                Tables\Columns\TextColumn::make('username')->label('نام کاربری')->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('نقش')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'superadmin' => 'primary',
                        'admin' => 'success',
                        'Financial Manager' => 'warning',
                        'Cashier' => 'info',
                        'Customer Service' => 'secondary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'superadmin' => 'مدیر کل سیستم',
                        'admin' => 'ادمین',
                        'Financial Manager' => 'مدیر مالی',
                        'Cashier' => 'حساب دار',
                        'Customer Service' => 'خدمه مشتریان',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')->label('تاریخ ایجاد')
                    ->dateTime()
                    ->formatStateUsing(fn($state) => Jalalian::fromDateTime($state)->format('Y/m/d h:i:s A'))

                    ->sortable(),
            ])
            ->filters([])
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

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        // سوپر ادمین همه چیزو ببینه
        if ($user->role === 'superadmin') {
            return parent::getEloquentQuery();
        }

        // ادمین فقط کارمندای خودش رو ببینه
        if ($user->role === 'admin') {
            return parent::getEloquentQuery()
                ->where('admin_id', $user->id)
                ->orWhere('id', $user->id);
        }

        // کارمند فقط خودش رو ببینه
        return parent::getEloquentQuery()
            ->where('id', $user->id);
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
