<?php

namespace App\Filament\Import\Resources\UserResource\Pages;

use App\Filament\Import\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function canView(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'superadmin']);
    }
}
