<?php

namespace App\Filament\Market\Resources\ShopkeeperResource\Pages;

use App\Filament\Market\Resources\ShopkeeperResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShopkeepers extends ListRecords
{
    protected static string $resource = ShopkeeperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
