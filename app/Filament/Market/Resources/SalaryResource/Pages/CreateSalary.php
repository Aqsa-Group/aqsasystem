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

                // در حالت رسید قرض باقیمانده ماه ذخیره نمی‌شود
                $data['remained'] = 0;
            }
        } else {
            // جمع باقیمانده ماه قبل با معاش ماه جاری
            $data['remained'] = max(($salary - $paid) + $lastRemained, 0);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $salary = $this->record;
        $user = Auth::user();

        $adminIdToSave = in_array($user->role, ['superadmin', 'admin']) ? $user->id : $user->admin_id;

        // ثبت رسید قرض
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

        // محاسبه مبلغ قابل برداشت از صندوق
        $amountToDeduct = $salary->is_reduce ? ($salary->salary - $salary->reduce_loan) : $salary->paid;

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
