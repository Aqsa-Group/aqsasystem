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

        $adminIdToSave = in_array($user->role, ['superadmin', 'admin'])
            ? $user->id
            : $user->admin_id;


        // ===============================
        // ثبت کسر قرض از معاش
        // ===============================
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


                // بروزرسانی قرض
                try {

                    if (isset($loan->remaining_amount)) {

                        $loan->update([
                            'remaining_amount' => max(
                                $loan->remaining_amount - $salary->reduce_loan,
                                0
                            )
                        ]);
                    } else {

                        $loan->increment(
                            'paid_amount',
                            $salary->reduce_loan
                        );
                    }
                } catch (\Exception $e) {

                    Log::error(
                        'Loan update error: ' . $e->getMessage()
                    );
                }
            }
        }



        // ===============================
        // برداشت از صندوق
        // ===============================
        $amountToDeduct = $salary->paid;


        if ($salary->reduce_from && $amountToDeduct > 0) {


            // ثبت برداشت (همیشه انجام می‌شود)
            DB::connection('market')->table('accountings')->insert([

                'expanses_type' => $salary->reduce_from,

                'currency' => $salary->currency,

                // منفی کردن مبلغ برداشت
                'paid' => -abs($amountToDeduct),

                'type' => 'Salary',

                'market_id' => $salary->market_id,

                'admin_id' => $adminIdToSave,

                'created_at' => now(),

                'updated_at' => now(),
            ]);
        }



        // ===============================
        // پیام موفقیت
        // ===============================
        Notification::make()
            ->title('پرداخت معاش با موفقیت ثبت شد')
            ->body(
                'معاش ' . $salary->staff->fullname .
                    ' به مبلغ ' .
                    number_format($salary->paid) .
                    ' ' . $salary->currency .

                    ($salary->remained > 0
                        ? ' | باقیمانده: ' .
                        number_format($salary->remained) .
                        ' ' . $salary->currency
                        : '') .

                    ' | روزهای پرداخت نشده: ' .
                    $salary->unpaid_days
            )
            ->success()
            ->send();
        $this->js("
        window.open('" . route('salary.print', $this->record->id) . "', '_blank');
    ");
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
