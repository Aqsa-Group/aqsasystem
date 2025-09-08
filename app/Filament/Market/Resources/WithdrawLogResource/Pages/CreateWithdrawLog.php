<?php

namespace App\Filament\Market\Resources\WithdrawLogResource\Pages;

use App\Filament\Market\Resources\WithdrawLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWithdrawLog extends CreateRecord
{
    protected static string $resource = WithdrawLogResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
