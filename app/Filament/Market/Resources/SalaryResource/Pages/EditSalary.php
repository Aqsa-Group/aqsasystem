<?php

namespace App\Filament\Market\Resources\SalaryResource\Pages;

use App\Filament\Market\Resources\SalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Market\Salary;
use App\Models\Market\Staff;
use App\Models\Market\Loan;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class EditSalary extends EditRecord
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * قبل از پر کردن فرم با داده‌های رکورد
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return $data;
        }

        // تنظیم salary_id برای استفاده در محاسبات
        $data['salary_id'] = $record->id;

        // دریافت اطلاعات کارمند
        $staff = Staff::find($record->staff_id);
        if (!$staff) {
            return $data;
        }

        // محاسبه حقوق روزانه
        $dailySalary = $staff->salary / 30;
        $data['daily_salary'] = round($dailySalary, 2);
        $data['salary'] = $staff->salary;

        // دریافت آخرین پرداخت قبلی (به جز رکورد جاری)
        $lastSalary = Salary::where('staff_id', $record->staff_id)
            ->where('market_id', $record->market_id)
            ->where('id', '!=', $record->id)
            ->orderBy('paid_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        // محاسبه روزهای پرداخت نشده
        $unpaidDays = 0;
        $lastRemained = 0;

        if ($lastSalary) {
            // تاریخ آخرین پرداخت قبلی
            $lastDate = Jalalian::fromDateTime($lastSalary->paid_date);
            // تاریخ فعلی (امروز)
            $currentDate = Jalalian::fromDateTime(now());
            
            // محاسبه روزهای بین آخرین پرداخت و امروز
            $unpaidDays = $this->calculateJalaliDaysDifference($lastDate, $currentDate);
            $unpaidDays = max(0, $unpaidDays);
            $lastRemained = $lastSalary->remained ?? 0;
            
            // اگر تفاوت روزها صفر یا کمتر است، حداقل 1 روز در نظر بگیر
            if ($unpaidDays == 0) {
                $unpaidDays = 1;
            }
        } else {
            // اولین پرداخت
            if ($staff->contract_start) {
                $startDate = Carbon::parse($staff->contract_start);
                if (!$startDate->isFuture()) {
                    $startJalali = Jalalian::fromDateTime($startDate);
                    $currentDate = Jalalian::fromDateTime(now());
                    $unpaidDays = $this->calculateJalaliDaysDifference($startJalali, $currentDate);
                    $unpaidDays = max(1, $unpaidDays);
                }
            } else {
                $currentDate = Jalalian::fromDateTime(now());
                $firstDayOfMonth = new Jalalian(
                    $currentDate->getYear(),
                    $currentDate->getMonth(),
                    1
                );
                $unpaidDays = $this->calculateJalaliDaysDifference($firstDayOfMonth, $currentDate);
                $unpaidDays = max(1, $unpaidDays + 1);
            }
        }

        // کل مبلغ قابل پرداخت
        $totalSalary = ($dailySalary * max(0, $unpaidDays)) + max(0, $lastRemained);
        
        // محاسبه باقیمانده
        $paid = (float) ($data['paid'] ?? 0);
        $remained = max(0, $totalSalary - $paid);

        // بروزرسانی داده‌ها
        $data['unpaid_days'] = max(0, $unpaidDays);
        $data['last_remained'] = round($lastRemained, 2);
        $data['remained'] = round($remained, 2);
        $data['daily_salary'] = round($dailySalary, 2);
        $data['paid_date'] = $record->paid_date; // حفظ تاریخ پرداخت اصلی

        // دریافت قرض
        $loan = Loan::where('staff_id', $record->staff_id)
            ->where('market_id', $record->market_id)
            ->latest()
            ->first();

        if ($loan && $loan->remainingAmount() > 0) {
            $data['loan_id'] = $loan->id;
            $data['loan'] = $loan->remainingAmount();
        } else {
            $data['loan_id'] = null;
            $data['loan'] = 0;
        }

        return $data;
    }

    /**
     * قبل از ذخیره، محاسبات نهایی را انجام می‌دهد
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();
        
        $staffId = $data['staff_id'] ?? $record?->staff_id;
        $marketId = $data['market_id'] ?? $record?->market_id;
        $salaryId = $data['salary_id'] ?? $record?->id;

        if (!$staffId || !$marketId) {
            return $data;
        }

        $staff = Staff::find($staffId);
        if (!$staff) {
            return $data;
        }

        // محاسبه حقوق روزانه
        $dailySalary = $staff->salary / 30;

        // دریافت آخرین پرداخت قبلی (به جز رکورد جاری)
        $lastSalary = Salary::where('staff_id', $staffId)
            ->where('market_id', $marketId)
            ->when($salaryId, function ($query) use ($salaryId) {
                $query->where('id', '!=', $salaryId);
            })
            ->orderBy('paid_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        // محاسبه روزهای پرداخت نشده
        $unpaidDays = 0;
        $lastRemained = 0;

        if ($lastSalary) {
            $lastDate = Jalalian::fromDateTime($lastSalary->paid_date);
            $currentDate = Jalalian::fromDateTime(now()); // تاریخ امروز
            $unpaidDays = $this->calculateJalaliDaysDifference($lastDate, $currentDate);
            $unpaidDays = max(0, $unpaidDays);
            $lastRemained = $lastSalary->remained ?? 0;
            
            // اگر تفاوت روزها صفر است، حداقل 1 روز
            if ($unpaidDays == 0) {
                $unpaidDays = 1;
            }
        } else {
            // اولین پرداخت
            if ($staff->contract_start) {
                $startDate = Carbon::parse($staff->contract_start);
                if (!$startDate->isFuture()) {
                    $startJalali = Jalalian::fromDateTime($startDate);
                    $currentDate = Jalalian::fromDateTime(now());
                    $unpaidDays = $this->calculateJalaliDaysDifference($startJalali, $currentDate);
                    $unpaidDays = max(1, $unpaidDays);
                }
            } else {
                $currentDate = Jalalian::fromDateTime(now());
                $firstDayOfMonth = new Jalalian(
                    $currentDate->getYear(),
                    $currentDate->getMonth(),
                    1
                );
                $unpaidDays = $this->calculateJalaliDaysDifference($firstDayOfMonth, $currentDate);
                $unpaidDays = max(1, $unpaidDays + 1);
            }
        }

        // کل مبلغ قابل پرداخت
        $totalSalary = ($dailySalary * max(0, $unpaidDays)) + max(0, $lastRemained);
        
        // محاسبه باقیمانده
        $paid = (float) ($data['paid'] ?? 0);
        $remained = max(0, $totalSalary - $paid);

        // بروزرسانی داده‌ها برای ذخیره
        $data['unpaid_days'] = max(0, $unpaidDays);
        $data['remained'] = round($remained, 2);
        $data['salary'] = $staff->salary;
        $data['paid_date'] = now(); // تاریخ پرداخت را به امروز تنظیم کن

        // اگر قرض کسر شده، آن را محاسبه کن
        if (isset($data['is_reduce']) && $data['is_reduce'] && isset($data['reduce_loan'])) {
            $loan = Loan::where('staff_id', $staffId)
                ->where('market_id', $marketId)
                ->latest()
                ->first();
            
            if ($loan) {
                $newLoanAmount = max(0, $loan->remainingAmount() - (float) $data['reduce_loan']);
                // به‌روزرسانی قرض
                $loan->remaining = $newLoanAmount;
                $loan->save();
            }
        }

        return $data;
    }

    /**
     * بعد از ذخیره
     */
    protected function afterSave(): void
    {
        $record = $this->getRecord();
        
        // اگر قرض کسر شده، آن را به‌روزرسانی کن
        if ($record->is_reduce && $record->reduce_loan > 0) {
            $loan = Loan::where('staff_id', $record->staff_id)
                ->where('market_id', $record->market_id)
                ->latest()
                ->first();
            
            if ($loan) {
                $newRemaining = max(0, $loan->remainingAmount() - $record->reduce_loan);
                $loan->remaining = $newRemaining;
                $loan->save();
            }
        }
    }

    /**
     * محاسبه تفاوت روزها بین دو تاریخ شمسی
     */
    private function calculateJalaliDaysDifference(Jalalian $startDate, Jalalian $endDate): int
    {
        try {
            $startCarbon = $startDate->toCarbon();
            $endCarbon = $endDate->toCarbon();
            return (int) $startCarbon->diffInDays($endCarbon);
        } catch (\Exception $e) {
            return (int) abs($endDate->getTimestamp() - $startDate->getTimestamp()) / (60 * 60 * 24);
        }
    }
}