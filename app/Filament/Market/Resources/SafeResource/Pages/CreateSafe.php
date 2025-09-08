<?php

namespace App\Filament\Market\Resources\SafeResource\Pages;

use App\Filament\Market\Resources\SafeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSafe extends CreateRecord
{
    protected static string $resource = SafeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
