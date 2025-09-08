<?php

namespace App\Filament\Market\Resources\UserResource\Pages;

use App\Filament\Market\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function canView(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'superadmin']);
    }
}
