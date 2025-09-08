<?php

namespace App\Filament\Market\Resources\UserResource\Pages;

use App\Filament\Market\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        return $data;
    }
    
    protected function canEdit(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'superadmin']);
    }
}
