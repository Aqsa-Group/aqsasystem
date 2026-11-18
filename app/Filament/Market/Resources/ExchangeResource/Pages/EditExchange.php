<?php

namespace App\Filament\Market\Resources\ExchangeResource\Pages;

use App\Filament\Market\Resources\ExchangeResource;
use App\Models\Market\Accounting;
use App\Models\Market\WithdrawLog;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // تاریخ انتخاب‌شده در فرم تبادله
        $createdAt = $record->created_at;

        DB::transaction(function () use ($record, $adminId, $createdAt) {

            // =====================================
            // 1. حذف رکوردهای قدیمی Accounting
            // =====================================

            Accounting::where('exchange_id', $record->id)
                ->where('type', 'تبادله صندوق')
                ->delete();

            // =====================================
            // 2. ثبت مجدد سمت برداشت
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
            // 3. ثبت مجدد سمت واریز
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
            // 4. حذف WithdrawLog قدیمی
            // =====================================

            WithdrawLog::where('exchange_id', $record->id)
                ->delete();

            // =====================================
            // 5. ثبت مجدد WithdrawLog
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

                // تاریخ اصلی تبادله
                'created_at'    => $createdAt,
            ]);

        });
    }

    protected function beforeDelete(): void
    {
        DB::transaction(function () {

            Accounting::where('exchange_id', $this->record->id)
                ->delete();

            WithdrawLog::where('exchange_id', $this->record->id)
                ->delete();

        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

