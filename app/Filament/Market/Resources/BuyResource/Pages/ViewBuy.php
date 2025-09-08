<?php

namespace App\Filament\Market\Resources\BuyResource\Pages;

use App\Filament\Market\Resources\BuyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBuy extends ViewRecord
{
    protected static string $resource = BuyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
