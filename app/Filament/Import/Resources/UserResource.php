<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\UserResource\Pages;
use App\Models\Import\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationGroup = 'گزارشات و تنظیمات';

    public static function getNavigationIcon(): string
    {
        return Auth::user()?->role === 'superadmin'
            ? 'heroicon-o-user'
            : 'heroicon-o-cog-6-tooth';
    }

    public static function getNavigationLabel(): string
    {
        return Auth::user()?->role === 'superadmin'
            ? 'کاربران'
            : 'تنظیمات';
    }

    public static function getModelLabel(): string
    {
        return Auth::user()?->role === 'superadmin'
            ? 'کاربر'
            : 'تنظیمات';
    }

    public static function getPluralModelLabel(): string
    {
        return Auth::user()?->role === 'superadmin'
            ? 'کاربران'
            : 'تنظیمات';
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()?->role === 'superadmin';
    }

    public static function canEdit($record): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'admin']);
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && Auth::user()?->role === 'superadmin';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()?->role === 'superadmin') {
            return $query;
        }

        return $query->where('id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        $isSuperAdmin = Auth::user()?->role === 'superadmin';

        return $form
            ->schema(array_filter([
                Forms\Components\TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('username')
                    ->label('نام کاربری')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->label('رمز')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),

                $isSuperAdmin ? Forms\Components\TextInput::make('role')
                    ->label('وظیفه')
                    ->required()
                    ->maxLength(255) : null,
            ]));
    }

    public static function table(Table $table): Table
    {
        $isSuperAdmin = Auth::user()?->role === 'superadmin';

        return $table
            ->columns(array_filter([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),

                Tables\Columns\TextColumn::make('username')
                    ->label('نام کاربری')
                    ->searchable(),

                $isSuperAdmin ? Tables\Columns\TextColumn::make('role')
                    ->label('وظیفه')
                    ->searchable() : null,

                Tables\Columns\TextColumn::make('created_at')
                    ->label('زمان ایجاد')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]))
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make('edit_account')
                    ->label('ویرایش حساب')
                    ->form(array_filter([
                        Forms\Components\TextInput::make('name')
                            ->label('نام')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('username')
                            ->label('نام کاربری')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->label('رمز جدید')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255),

                        $isSuperAdmin ? Forms\Components\TextInput::make('role')
                            ->label('وظیفه')
                            ->required()
                            ->maxLength(255) : null,
                    ]))
                    ->mutateFormDataUsing(function (array $data): array {
                        if (!empty($data['password'])) {
                            $data['password'] = bcrypt($data['password']);
                        } else {
                            unset($data['password']);
                        }
                        return $data;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()?->role === 'superadmin'),
                ]),
            ]);
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
