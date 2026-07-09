<?php

namespace App\Filament\Market\Resources\ExchangeResource\Pages;

use App\Filament\Market\Resources\ExchangeResource;
use App\Models\Market\Accounting;
use App\Models\Market\WithdrawLog;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditExchange extends EditRecord
{
    protected static string $resource = ExchangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $user = Auth::user();

        $adminId = in_array($user->role, ['superadmin', 'admin'])
            ? $user->id
            : $user->admin_id;

        // ====== 1. حذف رکوردهای قدیمی Accounting ======
        Accounting::where('exchange_id', $record->id)
            ->where('type', 'تبادله صندوق')
            ->delete();

        // ====== 2. ثبت مجدد در Accounting ======
        // سمت برداشت
        Accounting::create([
            'expanses_type' => $record->from_type,
            'currency'      => $record->currency,
            'paid'          => -1 * $record->amount,
            'type'          => 'تبادله صندوق',
            'exchange_id'   => $record->id,
            'admin_id'      => $adminId,
        ]);

        // سمت واریز
        Accounting::create([
            'expanses_type' => $record->to_type,
            'currency'      => $record->currency,
            'paid'          => $record->amount,
            'type'          => 'تبادله صندوق',
            'exchange_id'   => $record->id,
            'admin_id'      => $adminId,
        ]);

        // ====== 3. مدیریت WithdrawLog ======
        // حذف رکوردهای قدیمی
        WithdrawLog::where('exchange_id', $record->id)->delete();

        // ثبت رکورد جدید (سمت برداشت)
        WithdrawLog::create([
            'expanses_type' => $record->from_type,
            'currency'      => $record->currency,
            'amount'        => $record->amount,
            'staff_id'      => null,
            'customer_id'   => null,
            'description'   => 'تبادله صندوق از ' . $record->from_type . ' به ' . $record->to_type,
            'exchange_id'   => $record->id,
            'admin_id'      => $adminId,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    protected function beforeDelete(): void
    {
        // حذف رکوردهای Accounting مرتبط
        Accounting::where('exchange_id', $this->record->id)->delete();

        // حذف رکوردهای WithdrawLog مرتبط
        WithdrawLog::where('exchange_id', $this->record->id)->delete();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}