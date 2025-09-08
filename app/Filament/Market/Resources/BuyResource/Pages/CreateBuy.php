<?php

namespace App\Filament\Market\Resources\BuyResource\Pages;

use App\Filament\Market\Resources\BuyResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateBuy extends CreateRecord
{
    protected static string $resource = BuyResource::class;

    protected function afterCreate(): void
    {
        $buy = $this->record;
        $user = Auth::user();

        $adminIdToSave = ($user->role === 'superadmin' || $user->role === 'admin')
            ? $user->id
            : $user->admin_id;
       

   
        }

    

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
