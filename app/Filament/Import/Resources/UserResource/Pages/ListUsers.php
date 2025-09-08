<?php

namespace App\Filament\Import\Resources\UserResource\Pages;

use App\Filament\Import\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function canView(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'superadmin']);
    }
}
