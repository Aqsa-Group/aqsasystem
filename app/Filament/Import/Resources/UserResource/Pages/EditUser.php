<?php

namespace App\Filament\Import\Resources\UserResource\Pages;

use App\Filament\Import\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function canEdit(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'superadmin']);
    }
}
