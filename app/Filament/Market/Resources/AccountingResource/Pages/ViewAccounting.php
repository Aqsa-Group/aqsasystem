<?php

namespace App\Filament\Market\Resources\AccountingResource\Pages;

use App\Filament\Market\Resources\AccountingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAccounting extends ViewRecord
{
    protected static string $resource = AccountingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
