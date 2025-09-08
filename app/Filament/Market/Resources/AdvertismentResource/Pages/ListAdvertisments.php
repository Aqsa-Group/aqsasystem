<?php

namespace App\Filament\Market\Resources\AdvertismentResource\Pages;

use App\Filament\Market\Resources\AdvertismentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdvertisments extends ListRecords
{
    protected static string $resource = AdvertismentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
