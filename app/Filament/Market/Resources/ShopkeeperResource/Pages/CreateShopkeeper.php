<?php

namespace App\Filament\Market\Resources\ShopkeeperResource\Pages;

use App\Filament\Market\Resources\ShopkeeperResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Market\Shop;
use App\Models\Market\Booth;

class CreateShopkeeper extends CreateRecord
{
    protected static string $resource = ShopkeeperResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getState();
    
       
        foreach ($data['shops'] ?? [] as $shopData) {
            if (!empty($shopData['shop_id'])) {
                Shop::where('id', $shopData['shop_id'])->update([
                    'shopkeeper_id' => $this->record->id,
                ]);
            }
        }
    
        // ثبت غرفه‌ها
        foreach ($data['booths'] ?? [] as $boothData) {
            if (!empty($boothData['booth_id'])) {
                Booth::where('id', $boothData['booth_id'])->update([
                    'shopkeeper_id' => $this->record->id,
                ]);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
