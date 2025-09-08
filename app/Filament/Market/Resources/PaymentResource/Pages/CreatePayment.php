<?php

namespace App\Filament\Market\Resources\PaymentResource\Pages;

use App\Filament\Market\Resources\PaymentResource;
use App\Models\Market\Loan;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

     
    protected function mutateFormDataBeforeCreate(array $data): array
{
    $user = Auth::user();

    if ($user->role === 'superadmin' || $user->role === 'admin') {
        $data['admin_id'] = $user->id;
    } else {
        $data['admin_id'] = $user->admin_id;
    }

    return $data;
}


    protected function beforeCreate(): void
    {
        $state = $this->form->getState();
        $loan = Loan::find($state['loan_id']);

        if (!$loan) {
            Notification::make()
                ->title('قرضه پیدا نشد')
                ->danger()
                ->send();
            $this->halt();
        }

        $paidAmount = $loan->payments()->sum('amount');
        $remaining = $loan->amount - $paidAmount;

        if ($state['amount'] > $remaining) {
            Notification::make()
                ->title('مبلغ پرداخت بیشتر از مانده حساب است!')
                ->body("مقدار باقی‌مانده: {$remaining} {$state['currency']}")
                ->danger()
                ->send();
            $this->halt();
        }
    }

   
    protected function afterCreate(): void
    {
        $payment = $this->record;
        $loan = $payment->loan;
        $user = Auth::user();
    
        if ($user->role === 'superadmin' || $user->role === 'admin') {
            $adminIdToSave = $user->id;
        } else {
            $adminIdToSave = $user->admin_id;
        }
    
        DB::connection('market')->table('accountings')->insert([
            'expanses_type' => 'رسید از قرض ها',
            'currency' => $payment->currency,
            'paid' => $payment->amount,
            'type' => 'فرضه ها',
            'admin_id' => $adminIdToSave,  
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        Notification::make()
            ->title('پرداخت با موفقیت ثبت شد')
            ->success()
            ->send();
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
