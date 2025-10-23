<?php

namespace App\Livewire\ToolsPanel;

use App\Models\Tools\CurrencySafe;
use App\Models\Tools\Staffs;
use App\Models\Tools\Salarys;
use App\Models\Tools\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class Salary extends Component
{
    use WithFileUploads;
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
        'percentage' => 0
    ];

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
    public function updateSalaryCards()
    {
        if (!$this->selectedStaffId) {
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
            return;
        }

        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        // دریافت معاش ماهانه کارمند
        $staff = Staffs::find($this->selectedStaffId);
        $monthlySalary = $staff ? $staff->salary : 0;

        // محاسبه کل پرداختی‌ها (همه زمان)
        $totalPaid = Salarys::where('staff_id', $this->selectedStaffId)
            ->where('admin_id', $adminId)
            ->sum('amount');

        // محاسبه پرداختی‌های ماه جاری - روش بهبود یافته
        $currentJalali = Jalalian::now();
        $currentYear = $currentJalali->getYear();
        $currentMonth = $currentJalali->getMonth();

        // روش 1: استفاده از فیلتر روی کلکسیون برای اطمینان بیشتر
        $allSalaries = Salarys::where('staff_id', $this->selectedStaffId)
            ->where('admin_id', $adminId)
            ->get();

        // در متد updateSalaryCards به جای فیلتر، از این استفاده کنید:
        $monthlyPaid = Salarys::where('staff_id', $this->selectedStaffId)
            ->where('admin_id', $adminId)
            ->whereRaw("YEAR(date) = ? AND MONTH(date) = ?", [$currentYear, $currentMonth])
            ->sum('amount');

        // محاسبات سالانه
        $totalSalary = $monthlySalary * 12;
        $remainingSalary = max(0, $totalSalary - $totalPaid);
        $percentage = $totalSalary > 0 ? round(($totalPaid / $totalSalary) * 100, 1) : 0;

        // محاسبات ماهانه
        $monthlyRemaining = max(0, $monthlySalary - $monthlyPaid);
        $monthlyPercentage = $monthlySalary > 0 ? round(($monthlyPaid / $monthlySalary) * 100, 1) : 0;

        // دیباگ اطلاعات
        \Log::info("Salary Cards Debug", [
            'staff_id' => $this->selectedStaffId,
            'monthly_salary' => $monthlySalary,
            'total_paid' => $totalPaid,
            'monthly_paid' => $monthlyPaid,
            'current_month' => $currentMonth,
            'current_year' => $currentYear,
            'all_salaries_count' => $allSalaries->count(),
            'all_salaries_dates' => $allSalaries->pluck('date')->toArray()
        ]);

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

    public function debugMonthlyPayment()
    {
        if (!$this->selectedStaffId) {
            return "No staff selected";
        }

        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        $currentJalali = Jalalian::now();
        $currentYear = $currentJalali->getYear();
        $currentMonth = $currentJalali->getMonth();

        $allSalaries = Salarys::where('staff_id', $this->selectedStaffId)
            ->where('admin_id', $adminId)
            ->get();

        $debugInfo = [
            'current_month' => $currentMonth,
            'current_year' => $currentYear,
            'current_month_name' => $this->getAfghanMonthName($currentMonth),
            'all_salaries' => []
        ];

        foreach ($allSalaries as $salary) {
            try {
                $salaryDate = Jalalian::fromFormat('Y/m/d', $salary->date);
                $isCurrentMonth = $salaryDate->getYear() == $currentYear &&
                    $salaryDate->getMonth() == $currentMonth;

                $debugInfo['all_salaries'][] = [
                    'id' => $salary->id,
                    'date' => $salary->date,
                    'amount' => $salary->amount,
                    'parsed_year' => $salaryDate->getYear(),
                    'parsed_month' => $salaryDate->getMonth(),
                    'parsed_month_name' => $this->getAfghanMonthName($salaryDate->getMonth()),
                    'is_current_month' => $isCurrentMonth
                ];
            } catch (\Exception $e) {
                $debugInfo['all_salaries'][] = [
                    'id' => $salary->id,
                    'date' => $salary->date,
                    'amount' => $salary->amount,
                    'error' => $e->getMessage()
                ];
            }
        }

        dd($debugInfo);
    }


    private function getCurrentAfghanMonth()
    {
        return $this->getAfghanMonthName(Jalalian::now()->getMonth());
    }
    public function submitSalary()
    {
        $this->selectedStaff = (int) $this->selectedStaff;
        $this->amount = str_replace(',', '', $this->amount);
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        // اعتبارسنجی
        $this->validate([
            'selectedStaff' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($adminId) {
                    $exists = Staffs::where('id', $value)
                        ->where('admin_id', $adminId)
                        ->exists();
                    if (!$exists) {
                        $fail('کارمند انتخاب شده در سیستم شما وجود ندارد.');
                    }
                }
            ],
            'amount'        => 'required|numeric|min:1',
            'date'          => 'required|date',
            'description'   => 'nullable|string|max:500',
        ]);

        // بررسی تکراری نبودن پرداخت برای ماه مشابه
        $selectedDate = Carbon::createFromFormat('Y/m/d', $this->date);
        $yearMonth = $selectedDate->format('Y-m');

        $existingSalary = Salarys::where('staff_id', $this->selectedStaff)
            ->where('admin_id', $adminId)
            ->whereDate('date', '>=', $yearMonth . '-01')
            ->whereDate('date', '<=', $yearMonth . '-31')
            ->first();

        if ($existingSalary && $existingSalary->id != $this->salaryId) {
            session()->flash('error', 'برای این کارمند در ماه ' . $yearMonth . ' قبلاً پرداخت معاش ثبت شده است.');
            return;
        }

        $data = [
            'staff_id'        => $this->selectedStaff,
            'user_id'         => $user->id,
            'admin_id'        => $adminId,
            'currency'        => 'afn',
            'amount'          => $this->amount,
            'date'            => $this->date,
            'description'     => $this->description,
        ];

        if ($this->salaryId) {
            $old = Salarys::findOrFail($this->salaryId);

            // برگشت معاش قدیمی
            $this->applyCurrencyChange($user, $old->amount, true);

            $old->update($data);

            // اعمال معاش جدید
            $this->applyCurrencyChange($user, $this->amount);

            session()->flash('message', 'پرداخت معاش با موفقیت بروزرسانی شد.');
        } else {
            Salarys::create($data);

            $this->applyCurrencyChange($user, $this->amount);

            session()->flash('message', 'پرداخت معاش با موفقیت ثبت شد.');
        }

        $this->updateSalaries();
        $this->updateSalaryCards();
        $this->resetForm();
    }


    private function applyCurrencyChange($user, $amount, $reverse = false)
    {
        $adminId = $user->admin_id ?? $user->id;

        $factor = $reverse ? 1 : -1;
        $change = $amount * $factor;

        $safe = CurrencySafe::firstOrCreate(
            ['user_id' => $adminId, 'admin_id' => null],
            ['afn' => 0]
        );

        $safe->afn += $change;
        $safe->save();

        Log::info("Currency safe updated for salary", [
            'amount' => $amount,
            'change' => $change,
            'new_balance' => $safe->afn,
            'reverse' => $reverse
        ]);
    }

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->updateSalaries();
        $this->updateSalaryCards();

        $user = Auth::guard('tools')->user();
        if (!$user) {
            $this->staffs = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        $this->staffs = Staffs::select('id', 'name', 'lastname', 'job', 'salary')
            ->where('admin_id', $adminId)
            ->orderBy('name')
            ->orderBy('lastname')
            ->get();

        $this->staffs = collect($this->staffs);
    }

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
        $this->currency = 'afn';
    }

    public function formatAmount()
    {
        if ($this->amount) {
            $this->amount = number_format((int)$this->amount);
        }
    }

    public function edit($id)
    {
        $salary = Salarys::findOrFail($id);
        $this->salaryId = $id;
        $this->selectedStaff = $salary->staff_id;
        $this->amount = $salary->amount;
        $this->date = $salary->date;
        $this->description = $salary->description;

        // انتخاب کارمند برای نمایش اطلاعات
        $this->selectStaff($salary->staff_id);
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        $salary = Salarys::findOrFail($this->confirmDeleteId);

        $user = Auth::guard('tools')->user();
        $this->applyCurrencyChange($user, $salary->amount, true);

        $salary->delete();

        session()->flash('message', 'پرداخت معاش موفقیت حذف گردید.');

        $this->updateSalaries();
        $this->updateSalaryCards();
        $this->confirmDeleteId = null;
    }

    public function updatedSearch($value)
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        if (empty($value)) {
            $this->selectedStaffId = null;
            $this->filteredStaffs = [];
            $this->updateSalaries();
            return;
        }

        $this->filteredStaffs = Staffs::where('admin_id', $adminId)
            ->where(function ($q) use ($value) {
                $q->where('name', 'like', "%{$value}%")
                    ->orWhere('lastname', 'like', "%{$value}%")
                    ->orWhere('job', 'like', "%{$value}%");
            })
            ->limit(15)
            ->get();

        if ($this->filteredStaffs->count() === 1) {
            $this->selectStaff($this->filteredStaffs->first()->id);
        } else {
            $this->selectedStaffId = null;
            $this->updateSalaries();
        }
    }

    public function selectStaff($staffId)
    {
        $this->selectedStaffId = $staffId;
        $this->selectedStaff = $staffId;
        $this->filteredStaffs = [];

        $staff = Staffs::find($staffId);
        if ($staff) {
            $this->search = $staff->name . ' ' . $staff->lastname;

            // پر کردن خودکار مقدار معاش
            $this->amount = $staff->salary;
            $this->formatAmount();

            if (!$this->staffs->contains('id', $staff->id)) {
                $this->staffs->push($staff);
            }

            $this->updateSalaries();
            $this->updateSalaryCards();

            Log::debug("Staff selected", [
                'staff_id' => $staffId,
                'staff_name' => $staff->name . ' ' . $staff->lastname,
                'salary' => $staff->salary
            ]);
        }
    }

    public function updatedSelectedStaff($value)
    {
        if ($value) {
            $this->selectStaff($value);
        }
    }

    public function print($salaryId)
    {
        $salary = Salarys::with(['staff', 'user'])->findOrFail($salaryId);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 190],
            'directionality' => 'rtl',
            'margin_top' => 2,
            'margin_bottom' => 2,
            'margin_left' => 2,
            'margin_right' => 2,
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'Shabnam' => [
                    'R' => 'Shabnam-FD.ttf',
                ],
            ],
            'default_font' => 'Shabnam',
        ]);

        $mpdf->SetAutoPageBreak(false);

        $html = view('pdf.Tools.salary-print', compact('salary'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'سند معاش شماره' . $salary->id . '_به_اسم_' . $salary->staff->name . '_' . $salary->staff->lastname . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    public function clearFilter()
    {
        $this->selectedStaffId = null;
        $this->selectedStaff = null;
        $this->search = '';
        $this->filteredStaffs = [];
        $this->updateSalaries();
        $this->updateSalaryCards();
        $this->resetForm();
    }

    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedStaffId = null;
        $this->selectedStaff = null;
        $this->filteredStaffs = [];
        $this->updateSalaries();
        $this->updateSalaryCards();
        $this->resetForm();
    }

    public function updateSalaries()
    {
        $user = Auth::guard('tools')->user();
        if (!$user) {
            $this->salaries = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        $query = Salarys::with('staff')
            ->where('admin_id', $adminId);

        if ($this->selectedStaffId) {
            $query->where('staff_id', $this->selectedStaffId);
        }

        $this->salaries = $query->latest()->get();
    }

    public function render()
    {
        $user = Auth::guard('tools')->user();

        if (!$user) {
            return view('livewire.tools-panel.salary', [
                'staffs' => collect(),
                'salaries' => collect(),
            ]);
        }

        $adminId = $user->admin_id ?? $user->id;

        if (!$this->staffs || $this->staffs->isEmpty()) {
            $this->staffs = Staffs::select('id', 'name', 'lastname', 'job', 'salary')
                ->where('admin_id', $adminId)
                ->orderBy('name')
                ->orderBy('lastname')
                ->get();

            $this->staffs = collect($this->staffs);
        }

        return view('livewire.tools-panel.salary', [
            'staffs' => $this->staffs,
            'salaries' => $this->salaries,
        ]);
    }
}
