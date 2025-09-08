<?php

namespace App\Filament\Market\Resources\ShopResource\Pages;

use App\Filament\Market\Resources\ShopResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateShop extends CreateRecord
{
    protected static string $resource = ShopResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $record = $this->record; 
        $user = Auth::user();

       
        if ($record->sarqofli === 'بلی' && $record->sarqofli_time === 'now') {
            DB::connection('market')->table('accountings')->insert([
                'expanses_type' => 'پول سرقفلی',
                'currency' => $record->currency,
                'paid' => 1 * $record->sarqofli_price,
                'type' => 'sarqoflimoney',
                'admin_id' => $user->role === 'superadmin' || $user->role === 'admin' ? $user->id : $user->admin_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($record->rent === 'بلی' && $record->rent_time === 'now') {
            DB::connection('market')->table('accountings')->insert([
                'expanses_type' => 'پول گروی',
                'currency' => $record->currency,
                'paid' => 1 * $record->rent_price,
                'type' => 'mortagagemoney',
                'admin_id' => $user->role === 'superadmin' || $user->role === 'admin' ? $user->id : $user->admin_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
