<?php

namespace App\Filament\Market\Resources\ShopkeeperReceiptResource\Pages;

use App\Filament\Market\Resources\ShopkeeperReceiptResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShopkeeperReceipt extends CreateRecord
{
    protected static string $resource = ShopkeeperReceiptResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}