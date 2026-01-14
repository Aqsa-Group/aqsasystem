<?php

namespace App\Livewire\Sarafi;

use App\Helpers\DateHelper;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Salaries;
use App\Models\Sarafi\StaffAttendance;
use App\Models\Sarafi\Staffs;
use App\Models\Sarafi\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Salary extends Component
{
    public $staffs = [];
    public $selectedStaffId = null;
    public $staffDetails = null;
    public $dueDays = 0;
    public $dueAmount = 0;
    public $paymentMethod = 'نقدی';
    public $customers = [];
    public $selectedCustomerId = null;
    public $selectedCustomer = null;
    public $description = '';
    public $salaryHistory = [];
    public $search = '';
    public $selectedAccount = null;
    public $currencies = [];
    public $currency = 'afn';
    public $amount = '';
    public $date;

    // برای نمایش اطلاعات مشتری
    public $customerCashBalances = [];
    public $customerBankBalances = [];
    public $customerTotalBalances = [];

    // برای محاسبه حقوق بر اساس حضور و غیاب
    public $attendanceData = [];

    public function mount()
    {
        $this->staffs = Staffs::all();
        $this->customers = Customer::all();
        $this->date = Jalalian::now()->format('Y/m/d');


        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $this->salaryHistory = Salaries::with(['staff'])
            ->orderBy('paid_date', 'desc')
            ->where('admin_id', $adminId)
            ->limit(30)
            ->get();

        // تعریف ارزها
        $this->currencies = [
            ['code' => 'afn', 'name_fa' => 'افغانی', 'name_en' => 'Afghani'],
            ['code' => 'USD', 'name_fa' => 'دالر', 'name_en' => 'US Dollar'],
            ['code' => 'EUR', 'name_fa' => 'یورو', 'name_en' => 'Euro'],
            ['code' => 'IRR', 'name_fa' => 'تومان', 'name_en' => 'Iranian Rial'],
            ['code' => 'AED', 'name_fa' => 'درهم', 'name_en' => 'UAE Dirham'],
            ['code' => 'TRY', 'name_fa' => 'لیره', 'name_en' => 'Turkish Lira'],
            ['code' => 'CNY', 'name_fa' => 'یوان', 'name_en' => 'Chinese Yuan'],
            ['code' => 'PKR', 'name_fa' => 'کلدار', 'name_en' => 'Pakistani Rupee'],
            ['code' => 'GBP', 'name_fa' => 'پوند', 'name_en' => 'British Pound'],
            ['code' => 'JPY', 'name_fa' => 'ین', 'name_en' => 'Japanese Yen'],
            ['code' => 'SAR', 'name_fa' => 'ریال', 'name_en' => 'Saudi Riyal'],
            ['code' => 'INR', 'name_fa' => 'روپیه', 'name_en' => 'Indian Rupee'],
        ];
    }

    public function updatedSelectedStaffId($value)
    {
        if ($value) {
            $this->staffDetails = Staffs::find($value);

            // اگر کارمند customer_id دارد، آن را به عنوان مشتری پیش فرض تنظیم کن
            if ($this->staffDetails->customer_id) {
                $this->selectedCustomerId = $this->staffDetails->customer_id;
                $this->selectCustomer($this->selectedCustomerId);
            }

            $this->calculateDueSalary();
            $this->loadSalaryHistory();

            // تنظیم ارز پیش فرض بر اساس واحد حقوق کارمند (فرض بر افغانی)
            $this->currency = 'afn';
        } else {
            $this->staffDetails = null;
            $this->dueDays = 0;
            $this->dueAmount = 0;
            $this->salaryHistory = [];
            $this->attendanceData = [];
        }
    }
    public function calculateDueSalary()
    {
        if (!$this->staffDetails) {
            return;
        }

        Log::info("=== Starting Salary Calculation ===");
        Log::info("Staff: " . $this->staffDetails->name);
        Log::info("Salary Amount: " . $this->staffDetails->salary_amount);
        Log::info("Final Salary: " . $this->staffDetails->final_salary);

        // بررسی اینکه آیا کارمند حقوق دارد
        if (empty($this->staffDetails->salary_amount) || $this->staffDetails->salary_amount <= 0) {
            session()->flash('error', 'حقوق ماهیانه برای این کارمند تعریف نشده است!');
            $this->dueDays = 0;
            $this->dueAmount = 0;
            $this->attendanceData = [];
            return;
        }

        // پیدا کردن آخرین پرداخت معاش
        $lastSalary = Salaries::where('staff_id', $this->selectedStaffId)
            ->latest('paid_date')
            ->first();

        Log::info("Last Salary Found: " . ($lastSalary ? $lastSalary->paid_date : 'None'));

        $startDate = null;
        $endDate = Carbon::now()->startOfDay();

        if ($lastSalary) {
            $startDate = Carbon::parse($lastSalary->paid_date)->addDay()->startOfDay();
        } else {
            // اگر پرداختی نبود، از تاریخ شروع قرارداد شروع کن
            if ($this->staffDetails->contract_start) {
                try {
                    // تاریخ شروع را از شمسی به میلادی تبدیل کن
                    $startDate = $this->convertJalaliToCarbon($this->staffDetails->contract_start);
                    Log::info("Contract Start: " . $this->staffDetails->contract_start . " -> " . $startDate->format('Y-m-d'));
                } catch (\Exception $e) {
                    Log::error("Date conversion error: " . $e->getMessage());
                    $startDate = Carbon::now()->subMonth()->startOfDay();
                }
            } else {
                $startDate = Carbon::now()->subMonth()->startOfDay();
                Log::info("No contract start, using last month");
            }
        }

        // بررسی تاریخ پایان قرارداد
        if ($this->staffDetails->contract_end) {
            try {
                $contractEnd = $this->convertJalaliToCarbon($this->staffDetails->contract_end);
                if ($contractEnd->lessThan($endDate)) {
                    $endDate = $contractEnd;
                    Log::info("Contract End: " . $this->staffDetails->contract_end . " -> " . $endDate->format('Y-m-d'));
                }
            } catch (\Exception $e) {
                Log::error("Contract end date error: " . $e->getMessage());
            }
        }

        // اطمینان از صحت بازه تاریخی
        if ($startDate->greaterThan($endDate)) {
            Log::info("Start date is after end date");
            $this->dueDays = 0;
            $this->dueAmount = 0;
            $this->attendanceData = [];
            return;
        }

        Log::info("Date Range: " . $startDate->format('Y-m-d') . " to " . $endDate->format('Y-m-d'));

        // محاسبه از حضور و غیاب
        $this->calculateSalaryFromAttendance($startDate, $endDate);
    }

    // متد کمکی برای تبدیل تاریخ شمسی به میلادی
    private function convertJalaliToCarbon($jalaliDate)
    {
        // پاکسازی تاریخ
        $jalaliDate = trim(str_replace('/', '-', $jalaliDate));

        // اگر تاریخ خالی باشد
        if (empty($jalaliDate)) {
            throw new \Exception("تاریخ خالی است");
        }

        $parts = explode('-', $jalaliDate);

        if (count($parts) === 3) {
            $year = (int)$parts[0];
            $month = (int)$parts[1];
            $day = (int)$parts[2];

            // اعتبارسنجی تاریخ
            if ($year < 1300 || $year > 1500 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
                throw new \Exception("فرمت تاریخ نامعتبر: " . $jalaliDate);
            }

            $jalalian = new Jalalian($year, $month, $day);
            return $jalalian->toCarbon()->startOfDay();
        }

        throw new \Exception("فرمت تاریخ نامعتبر: " . $jalaliDate);
    }

    private function calculateSalaryFromAttendance($startDate, $endDate)
    {
        $totalSalary = 0;
        $attendanceDays = [];

        Log::info("=== Calculating from Attendance ===");
        Log::info("Staff ID: " . $this->selectedStaffId);

        // تمام رکوردهای حضور و غیاب این کارمند را بگیر
        $allAttendances = StaffAttendance::where('staff_id', $this->selectedStaffId)
            ->orderBy('attendance_date', 'asc')
            ->get();

        Log::info("Total attendance records in DB: " . $allAttendances->count());

        $currentDate = clone $startDate;

        while ($currentDate->lte($endDate)) {
            try {
                // تبدیل به تاریخ شمسی
                $jalaliDate = Jalalian::fromCarbon($currentDate);
                $currentJalali = $jalaliDate->format('Y-m-d');

                // جستجوی رکورد حضور و غیاب
                $attendance = null;
                foreach ($allAttendances as $att) {
                    if ($this->normalizeDate($att->attendance_date) == $this->normalizeDate($currentJalali)) {
                        $attendance = $att;
                        break;
                    }
                }

                if ($attendance) {
                    $dailySalary = $attendance->daily_salary;

                    // اگر daily_salary صفر یا null بود، محاسبه کن
                    if (empty($dailySalary) || $dailySalary == 0) {
                        $dailySalary = $attendance->calculateDailySalary();
                        Log::info("Calculated daily salary for " . $currentJalali . ": " . $dailySalary);
                    }

                    if ($dailySalary > 0) {
                        $totalSalary += $dailySalary;
                        $attendanceDays[] = [
                            'date' => $attendance->attendance_date,
                            'salary' => $dailySalary,
                            'morning_present' => $attendance->morning_present,
                            'evening_present' => $attendance->evening_present,
                            'leave_type' => $attendance->leave_type,
                            'is_paid' => $attendance->is_paid
                        ];
                    }
                } else {
                    Log::info("No attendance found for date: " . $currentJalali);

                    // اگر رکورد حضور و غیاب نبود، به عنوان روز کاری کامل حساب کن
                    // اما فقط اگر تاریخ بعد از قرارداد شروع باشد
                    if ($this->shouldCalculateSalaryForDate($currentDate)) {
                        $dailySalary = $this->calculateDefaultDailySalary();
                        if ($dailySalary > 0) {
                            $totalSalary += $dailySalary;
                            $attendanceDays[] = [
                                'date' => $currentJalali,
                                'salary' => $dailySalary,
                                'morning_present' => true,
                                'evening_present' => true,
                                'leave_type' => 'none',
                                'is_paid' => false
                            ];
                            Log::info("Added default salary for " . $currentJalali . ": " . $dailySalary);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error processing date " . $currentDate->format('Y-m-d') . ": " . $e->getMessage());
            }

            $currentDate->addDay();
        }

        $this->dueAmount = round($totalSalary);
        $this->dueDays = count($attendanceDays);
        $this->attendanceData = $attendanceDays;

        Log::info("Final Calculation - Total Salary: " . $totalSalary . ", Days: " . count($attendanceDays));

        // مقدار amount را بروزرسانی کن
        $this->amount = $this->dueAmount > 0 ? number_format($this->dueAmount) : '';
    }

    // متد کمکی برای نرمال‌سازی تاریخ
    private function normalizeDate($date)
    {
        $date = trim($date);
        $date = str_replace('/', '-', $date);
        $date = str_replace('\\', '-', $date);
        $date = preg_replace('/[^\d\-]/', '', $date);
        return $date;
    }

    // بررسی آیا برای این تاریخ باید معاش حساب کرد
    private function shouldCalculateSalaryForDate($date)
    {
        // بررسی تعطیلات آخر هفته (جمعه)
        if ($date->dayOfWeek == Carbon::FRIDAY) {
            return false;
        }

        // می‌توانید لیست تعطیلات رسمی را هم اضافه کنید

        return true;
    }

    // محاسبه معاش روزانه پیش فرض
    private function calculateDefaultDailySalary()
    {
        if ($this->staffDetails && $this->staffDetails->salary_amount > 0) {
            // تقسیم بر 22 روز کاری در ماه
            return $this->staffDetails->salary_amount / 22;
        }

        return 0;
    }

    // متد کمکی برای محاسبه معاش روزانه
    private function calculateDailySalaryForAttendance($attendance)
    {
        // اگر کارمند جزئیات دارد، معاش ماهانه را بگیر و تقسیم بر 22 کن
        if ($this->staffDetails && $this->staffDetails->monthly_salary) {
            return $this->staffDetails->monthly_salary / 22;
        }

        // در غیر این صورت معاش پیش فرض
        return 500; // مبلغ پیش فرض
    }



    public function loadSalaryHistory()
    {
        $this->salaryHistory = Salaries::where('staff_id', $this->selectedStaffId)
            ->with(['customer', 'admin', 'user'])
            ->orderBy('paid_date', 'desc')
            ->get();
    }

    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
        if ($method === 'نقدی') {
            $this->selectedCustomerId = null;
            $this->selectedCustomer = null;
        } else {
            // اگر کارتی انتخاب شد و کارمند customer_id دارد، آن را تنظیم کن
            if ($this->staffDetails && $this->staffDetails->customer_id) {
                $this->selectedCustomerId = $this->staffDetails->customer_id;
                $this->selectCustomer($this->selectedCustomerId);
            }
        }
    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $customer = Customer::find($customerId);

        if ($customer) {
            $this->selectedCustomer = $customer;
            $this->selectedAccount = $customerId;
            $this->calculateCustomerBalances($customerId);
        }
    }

    public function calculateCustomerBalances($customerId)
    {
        // محاسبه موجودی‌های مشتری
        $transactions = Transaction::where('customer_id', $customerId)->get();

        $cashBalances = [];
        $bankBalances = [];
        $totalBalances = [];

        foreach ($this->currencies as $currency) {
            $code = $currency['code'];
            $name_fa = $currency['name_fa'];

            // موجودی نقدی
            $cashBalance = $transactions->where('currency', $code)
                ->where('account_type', 'نقدی')
                ->sum(function ($transaction) {
                    return $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;
                });

            // موجودی بانکی
            $bankBalance = $transactions->where('currency', $code)
                ->where('account_type', 'بانکی')
                ->sum(function ($transaction) {
                    return $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;
                });

            $cashBalances[$name_fa] = max($cashBalance, 0);
            $bankBalances[$name_fa] = max($bankBalance, 0);
            $totalBalances[$name_fa] = max($cashBalance + $bankBalance, 0);
        }

        $this->customerCashBalances = $cashBalances;
        $this->customerBankBalances = $bankBalances;
        $this->customerTotalBalances = $totalBalances;
    }

    public function paySalary()
    {
        // اعتبارسنجی دستی
        if (!$this->selectedStaffId) {
            session()->flash('error', 'لطفاً کارمند را انتخاب کنید.');
            return;
        }

        if ($this->dueAmount <= 0) {
            session()->flash('error', 'مبلغ قابل پرداخت معتبر نیست.');
            return;
        }

        if ($this->paymentMethod === 'کارتی' && !$this->selectedCustomerId) {
            session()->flash('error', 'برای پرداخت کارتی، مشتری را انتخاب کنید.');
            return;
        }

        DB::beginTransaction();

        try {
            $adminId = Auth::guard('sarafi')->user()->admin_id ?? Auth::guard('sarafi')->id();
            $userId = Auth::guard('sarafi')->id();

            // تبدیل تاریخ شمسی به میلادی
            try {
                $dateParts = explode('/', $this->date);
                if (count($dateParts) !== 3) {
                    throw new \Exception('فرمت تاریخ نامعتبر است.');
                }
                $persianYear = (int) $dateParts[0];
                $persianMonth = (int) $dateParts[1];
                $persianDay = (int) $dateParts[2];

                $jalalian = new Jalalian($persianYear, $persianMonth, $persianDay);
                $paidDate = $jalalian->toCarbon();
            } catch (\Exception $e) {
                $paidDate = Carbon::now();
            }

            // پرداخت نقدی
            if ($this->paymentMethod === 'نقدی') {
                // کسر از صندوق
                $safe = CurrencySafe::where('user_id', $userId)->first();

                if (!$safe) {
                    // اگر صندوق وجود نداشت، ایجاد می‌کنیم
                    $safe = CurrencySafe::create([
                        'user_id' => $userId,
                        'admin_id' => $adminId,
                        'afn' => 0,
                        'usd' => 0,
                        'eur' => 0,
                        'irr' => 0,
                        'aed' => 0,
                        'try' => 0,
                        'cny' => 0,
                        'pkr' => 0,
                        'gbp' => 0,
                        'jpy' => 0,
                        'sar' => 0,
                        'inr' => 0,
                    ]);
                }

                // بررسی موجودی صندوق
                $currencyField = strtolower($this->currency);
                if ($safe->{$currencyField} < $this->dueAmount) {
                    throw new \Exception('موجودی صندوق کافی نیست.');
                }

                // کسر از صندوق
                $safe->{$currencyField} -= $this->dueAmount;
                $safe->save();

                // ثبت پرداخت حقوق
                Salaries::create([
                    'user_id' => $userId,
                    'admin_id' => $adminId,
                    'staff_id' => $this->selectedStaffId,
                    'customer_id' => null,
                    'amount' => $this->dueAmount,
                    'currency' => $this->currency,
                    'payment_method' => 'نقدی',
                    'paid_date' => $paidDate,
                    'description' => $this->description ?: 'پرداخت حقوق بر اساس حضور و غیاب',
                ]);

                session()->flash('message', 'پرداخت نقدی با موفقیت انجام شد و از صندوق کسر شد.');
            }
            // پرداخت کارتی
            else {
                // ثبت تراکنش از مشتری
                Transaction::create([
                    'user_id' => $userId,
                    'admin_id' => $adminId,
                    'customer_id' => $this->selectedCustomerId,
                    'amount' => $this->dueAmount,
                    'currency' => $this->currency,
                    'type' => 'رسید',
                    'account_type' => 'نقدی',
                    'description' => $this->description ?: 'پرداخت حقوق کارمند ' . $this->staffDetails->name,
                    'zone' => Auth::guard('sarafi')->user()->zone ?? 'کابل',
                    'date' => $paidDate,
                ]);

                // ثبت پرداخت حقوق
                Salaries::create([
                    'user_id' => $userId,
                    'admin_id' => $adminId,
                    'staff_id' => $this->selectedStaffId,
                    'customer_id' => $this->selectedCustomerId,
                    'amount' => $this->dueAmount,
                    'currency' => $this->currency,
                    'payment_method' => 'کارتی',
                    'paid_date' => $paidDate,
                    'description' => $this->description ?: 'پرداخت حقوق بر اساس حضور و غیاب',
                ]);

                session()->flash('message', 'پرداخت کارتی با موفقیت انجام شد و از حساب مشتری کسر شد.');
            }

            DB::commit();

            // بازخوانی اطلاعات
            $this->staffDetails = Staffs::find($this->selectedStaffId);
            $this->calculateDueSalary();
            $this->loadSalaryHistory();
            $this->description = '';

            // اگر مشتری انتخاب شده بود، موجودی‌ها را به روز کنیم
            if ($this->selectedCustomerId) {
                $this->calculateCustomerBalances($this->selectedCustomerId);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'خطا در پرداخت: ' . $e->getMessage());
        }
    }

    public function formatAmount()
    {
        if ($this->amount) {
            $this->amount = number_format(str_replace(',', '', $this->amount));
        }
    }

    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->selectedCustomer = null;
        $this->selectedAccount = null;
        $this->customerCashBalances = [];
        $this->customerBankBalances = [];
        $this->customerTotalBalances = [];
    }

    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedCustomerId = null;
        $this->selectedCustomer = null;
        $this->selectedAccount = null;
    }

    public function render()
    {
        $filteredCustomers = $this->search
            ? Customer::where('fullname', 'like', '%' . $this->search . '%')
            ->orWhere('account_number', 'like', '%' . $this->search . '%')
            ->limit(10)
            ->get()
            : collect([]);

        // اگر مشتری انتخاب شده، موجودی‌ها را محاسبه کن
        if ($this->selectedCustomerId && empty($this->customerCashBalances)) {
            $this->calculateCustomerBalances($this->selectedCustomerId);
        }

        return view('livewire.sarafi.salary', [
            'filteredCustomers' => $filteredCustomers,
            'staffs' => $this->staffs,
        ]);
    }
}
