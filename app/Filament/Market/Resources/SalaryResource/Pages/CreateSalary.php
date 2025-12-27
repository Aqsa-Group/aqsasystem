<?php

namespace App\Filament\Market\Resources\SalaryResource\Pages;

use App\Filament\Market\Resources\SalaryResource;
use App\Models\Market\Loan;
use App\Models\Market\Staff;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Morilog\Jalali\Jalalian;

class CreateSalary extends CreateRecord
{
    protected static string $resource = SalaryResource::class;

protected function mutateFormDataBeforeCreate(array $data): array
{
    $user = Auth::user();
    $data['admin_id'] = in_array($user->role, ['superadmin', 'admin'])
        ? $user->id
        : $user->admin_id;

    // ⚠️ remained همان مقداری است که از فرم آمده
    // فقط اطمینان از منفی نبودن
    $data['remained'] = max($data['remained'] ?? 0, 0);

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
                // ثبت پرداخت قرض
                $loan->payments()->create([
                    'amount' => $salary->reduce_loan,
                    'date' => $salary->paid_date ?? now(),
                    'currency' => $salary->currency,
                    'description' => 'کسر از معاش',
                ]);
                
                // آپدیت موجودی قرض - اگر ستون remaining_amount وجود دارد
                try {
                    if (isset($loan->remaining_amount)) {
                        $loan->update([
                            'remaining_amount' => max($loan->remaining_amount - $salary->reduce_loan, 0)
                        ]);
                    } else {
                        // اگر ستون paid_amount وجود دارد، آن را افزایش بده
                        $loan->increment('paid_amount', $salary->reduce_loan);
                    }
                } catch (\Exception $e) {
                    // اگر خطایی رخ داد، فقط لاگ کن
                    Log::error('Error updating loan: ' . $e->getMessage());
                }
            }
        }

        // محاسبه مبلغ قابل برداشت از صندوق
        $amountToDeduct = $salary->paid;

        if ($salary->reduce_from && $amountToDeduct > 0) {
            // محاسبه موجودی فعلی صندوق
            $currentBalance = DB::connection('market')->table('accountings')
                ->where('expanses_type', $salary->reduce_from)
                ->sum('paid');

            // اگر موجودی کافی نیست
            if ($currentBalance < $amountToDeduct) {
                Notification::make()
                    ->title('خطا')
                    ->body('موجودی حساب ' . $salary->reduce_from . ' کافی نیست. موجودی: ' . 
                           number_format($currentBalance) . ' ' . $salary->currency . 
                           ' - مبلغ مورد نیاز: ' . number_format($amountToDeduct) . ' ' . $salary->currency)
                    ->danger()
                    ->send();

                // حذف رکورد پرداخت معاش
                $salary->delete();
                return;
            }

            // ثبت برداشت از صندوق
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

        // نمایش پیام موفقیت‌آمیز
        Notification::make()
            ->title('پرداخت معاش با موفقیت ثبت شد')
            ->body('معاش ' . $salary->staff->fullname . ' به مبلغ ' . 
                   number_format($salary->paid) . ' ' . $salary->currency . 
                   ' پرداخت شد.' . 
                   ($salary->remained > 0 ? ' باقیمانده: ' . number_format($salary->remained) . ' ' . $salary->currency : '') .
                   ' روزهای پرداخت نشده: ' . $salary->unpaid_days)
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}