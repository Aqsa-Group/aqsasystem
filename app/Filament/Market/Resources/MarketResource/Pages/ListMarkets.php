<?php

namespace App\Filament\Market\Resources\MarketResource\Pages;

use App\Filament\Market\Resources\MarketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarkets extends ListRecords
{
    protected static string $resource = MarketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
