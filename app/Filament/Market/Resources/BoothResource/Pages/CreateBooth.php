<?php

namespace App\Filament\Market\Resources\BoothResource\Pages;

use App\Filament\Market\Resources\BoothResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBooth extends CreateRecord
{
    protected static string $resource = BoothResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
