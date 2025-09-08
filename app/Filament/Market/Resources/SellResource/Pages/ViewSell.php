<?php

namespace App\Filament\Market\Resources\SellResource\Pages;

use App\Filament\Market\Resources\SellResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSell extends ViewRecord
{
    protected static string $resource = SellResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
