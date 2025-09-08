<?php

namespace App\Filament\Market\Resources\ShopResource\Pages;

use App\Filament\Market\Resources\ShopResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Market\Shop;

class EditShop extends EditRecord
{
    protected static string $resource = ShopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $currentPayment = $data['paid'] ?? 0;

        // اگر expanases_type اصلاً در فرم نبود، یا کرایه نبود، یا مبلغی پرداخت نشده، هیچ کاری نکن
        if (($data['expanses_type'] ?? null) !== 'کرایه' || $currentPayment <= 0) {
            return $data;
        }

        // shop_id ممکنه در فرم نباشه؛ از رکورد جاری استفاده می‌کنیم
        $shopId = $data['shop_id'] ?? ($this->record->id ?? null);
        $shop   = $shopId ? Shop::find($shopId) : null;

        $expType = ($shop && $shop->customer_id && $shop->collect === 'market')
            ? 'کرایه دوکان‌های گروی و سرقفلی'
            : 'کرایه';

        $existing = DB::connection('market')
            ->table('accountings')
            ->where('shop_id', $shopId)
            ->where('expanses_type', $expType)
            ->first();

        if ($existing) {
            DB::connection('market')->table('accountings')
                ->where('id', $existing->id)
                ->update([
                    'paid'       => $existing->paid + $currentPayment,
                    'updated_at' => now(),
                ]);
        } else {
            DB::connection('market')->table('accountings')->insert([
                'expanses_type' => $expType,
                'currency'      => 'AFN',
                'paid'          => $currentPayment,
                'type'          => 'income',
                'market_id'     => $data['market_id'] ?? ($shop->market_id ?? null),
                'shop_id'       => $shopId,
                'shopkeeper_id' => $data['shopkeeper_id'] ?? ($shop->shopkeeper_id ?? null),
                'admin_id'      => (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin')
                    ? Auth::id()
                    : ($shop->admin_id ?? null),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        return $data;
    }

    protected function afterSave(): void
{
    $record = $this->record;
    $user = Auth::user();

    // سرقفلی
    if ($record->sarqofli === 'بلی') {
        $existing = DB::connection('market')->table('accountings')
            ->where('shop_id', $record->id)
            ->where('expanses_type', 'پول سرقفلی')
            ->first();

        if ($existing) {
            DB::connection('market')->table('accountings')
                ->where('id', $existing->id)
                ->update([
                    'paid'       => $record->sarqofli_price,
                    'updated_at' => now(),
                ]);
        } elseif ($record->sarqofli_time === 'now') {
            DB::connection('market')->table('accountings')->insert([
                'expanses_type' => 'پول سرقفلی',
                'currency'      => 'AFN',
                'paid'          => $record->sarqofli_price,
                'type'          => 'sarqoflimoney',
                'market_id'     => $record->market_id,
                'shop_id'       => $record->id,
                'shopkeeper_id' => $record->shopkeeper_id,
                'admin_id'      => $user->role === 'superadmin' || $user->role === 'admin'
                    ? $user->id
                    : $user->admin_id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    // گروی
    if ($record->rent === 'بلی') {
        $existing = DB::connection('market')->table('accountings')
            ->where('shop_id', $record->id)
            ->where('expanses_type', 'پول گروی')
            ->first();

        if ($existing) {
            DB::connection('market')->table('accountings')
                ->where('id', $existing->id)
                ->update([
                    'paid'       => $record->rent_price,
                    'updated_at' => now(),
                ]);
        } elseif ($record->rent_time === 'now') {
            DB::connection('market')->table('accountings')->insert([
                'expanses_type' => 'پول گروی',
                'currency'      => 'AFN',
                'paid'          => $record->rent_price,
                'type'          => 'mortagagemoney',
                'market_id'     => $record->market_id,
                'shop_id'       => $record->id,
                'shopkeeper_id' => $record->shopkeeper_id,
                'admin_id'      => $user->role === 'superadmin' || $user->role === 'admin'
                    ? $user->id
                    : $user->admin_id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}

}
