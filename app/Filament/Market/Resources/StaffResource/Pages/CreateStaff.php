<?php

namespace App\Filament\Market\Resources\StaffResource\Pages;

use App\Filament\Market\Resources\StaffResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
