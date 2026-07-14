<?php

namespace App\Filament\Market\Resources\DepositResource\Pages;

use App\Filament\Market\Resources\DepositResource;
use App\Models\Market\DepositLog;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditDeposit extends EditRecord
{
    protected static string $resource = DepositResource::class;

    protected ?int $depositLogId = null;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['paid'] = null;
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $deposit = $this->record->fresh();

        $newPayment = (int) ($data['paid'] ?? 0);

        $lastPaid = (int) $deposit->paid;
        $price = (int) $deposit->price;

        $remaining = $price - $lastPaid;

        if ($newPayment <= 0) {
            throw new \Exception('مبلغ پرداختی باید بیشتر از صفر باشد.');
        }

        if ($newPayment > $remaining) {
            throw new \Exception("مبلغ پرداختی نمی‌تواند از مقدار باقیمانده ({$remaining}) بیشتر باشد.");
        }

        $totalPaid = $lastPaid + $newPayment;
        $newRemaining = max($price - $totalPaid, 0);

        $log = DepositLog::create([
            'deposit_id'      => $deposit->id,
            'user_id'         => Auth::id(),
            'expanses_type'   => $deposit->accounting?->expanses_type,
            'market_id'       => $deposit->market_id,
            'shop_id'         => $deposit->shop_id,
            'shopkeeper_id'   => $deposit->shopkeeper_id,
            'market_name'     => $deposit->accounting?->market?->name,
            'shop_number'     => $deposit->accounting?->shop?->number,
            'shopkeeper_name' => $deposit->accounting?->shopkeeper?->fullname,
            'old_paid'        => $lastPaid,
            'old_remained'    => $deposit->remained,
            'new_paid' => $totalPaid,
            'new_remained'    => $newRemaining,
        ]);

        $this->depositLogId = $log->id;

        $data['paid'] = $totalPaid;
        $data['remained'] = $newRemaining;

        return $data;
    }

    protected function afterSave(): void
    {
        $url = route('deposit-log.print', $this->depositLogId);

        $this->js("
            window.open('{$url}', '_blank');
        ");
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
