<?php

namespace App\Filament\Market\Resources\BuyResource\Pages;

use App\Filament\Market\Resources\BuyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuys extends ListRecords
{
    protected static string $resource = BuyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
