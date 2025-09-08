<?php

namespace App\Filament\Market\Resources\AdvertismentResource\Pages;

use App\Filament\Market\Resources\AdvertismentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdvertisment extends CreateRecord
{
    protected static string $resource = AdvertismentResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
