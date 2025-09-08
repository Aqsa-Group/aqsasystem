<?php

namespace App\Filament\Market\Resources\SalaryResource\Pages;

use App\Filament\Market\Resources\SalaryResource;
use App\Models\Market\Loan;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateSalary extends CreateRecord
{
    protected static string $resource = SalaryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $salary = $data['salary'] ?? 0;
        $paid = $data['paid'] ?? 0;
        $lastRemained = $data['last_remained'] ?? 0;

        if (!empty($data['is_reduce']) && !empty($data['loan_id']) && !empty($data['reduce_loan'])) {
            $loan = Loan::find($data['loan_id']);

            if ($loan) {
                $data['loan'] = $loan->remainingAmount();
                $data['new_loan'] = $data['loan'] - $data['reduce_loan'];
                $data['paid'] = $data['reduce_loan'];

                // در حالت قرضه باقیمانده نباید ذخیره شود
                $data['remained'] = 0;
            }
        } else {
            $data['remained'] = ($salary - $paid) + $lastRemained;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $salary = $this->record;
        $user = Auth::user();
    
        // تعیین admin_id مناسب بر اساس نقش
        if ($user->role === 'superadmin' || $user->role === 'admin') {
            $adminIdToSave = $user->id;
        } else {
            $adminIdToSave = $user->admin_id;
        }
    
        if ($salary->is_reduce && $salary->loan_id && $salary->reduce_loan > 0) {
            $loan = Loan::find($salary->loan_id);
    
            if ($loan) {
                $loan->payments()->create([
                    'amount' => $salary->reduce_loan,
                    'date' => $salary->paid_date ?? now(),
                    'currency' => $salary->currency,
                ]);
            }
        }
    
        $amountToDeduct = $salary->is_reduce
            ? ($salary->salary - $salary->reduce_loan)
            : $salary->paid;
    
        if ($salary->reduce_from && $amountToDeduct > 0) {
    
            $currentBalance = DB::connection('market')->table('accountings')
                ->where('expanses_type', $salary->reduce_from)
                ->sum('paid');
    
            if ($currentBalance < $amountToDeduct) {
                Notification::make()
                    ->title('خطا')
                    ->body('موجودی حساب ' . $salary->reduce_from . ' کافی نیست.')
                    ->danger()
                    ->send();
    
                
                $salary->delete();
    
                return;
            }
    
            DB::connection('market')->table('accountings')->insert([
                'expanses_type' => $salary->reduce_from,
                'currency' => $salary->currency,
                'paid' => -1 * $amountToDeduct,
                'type' => 'Salary',
                'market_id' => $salary->market_id,
                'admin_id' => $adminIdToSave,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
