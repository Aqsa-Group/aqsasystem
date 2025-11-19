<?php

namespace App\Livewire\Import;

use App\Models\Import\Safe;
use App\Models\Import\Staff;
use App\Models\Import\Salarys;
use App\Models\Import\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Salary extends Component
{
    use WithFileUploads;

    // Properties
    public $confirmDeleteId = null;
    public $staff_id;
    public $selectedStaff;
    public $currency = 'afn';
    public $amount;
    public $amountInWords;
    public $staffs;
    public $date;
    public $description;
    public $salaryId;
    public $search = '';
    public $selectedStaffId = null;
    public $salaries = [];
    public $filteredStaffs = [];

    public $salaryCards = [
        'total_salary' => 0,
        'total_paid' => 0,
        'remaining_salary' => 0,
        'percentage' => 0,
        'monthly_salary' => 0,
        'monthly_paid' => 0,
        'monthly_remaining' => 0,
        'monthly_percentage' => 0,
        'current_month_name' => '',
        'current_year' => ''
    ];

    // ==================== INITIALIZATION ====================

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->loadInitialData();
    }

    private function loadInitialData()
    {
        $user = Auth::guard('import')->user();

        if (!$user) {
            $this->staffs = collect();
            $this->salaries = collect();
            return;
        }

        $userId = $user->id;

        $this->staffs = Staff::select('id', 'name', 'salary')
            ->where('user_id', $userId)
            ->orderBy('name')
            ->get();

        $this->updateSalaries();
        $this->updateSalaryCards();
    }

    // ==================== SALARY CARDS CALCULATIONS ====================

    public function updateSalaryCards()
    {
        if (!$this->selectedStaffId) {
            $this->resetSalaryCards();
            return;
        }

        $user = Auth::guard('import')->user();
        $userId = $user->id;

        $staff = Staff::where('id', $this->selectedStaffId)
            ->where('user_id', $userId)
            ->first();

        $monthlySalary = $staff ? $staff->salary : 0;

        $totalPaid = Salarys::where('staff_id', $this->selectedStaffId)
            ->where('user_id', $userId)
            ->sum('amount');

        $currentJalali = Jalalian::now();
        $currentYear = $currentJalali->getYear();
        $currentMonth = $currentJalali->getMonth();

        $monthlyPaid = Salarys::where('staff_id', $this->selectedStaffId)
            ->where('user_id', $userId)
            ->whereRaw("YEAR(date) = ? AND MONTH(date) = ?", [$currentYear, $currentMonth])
            ->sum('amount');

        $totalSalary = $monthlySalary * 12;
        $remainingSalary = max(0, $totalSalary - $totalPaid);
        $percentage = $totalSalary > 0 ? round(($totalPaid / $totalSalary) * 100, 1) : 0;

        $monthlyRemaining = max(0, $monthlySalary - $monthlyPaid);
        $monthlyPercentage = $monthlySalary > 0 ? round(($monthlyPaid / $monthlySalary) * 100, 1) : 0;

        $this->salaryCards = [
            'total_salary' => $totalSalary,
            'total_paid' => $totalPaid,
            'remaining_salary' => $remainingSalary,
            'percentage' => $percentage,
            'monthly_salary' => $monthlySalary,
            'monthly_paid' => $monthlyPaid,
            'monthly_remaining' => $monthlyRemaining,
            'monthly_percentage' => $monthlyPercentage,
            'current_month_name' => $this->getCurrentAfghanMonth(),
            'current_year' => $currentYear
        ];
    }

    private function resetSalaryCards()
    {
        $this->salaryCards = [
            'total_salary' => 0,
            'total_paid' => 0,
            'remaining_salary' => 0,
            'percentage' => 0,
            'monthly_salary' => 0,
            'monthly_paid' => 0,
            'monthly_remaining' => 0,
            'monthly_percentage' => 0,
            'current_month_name' => $this->getCurrentAfghanMonth(),
            'current_year' => Jalalian::now()->getYear()
        ];
    }

    // ==================== SALARY SUBMISSION ====================

    public function submitSalary()
    {
        $this->validateSubmission();

        $user = Auth::guard('import')->user();
        $userId = $user->id;

        // بررسی آیا مجموع پرداخت‌ها از معاش ماهانه بیشتر می‌شود
        if ($this->checkSalaryLimit($userId)) {
            return;
        }

        if ($this->salaryId) {
            $this->updateExistingSalary($user);
        } else {
            $this->createNewSalary($user);
        }

        $this->refreshData();
        $this->resetForm();
    }

    private function validateSubmission()
    {
        $user = Auth::guard('import')->user();
        $userId = $user->id;

        $this->selectedStaff = (int) $this->selectedStaff;
        $this->amount = str_replace(',', '', $this->amount);

        $this->validate([
            'selectedStaff' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($userId) {
                    if (!Staff::where('id', $value)->where('user_id', $userId)->exists()) {
                        $fail('کارمند انتخاب شده در سیستم شما وجود ندارد.');
                    }
                }
            ],
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);
    }

    private function checkSalaryLimit($userId)
    {
        $selectedDate = Carbon::createFromFormat('Y/m/d', $this->date);

        $dateParts = explode('/', $this->date);
        $monthNumber = (int)$dateParts[1];
        $currentMonthName = $this->getAfghanMonthName($monthNumber);

        $staff = Staff::where('id', $this->selectedStaff)
            ->where('user_id', $userId)
            ->first();

        if (!$staff) {
            session()->flash('error', 'کارمند یافت نشد.');
            return true;
        }

        $monthlySalary = $staff->salary;

        $query = Salarys::where('staff_id', $this->selectedStaff)
            ->where('user_id', $userId)
            ->whereYear('date', $dateParts[0])
            ->whereMonth('date', $monthNumber);

        if ($this->salaryId) {
            $query->where('id', '!=', $this->salaryId);
        }

        $totalPaidThisMonth = $query->sum('amount');

        // پرداخت جدید
        $newPayment = (int)$this->amount;

        // بررسی آیا مجموع از معاش ماهانه بیشتر می‌شود
        $totalAfterPayment = $totalPaidThisMonth + $newPayment;

        if ($totalAfterPayment > $monthlySalary) {
            $remaining = max(0, $monthlySalary - $totalPaidThisMonth);

            if ($remaining == 0) {
                session()->flash(
                    'error',
                    ' معاش ' . $staff->name . ' برای ماه ' . $currentMonthName . ' به طور کامل پرداخت شده است. ' .
                        ' مبلغ پرداخت شده: ' . number_format($totalPaidThisMonth) . ' افغانی از ' . number_format($monthlySalary) . ' افغانی. ' .
                        ' برای پرداخت بیشتر، ماه جدید را انتخاب کنید.'
                );
            } else {
                session()->flash(
                    'error',
                    ' مجموع پرداخت‌های این ماه از معاش ماهانه بیشتر می‌شود. ' .
                        ' تاکنون: ' . number_format($totalPaidThisMonth) . ' افغانی پرداخت شده. ' .
                        ' معاش ماهانه: ' . number_format($monthlySalary) . ' افغانی. ' .
                        ' حداکثر مبلغ مجاز برای پرداخت: ' . number_format($remaining) . ' افغانی.'
                );
            }
            return true;
        }

        // اگر مجموع پرداخت‌ها دقیقاً برابر معاش ماهانه باشد، پیام موفقیت نشان بده
        if ($totalAfterPayment == $monthlySalary && $newPayment > 0) {
            session()->flash(
                'info',
                ' معاش ماهانه به طور کامل پرداخت شد! ' .
                    ' مبلغ کل: ' . number_format($monthlySalary) . ' افغانی'
            );
        }

        return false;
    }

    private function updateExistingSalary($user)
    {
        $oldSalary = Salarys::findOrFail($this->salaryId);

        Log::info("Updating salary", [
            'old_amount' => $oldSalary->amount,
            'new_amount' => $this->amount,
            'difference' => $this->amount - $oldSalary->amount
        ]);

        // منطق ساده: ابتدا مبلغ قدیمی برگشت، سپس مبلغ جدید کسر می‌شود
        $this->refundToSafe($user, $oldSalary->amount); // برگشت مبلغ قدیمی
        $this->deductFromSafe($user, $this->amount);   // کسر مبلغ جدید

        $oldSalary->update([
            'staff_id' => $this->selectedStaff,
            'amount' => $this->amount,
            'date' => $this->date,
            'description' => $this->description,
        ]);

        session()->flash('message', 'پرداخت معاش با موفقیت بروزرسانی شد.');
    }

    private function createNewSalary($user)
    {
        $salary = Salarys::create([
            'staff_id' => $this->selectedStaff,
            'user_id' => $user->id,
            'currency' => 'afn',
            'amount' => $this->amount,
            'date' => $this->date,
            'description' => $this->description,
        ]);

        // پرداخت جدید: از صندوق کم می‌شود
        $this->deductFromSafe($user, $this->amount);

        session()->flash('message', 'پرداخت معاش با موفقیت ثبت شد.');
    }

    // ==================== SAFE MANAGEMENT ====================

    private function deductFromSafe($user, $amount)
    {
        $userId = $user->id;

        $safe = Safe::firstOrCreate(
            ['user_id' => $userId],
            ['AFN' => 0]
        );

        $oldBalance = $safe->AFN;
        $safe->AFN -= $amount;
        $safe->save();

        Log::info("Safe deduction", [
            'user_id' => $userId,
            'old_balance' => $oldBalance,
            'amount' => $amount,
            'new_balance' => $safe->AFN,
            'operation' => 'DEDUCTION'
        ]);
    }

    private function refundToSafe($user, $amount)
    {
        $userId = $user->id;

        $safe = Safe::firstOrCreate(
            ['user_id' => $userId],
            ['AFN' => 0]
        );

        $oldBalance = $safe->AFN;
        $safe->AFN += $amount;
        $safe->save();

        Log::info("Safe refund", [
            'user_id' => $userId,
            'old_balance' => $oldBalance,
            'amount' => $amount,
            'new_balance' => $safe->AFN,
            'operation' => 'REFUND'
        ]);
    }

    // ==================== FORM MANAGEMENT ====================

    private function resetForm()
    {
        $this->reset([
            'selectedStaff',
            'amount',
            'amountInWords',
            'description',
            'salaryId',
        ]);

        $this->date = Jalalian::now()->format('Y/m/d');
        $this->currency = 'AFN';
    }

    public function formatAmount()
    {
        if ($this->amount) {
            $this->amount = number_format((int)$this->amount);
        }
    }

    // ==================== STAFF MANAGEMENT ====================

    public function selectStaff($staffId, $isEditing = false)
    {
        $this->selectedStaffId = $staffId;
        $this->selectedStaff = $staffId;
        $this->filteredStaffs = [];

        $user = Auth::guard('import')->user();
        $userId = $user->id;

        $staff = Staff::where('id', $staffId)
            ->where('user_id', $userId)
            ->first();

        if ($staff) {
            $this->search = $staff->name;

            if (!$isEditing && (empty($this->amount) || !$this->salaryId)) {
                $this->amount = $staff->salary;
                $this->formatAmount();
            }

            $this->updateSalaries();
            $this->updateSalaryCards();
        }
    }

    public function updatedSearch($value)
    {
        $user = Auth::guard('import')->user();
        $userId = $user->id;

        if (empty($value)) {
            $this->selectedStaffId = null;
            $this->filteredStaffs = [];
            $this->updateSalaries();
            return;
        }

        $this->filteredStaffs = Staff::where('user_id', $userId)
            ->where('name', 'like', "%{$value}%")
            ->limit(15)
            ->get();

        if (count($this->filteredStaffs) === 1) {
            $this->selectStaff($this->filteredStaffs[0]['id']);
        } else {
            $this->selectedStaffId = null;
            $this->updateSalaries();
        }
    }

    public function updatedSelectedStaff($value)
    {
        if ($value) {
            $isEditing = (bool)$this->salaryId;
            $this->selectStaff($value, $isEditing);
        }
    }

    // ==================== SALARY MANAGEMENT ====================

    public function edit($id)
    {
        $salary = Salarys::findOrFail($id);

        $this->salaryId = $id;
        $this->selectedStaff = $salary->staff_id;
        $this->amount = $salary->amount;
        $this->date = $salary->date;
        $this->description = $salary->description;

        $this->selectStaff($salary->staff_id, true);
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        $salary = Salarys::findOrFail($this->confirmDeleteId);
        $user = Auth::guard('import')->user();

        // حذف پرداخت: به صندوق اضافه می‌شود
        $this->refundToSafe($user, $salary->amount);
        $salary->delete();

        session()->flash('message', 'پرداخت معاش موفقیت حذف گردید.');

        $this->refreshData();
        $this->confirmDeleteId = null;
    }

    private function refreshData()
    {
        $this->updateSalaries();
        $this->updateSalaryCards();
    }

    public function updateSalaries()
    {
        $user = Auth::guard('import')->user();

        if (!$user) {
            $this->salaries = collect();
            return;
        }

        $userId = $user->id;

        $query = Salarys::with('staff')
            ->where('user_id', $userId);

        if ($this->selectedStaffId) {
            $query->where('staff_id', $this->selectedStaffId);
        }

        $this->salaries = $query->latest()->get();
    }

    // ==================== FILTERS ====================

    public function clearFilter()
    {
        $this->selectedStaffId = null;
        $this->selectedStaff = null;
        $this->search = '';
        $this->filteredStaffs = [];
        $this->refreshData();
        $this->resetForm();
    }

    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedStaffId = null;
        $this->selectedStaff = null;
        $this->filteredStaffs = [];
        $this->refreshData();
        $this->resetForm();
    }

    // ==================== UTILITIES ====================

    private function getAfghanMonthName($monthNumber)
    {
        $months = [
            1 => 'حمل',
            2 => 'ثور',
            3 => 'جوزا',
            4 => 'سرطان',
            5 => 'اسد',
            6 => 'سنبله',
            7 => 'میزان',
            8 => 'عقرب',
            9 => 'قوس',
            10 => 'جدی',
            11 => 'دلو',
            12 => 'حوت'
        ];

        return $months[$monthNumber] ?? 'نامشخص';
    }

    private function getCurrentAfghanMonth()
    {
        return $this->getAfghanMonthName(Jalalian::now()->getMonth());
    }

    // ==================== RENDER ====================

    public function render()
    {
        $user = Auth::guard('import')->user();

        if (!$user) {
            return view('livewire.import.salary', [
                'staffs' => collect(),
                'salaries' => collect(),
            ]);
        }

        $userId = $user->id;

        if (!$this->staffs || $this->staffs->isEmpty()) {
            $this->staffs = Staff::select('id', 'name', 'salary')
                ->where('user_id', $userId)
                ->orderBy('name')
                ->get();
        }

        return view('livewire.import.salary', [
            'staffs' => $this->staffs,
            'salaries' => $this->salaries,
        ]);
    }
}
