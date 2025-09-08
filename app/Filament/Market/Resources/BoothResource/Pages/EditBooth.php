<?php

namespace App\Filament\Market\Resources\BoothResource\Pages;

use App\Filament\Market\Resources\BoothResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Market\Booth;

class EditBooth extends EditRecord
{
    protected static string $resource = BoothResource::class;

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
        if (($data['expanses_type'] ?? null) !== 'کرایه غرفه' || $currentPayment <= 0) {
            return $data;
        }

        // booth_id ممکنه در فرم نباشه؛ از رکورد جاری استفاده می‌کنیم
        $boothId = $data['booth_id'] ?? ($this->record->id ?? null);
        $booth   = $boothId ? Booth::find($boothId) : null;

        $expType = ($booth && $booth->customer_id && $booth->collect === 'market')
            ? 'کرایه غرفه‌های گروی و سرقفلی'
            : 'کرایه غرفه';

        $existing = DB::connection('market')
            ->table('accountings')
            ->where('booth_id', $boothId)
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
                'currency'      =>  $data['currency'] ?? 'AFN', 
                'paid'          => $currentPayment,
                'type'          => 'income',
                'market_id'     => $data['market_id'] ?? ($booth->market_id ?? null),
                'booth_id'      => $boothId,
                'shopkeeper_id'=> $data['shopkeeper_id'] ?? ($booth->shopkeeper_id ?? null),
                'admin_id'      => (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin')
                    ? Auth::id()
                    : ($booth->admin_id ?? null),
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

        // سرقفلی غرفه
        if ($record->sarqofli === 'بلی') {
            $existing = DB::connection('market')->table('accountings')
                ->where('booth_id', $record->id)
                ->where('expanses_type', 'پول سرقفلی غرفه')
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
                    'expanses_type' => 'پول سرقفلی غرفه',
                    'currency'      => $record->currency,  
                    'paid'          => $record->sarqofli_price,
                    'type'          => 'booth_sarqoflimoney',
                    'market_id'     => $record->market_id,
                    'booth_id'      => $record->id,
                    'shopkeeper_id'=> $record->shopkeeper_id,
                    'admin_id'      => $user->role === 'superadmin' || $user->role === 'admin'
                        ? $user->id
                        : $user->admin_id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // گروی غرفه
        if ($record->rent === 'بلی') {
            $existing = DB::connection('market')->table('accountings')
                ->where('booth_id', $record->id)
                ->where('expanses_type', 'پول گروی غرفه')
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
                    'expanses_type' => 'پول گروی غرفه',
                    'currency'      => $record->currency, 
                    'paid'          => $record->rent_price,
                    'type'          => 'booth_mortagagemoney',
                    'market_id'     => $record->market_id,
                    'booth_id'      => $record->id,
                    'shopkeeper_id'=> $record->shopkeeper_id,
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
