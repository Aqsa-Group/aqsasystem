<?php
namespace App\Filament\Market\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Market\UserLog;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\Filter;

class UserLogReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static string $view = 'filament.pages.user-log-report';
    protected static ?string $navigationGroup = 'گزارشات';
    protected static ?string $navigationLabel = 'گزارش ورود و خروج کارمندان';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    protected function getTableQuery()
    {
        $user = Auth::user();

        return UserLog::with('user')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('admin_id', $user->id);
            })
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user.username')
                ->label('نام کارمند')
                ->searchable()
                ->badge()
                ->color('gray'),

            Tables\Columns\TextColumn::make('login_at')
                ->label('زمان ورود')
                ->badge()
                ->color('primary')
                ->formatStateUsing(fn ($state) => Jalalian::fromDateTime($state)->format('Y/m/d h:i:s A'))
                ->sortable(),

            Tables\Columns\TextColumn::make('logout_at')
                ->label('زمان خروج')
                ->badge()
                ->color('success')
                ->formatStateUsing(fn ($state) => Jalalian::fromDateTime($state)->format('Y/m/d h:i:s A'))
                ->sortable(),
        ];
    }


    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['admin', 'superadmin']);
    }

    public static function shouldRegisterNavigation(): bool
{
    return Auth::check() && Auth::user()->role === 'admin';
}

    protected function getTableFilters(): array
    {
        return [
            Filter::make('login_date_range')
                ->form([
                    DatePicker::make('login_from')->jalali()->label('از تاریخ'),
                    DatePicker::make('login_until')->jalali()->label('تا تاریخ'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['login_from'], fn ($q) => $q->whereDate('login_at', '>=', $data['login_from']))
                        ->when($data['login_until'], fn ($q) => $q->whereDate('login_at', '<=', $data['login_until']));
                }),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
