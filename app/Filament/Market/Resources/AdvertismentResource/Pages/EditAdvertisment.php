<?php

namespace App\Filament\Market\Resources\AdvertismentResource\Pages;

use App\Filament\Market\Resources\AdvertismentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvertisment extends EditRecord
{
    protected static string $resource = AdvertismentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
