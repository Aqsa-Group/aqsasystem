<?php

namespace App\Filament\Market\Resources\AccountingResource\Pages;

use App\Filament\Market\Resources\AccountingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccounting extends EditRecord
{
    protected static string $resource = AccountingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
