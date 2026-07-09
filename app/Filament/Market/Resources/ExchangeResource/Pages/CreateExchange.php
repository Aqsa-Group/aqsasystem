<?php

namespace App\Filament\Market\Resources\ExchangeResource\Pages;

use App\Filament\Market\Resources\ExchangeResource;
use App\Models\Market\Accounting;
use App\Models\Market\WithdrawLog; // اضافه کردن مدل WithdrawLog
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

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

        // ثبت در جدول accountings (سمت برداشت)
        Accounting::create([
            'expanses_type' => $record->from_type,
            'currency'      => $record->currency,
            'paid'          => -1 * $record->amount,
            'type'          => 'تبادله صندوق',
            'exchange_id'   => $record->id,
            'admin_id'      => $adminId,
        ]);

        // ثبت در جدول accountings (سمت واریز)
        Accounting::create([
            'expanses_type' => $record->to_type,
            'currency'      => $record->currency,
            'paid'          => $record->amount,
            'type'          => 'تبادله صندوق',
            'exchange_id'   => $record->id,
            'admin_id'      => $adminId,
        ]);

        // ====== ثبت در جدول withdraw_logs (برای سمت برداشت) ======
        WithdrawLog::create([
            'expanses_type' => $record->from_type,    // نوع برداشت
            'currency'      => $record->currency,
            'amount'        => $record->amount,       // مبلغ مثبت (برداشت)
            'staff_id'      => null,                  // در صورت نیاز مقداردهی کنید
            'customer_id'   => null,                  // در صورت نیاز مقداردهی کنید
            'description'   => 'تبادله صندوق از ' . $record->from_type . ' به ' . $record->to_type,
            'exchange_id'   => $record->id,
            'admin_id'      => $adminId,
            'created_at'    => now(),
            'updated_at'    => now(),
            
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}