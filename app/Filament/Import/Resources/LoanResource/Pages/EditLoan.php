<?php

namespace App\Filament\Import\Resources\LoanResource\Pages;

use App\Filament\Import\Resources\LoanResource;
use App\Models\Import\Loan;
use Filament\Resources\Pages\EditRecord;

class EditLoan extends EditRecord
{
    protected static string $resource = LoanResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $totalLoan = Loan::where('customer_id', $data['customer_id'])
            ->where('type', 'بردگی')
            ->where('id', '!=', $this->record->id)
            ->sum('amount');

        $totalRecipt = Loan::where('customer_id', $data['customer_id'])
            ->where('type', 'رسید')
            ->where('id', '!=', $this->record->id)
            ->sum('loan_recipt');

        if ($data['type'] === 'بردگی') {
            $amount = $data['amount'] ?? 0;
            $data['reminded'] = ($totalLoan + $amount) - $totalRecipt;
        }

        if ($data['type'] === 'رسید') {
            $pastAmount = $data['past_amount'] ?? 0;
            $loanRecipt = $data['loan_recipt'] ?? 0;
            $data['reminded'] = $pastAmount - $loanRecipt;
        }

        return $data;
    }
}
