<?php

namespace App\Filament\Market\Resources\AdvertismentResource\Pages;

use App\Filament\Market\Resources\AdvertismentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdvertisment extends ViewRecord
{
    protected static string $resource = AdvertismentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
