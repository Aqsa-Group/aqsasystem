<?php

namespace App\Filament\Import\Resources\LoanResource\Pages;

use App\Filament\Import\Resources\LoanResource;
use App\Models\Import\Customer;
use App\Models\Import\Loan;
use App\Models\Import\Safe;
use Filament\Resources\Pages\CreateRecord;

class CreateLoan extends CreateRecord
{
    protected static string $resource = LoanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

protected function mutateFormDataBeforeCreate(array $data): array
{
    $customer = Customer::findOrFail($data['customer_id']);
    $type = $data['type'] ?? null;
    $amount = $data['amount'] ?? 0;
    $receipt = $data['loan_recipt'] ?? 0;
    $currency = $data['currency'] ?? 'USD';

    $safe = Safe::firstOrCreate([], [
        'USD' => 0,
        'AFN' => 0,
        'today' => 0,
        'last_update' => now()->toDateString(),
    ]);

    if ($type === 'رسید') {
        // محاسبه باقی‌مانده جدید
        $newRemaining = max(0, $customer->remaining_loan - $receipt);
        
        $data['amount'] = 0;
        $data['loan_recipt'] = $receipt;
        $data['reminded'] = $newRemaining; // ذخیره مقدار صحیح

        // به روز رسانی مشتری
        $customer->total_receipt += $receipt;
        $customer->remaining_loan = $newRemaining;
        $customer->save();

        // به روز رسانی صندوق
        if ($currency === 'دالر') {
            $safe->USD += $receipt;
        } elseif ($currency === 'افغانی') {
            $safe->AFN += $receipt;
        }
        $safe->today += $receipt;
        $safe->save();
    }

    if ($type === 'بردگی') {
        $data['amount'] = $amount;
        $data['loan_recipt'] = 0;
        $data['reminded'] = $customer->remaining_loan + $amount; // ذخیره مقدار صحیح

        // به روز رسانی مشتری
        $customer->total_loan += $amount;
        $customer->remaining_loan += $amount;
        $customer->save();

        // به روز رسانی صندوق
        if ($currency === 'دالر') {
            $safe->USD -= $amount;
        } elseif ($currency === 'افغانی') {
            $safe->AFN -= $amount;
        }
        $safe->today -= $amount;
        $safe->save();
    }

    return $data;
}


}
