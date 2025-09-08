<?php

namespace App\Filament\Import\Resources\UserResource\Pages;

use App\Filament\Import\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function canCreate(): bool
    {
        return in_array(Auth::user()?->role, ['superadmin']);
    }
}
