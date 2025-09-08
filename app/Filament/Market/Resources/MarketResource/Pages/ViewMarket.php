<?php

namespace App\Filament\Market\Resources\MarketResource\Pages;

use App\Filament\Market\Resources\MarketResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMarket extends ViewRecord
{
    protected static string $resource = MarketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
