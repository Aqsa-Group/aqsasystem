<?php

namespace App\Filament\Market\Resources\UserResource\Pages;

use App\Filament\Market\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => in_array(Auth::user()?->role, ['admin', 'superadmin'])),
        ];
    }

    protected function canView(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'superadmin']);
    }
}
