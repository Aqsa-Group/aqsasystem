<?php

namespace App\Filament\Market\Resources\DepositLogResource\Pages;

use App\Filament\Market\Resources\DepositLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepositLog extends EditRecord
{
    protected static string $resource = DepositLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
