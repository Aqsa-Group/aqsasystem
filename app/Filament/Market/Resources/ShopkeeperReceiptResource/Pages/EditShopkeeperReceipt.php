<?php

namespace App\Filament\Market\Resources\ShopkeeperReceiptResource\Pages;

use App\Filament\Market\Resources\ShopkeeperReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShopkeeperReceipt extends EditRecord
{
    protected static string $resource = ShopkeeperReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
