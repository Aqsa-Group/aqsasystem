<?php

namespace App\Filament\Market\Resources\SellResource\Pages;

use App\Filament\Market\Resources\SellResource;
use App\Models\Market\Shop;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateSell extends CreateRecord
{
    protected static string $resource = SellResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

    
        if ($user->role === 'superadmin' || $user->role === 'admin') {
            $data['admin_id'] = $user->id;
        } else {
            $data['admin_id'] = $user->admin_id;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $sell = $this->record;

        if ($sell->shop_id && $sell->customer_id) {
            Shop::where('id', $sell->shop_id)->update([
                'is_sell'     => true,
                'customer_id' => $sell->customer_id,
            ]);
        }

        if ($sell->price > 0) {
            DB::connection('market')->table('accountings')->insert([
                'expanses_type' => 'عواید فروش ملک',
                'currency'      => $sell->currency,
                'paid'          => $sell->price,
                'type'          => 'Sell',
                'market_id'     => $sell->market_id,
                'admin_id'      => $sell->admin_id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
