<?php

namespace App\Filament\Import\Resources\BuyResource\Pages;

use App\Filament\Import\Resources\BuyResource;
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
