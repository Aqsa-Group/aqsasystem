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
    $currency = $data['currency'] ?? 'USD'; // پیش‌فرض USD

    $safe = Safe::firstOrCreate([], [
        'USD' => 0,
        'AFN' => 0,
        'today' => 0,
        'last_update' => now()->toDateString(),
    ]);

    if ($type === 'رسید') {
        // کاهش بدهی مشتری
        $customer->total_loan -= $receipt;
        $customer->remaining_loan -= $receipt;
        $customer->total_receipt += $receipt;
        $customer->save();

        // اضافه کردن پول به صندوق بر اساس ارز
        if ($currency === 'USD') {
            $safe->USD += $receipt;
        } elseif ($currency === 'AFN') {
            $safe->AFN += $receipt;
        }
        $safe->today += $receipt;
        $safe->save();

        $data['amount'] = 0;
        $data['loan_recipt'] = $receipt;
        $data['reminded'] = $customer->remaining_loan;
    }

    if ($type === 'بردگی') {
        // افزایش بدهی مشتری
        $customer->total_loan += $amount;
        $customer->remaining_loan += $amount;
        $customer->save();

        $data['amount'] = $amount;
        $data['loan_recipt'] = 0;
        $data['reminded'] = $amount;

        // کم کردن از صندوق بر اساس ارز
        if ($currency === 'USD') {
            $safe->USD -= $amount;
        } elseif ($currency === 'AFN') {
            $safe->AFN -= $amount;
        }
        $safe->today -= $amount;
        $safe->save();
    }

    return $data;
}


}
