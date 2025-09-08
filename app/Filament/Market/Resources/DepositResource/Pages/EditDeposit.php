<?php

namespace App\Filament\Market\Resources\DepositResource\Pages;

use App\Filament\Market\Resources\DepositResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\Market\DepositLog;
use Illuminate\Support\Facades\Auth;

class EditDeposit extends EditRecord
{
    protected static string $resource = DepositResource::class;
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['paid'] = null;
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $deposit = $this->record->fresh();

        $currentPayment = $data['paid'] ?? 0;
        $lastPaid = $deposit->paid ?? 0;

        $totalPaid = $lastPaid + $currentPayment;
        $totalPrice = $deposit->price ?? 0;
        $remaining = max($totalPrice - $totalPaid, 0);

        DepositLog::create([
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
            'old_remained'    => $deposit->remained ?? $totalPrice,
            'new_paid'        => $currentPayment,
            'new_remained'    => $remaining,
        ]);

        $data['remained'] = $remaining;
        $data['paid'] = $totalPaid;

        return $data;
    }
    protected function getRedirectUrl(): string
    {
       
         return route('filament.market.resources.deposit-logs.index');
    }
}
