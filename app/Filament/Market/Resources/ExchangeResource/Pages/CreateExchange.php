<?php

namespace App\Filament\Market\Resources\ExchangeResource\Pages;

use App\Filament\Market\Resources\ExchangeResource;
use App\Models\Market\Accounting;
use App\Models\Market\WithdrawLog;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateExchange extends CreateRecord
{
    protected static string $resource = ExchangeResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;
        $user = Auth::user();

        $adminId = in_array($user->role, ['superadmin', 'admin'])
            ? $user->id
            : $user->admin_id;

        // تاریخ انتخاب‌شده هنگام ثبت تبادله
        $createdAt = $record->created_at ?? now();

        DB::transaction(function () use ($record, $adminId, $createdAt) {

            // =====================================
            // 1. ثبت حسابداری سمت برداشت
            // =====================================
            Accounting::create([
                'expanses_type' => $record->from_type,
                'currency'      => $record->currency,
                'paid'          => -1 * $record->amount,
                'type'          => 'تبادله صندوق',
                'exchange_id'   => $record->id,
                'admin_id'      => $adminId,
                'created_at'    => $createdAt,
            ]);

            // =====================================
            // 2. ثبت حسابداری سمت واریز
            // =====================================
            Accounting::create([
                'expanses_type' => $record->to_type,
                'currency'      => $record->currency,
                'paid'          => $record->amount,
                'type'          => 'تبادله صندوق',
                'exchange_id'   => $record->id,
                'admin_id'      => $adminId,
                'created_at'    => $createdAt,
            ]);

            // =====================================
            // 3. ثبت لاگ برداشت
            // =====================================
            WithdrawLog::create([
                'expanses_type' => $record->from_type,
                'currency'      => $record->currency,
                'amount'        => $record->amount,
                'staff_id'      => null,
                'customer_id'   => null,

                'description'   => 'تبادله صندوق از '
                    . $record->from_type
                    . ' به '
                    . $record->to_type,

                'exchange_id'   => $record->id,
                'admin_id'      => $adminId,

                'created_at'    => $createdAt,
            ]);

        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
