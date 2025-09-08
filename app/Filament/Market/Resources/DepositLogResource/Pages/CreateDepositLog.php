<?php

namespace App\Filament\Market\Resources\DepositLogResource\Pages;

use App\Filament\Market\Resources\DepositLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDepositLog extends CreateRecord
{
    protected static string $resource = DepositLogResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
