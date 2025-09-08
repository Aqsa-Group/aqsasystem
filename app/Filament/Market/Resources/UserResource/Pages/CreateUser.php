<?php

namespace App\Filament\Market\Resources\UserResource\Pages;

use App\Filament\Market\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = bcrypt($data['password']);
        if (Auth::user()?->role === 'admin') {
            $data['admin_id'] = Auth::id();
        } else {
            $data['admin_id'] = null;
        }
        return $data;
    }

    protected function canCreate(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'superadmin']);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
