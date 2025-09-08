<?php

namespace App\Filament\Market\Resources\BoothResource\Pages;

use App\Filament\Market\Resources\BoothResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateBooth extends CreateRecord
{
    protected static string $resource = BoothResource::class;

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
                'expanses_type' => 'پول سرقفلی غرفه',
                'currency' => $record->currency, 
                'paid' => 1 * $record->sarqofli_price,
                'type' => 'booth_sarqoflimoney',
                'admin_id' => $user->role === 'superadmin' || $user->role === 'admin' ? $user->id : $user->admin_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($record->rent === 'بلی' && $record->rent_time === 'now') {
            DB::connection('market')->table('accountings')->insert([
                'expanses_type' => 'پول کرایه غرفه',
                'currency' => $record->currency, 
                'paid' => 1 * $record->rent_price,
                'type' => 'booth_rentmoney',
                'admin_id' => $user->role === 'superadmin' || $user->role === 'admin' ? $user->id : $user->admin_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
