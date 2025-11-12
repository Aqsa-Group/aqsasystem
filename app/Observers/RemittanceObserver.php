<?php

namespace App\Observers;

use App\Models\Sarafi\Remittances;
use App\Models\Sarafi\Transaction;

class RemittanceObserver
{
    public function updated(Remittances $remittance)
    {
        // اگر state به 1 تغییر کرد و قبلا 0 بوده
        if ($remittance->state == 1 && $remittance->getOriginal('state') == 0) {
            $this->createTransactionForRecipient($remittance);
        }
    }

    public function created(Remittances $remittance)
    {
        // اگر از اول state=1 باشد
        if ($remittance->state == 1) {
            $this->createTransactionForRecipient($remittance);
        }
    }

    private function createTransactionForRecipient(Remittances $remittance)
    {
        $user = auth()->guard('sarafi')->user();
        
        Transaction::create([
            'customer_id' => $remittance->to_account, // حساب مقصد
            'user_id' => $user->id ?? $remittance->user_id,
            'admin_id' => $user->admin_id ?? $remittance->admin_id,
            'currency' => $remittance->currency,
            'amount' => $remittance->amount,
            'type' => 'رسید',
            'account_type' => 'بانکی',
            'date' => $remittance->date,
            'description' => $this->generateTransactionDescription($remittance),
            'zone' => $remittance->zone,
            'by' => $remittance->giver_name,
            'conversion_in_account_id' => null, // یا اگر نیاز دارید مقدار بدهید
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function generateTransactionDescription(Remittances $remittance)
    {
        return "رسید حواله از حساب {$remittance->source_account} - کد رهگیری: {$remittance->tracking_code}";
    }
}