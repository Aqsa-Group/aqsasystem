<?php

namespace App\Filament\Market\Resources\AccountingResource\Pages;

use App\Filament\Market\Resources\AccountingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccounting extends CreateRecord
{
    protected static string $resource = AccountingResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
