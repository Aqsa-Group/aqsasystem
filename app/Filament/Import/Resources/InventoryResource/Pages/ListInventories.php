<?php

namespace App\Filament\Import\Resources\InventoryResource\Pages;

use App\Filament\Import\Resources\InventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventories extends ListRecords
{
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
