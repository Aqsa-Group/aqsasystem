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

     
        $safe = Safe::firstOrCreate([], [
            'total' => 0,
            'today' => 0,
            'last_update' => now()->toDateString(),
        ]);

        if ($type === 'رسید') {
       
            $customer->total_receipt += $receipt;
            $customer->remaining_loan -= $receipt;
            $customer->save();

         
            $data['amount'] = 0;
            $data['loan_recipt'] = $receipt;
            $data['reminded'] = $customer->remaining_loan; 
        }

        if ($type === 'بردگی') {
           
            $customer->total_loan += $amount;
            $customer->remaining_loan += $amount;
            $customer->save();

         
            $data['amount'] = $amount;
            $data['loan_recipt'] = 0;
            $data['reminded'] = $amount; 

            $safe->total -= $amount;
            $safe->save();
        }

        return $data;
    }
}
