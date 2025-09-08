<?php

namespace App\Filament\Market\Resources\ShopkeeperResource\Pages;

use App\Models\Market\Booth;
use App\Models\Market\Shop;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Market\Resources\ShopkeeperResource;

class EditShopkeeper extends EditRecord
{
    protected static string $resource = ShopkeeperResource::class;

    protected function afterUpdate(): void
    {
        $data = $this->form->getState();
    
        // 1. همه دوکان‌ها و غرفه‌های فعلی این دوکاندار را خالی می‌کنیم
        Shop::where('shopkeeper_id', $this->record->id)->update(['shopkeeper_id' => null]);
        Booth::where('shopkeeper_id', $this->record->id)->update(['shopkeeper_id' => null]);
    
        // 2. دوباره دوکان‌هایی که در فرم انتخاب شدند را به این دوکاندار وصل می‌کنیم
        foreach ($data['shops'] ?? [] as $shopData) {
            if (!empty($shopData['shop_id'])) {
                Shop::where('id', $shopData['shop_id'])->update([
                    'shopkeeper_id' => $this->record->id,
                ]);
            }
        }
    
        // 3. دوباره غرفه‌هایی که در فرم انتخاب شدند را به این دوکاندار وصل می‌کنیم
        foreach ($data['booths'] ?? [] as $boothData) {
            if (!empty($boothData['booth_id'])) {
                Booth::where('id', $boothData['booth_id'])->update([
                    'shopkeeper_id' => $this->record->id,
                ]);
            }
        }
    }
    

    protected function beforeDelete(): void
{
    Shop::where('shopkeeper_id', $this->record->id)->update(['shopkeeper_id' => null]);
    Booth::where('shopkeeper_id', $this->record->id)->update(['shopkeeper_id' => null]);
}

}
