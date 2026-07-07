<?php

namespace App\Livewire\Market;

use App\Models\Market\Staff;
use App\Models\Market\WithdrawLog;
use App\Models\Market\Salary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use Illuminate\Pagination\LengthAwarePaginator;

class StaffProfile extends Component
{
    use WithPagination;

    // ==================== فیلترها ====================
    public $search = '';
    public $filterStaffId = '';
    public $filterCurrency = '';
    public $filterTransactionType = 'all'; // all, withdrawal, salary

    // تاریخ‌های شمسی (Y/m/d)
    public $startDate = '';
    public $endDate = '';

    // ==================== داده‌های گزارش ====================
    public $reports = [];
    public $staffs = [];
    public $currencies = [];
    public $sourceCurrency = 'AFN';
    public $totalWithdrawals = [];
    public $totalSalaries = [];

    // ==================== لیسنرها ====================
    protected $listeners = ['refreshReport' => 'generateReport', 'print-pdf' => 'printReport'];

    // ==================== متدهای اولیه ====================
    public function mount()
    {
        $this->currencies = $this->getCurrencies();
        $this->staffs = Staff::where('admin_id', $this->getAdminId())
            ->orderBy('fullname')
            ->get(['id', 'fullname'])
            ->toArray();
        $this->generateReport();
    }

    private function getAdminId()
    {
        $user = Auth::guard('market')->user();
        return $user->role === 'admin' ? $user->id : $user->admin_id;
    }

    private function getCurrencies()
    {
        $currencies = WithdrawLog::where('admin_id', $this->getAdminId())
            ->distinct()
            ->pluck('currency')
            ->merge(
                Salary::where('admin_id', $this->getAdminId())
                    ->distinct()
                    ->pluck('currency')
            )
            ->unique()
            ->values()
            ->toArray();

        if (empty($currencies)) {
            $currencies = ['AFN', 'USD', 'EUR', 'IRR'];
        }

        $map = [
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            'IRR' => 'تومان',
            'PKR' => 'کلدار',
            'AED' => 'درهم',
            'TRY' => 'لیره',
            'CNY' => 'یوان',
            'GBP' => 'پوند',
            'JPY' => 'ین',
            'SAR' => 'ریال سعودی',
            'INR' => 'روپیه',
        ];

        $result = [];
        foreach ($currencies as $code) {
            $result[$code] = $map[$code] ?? $code;
        }
        return $result;
    }

    // ==================== تولید گزارش خلاصه (اصلاح‌شده) ====================
    public function generateReport()
    {
        $adminId = $this->getAdminId();
        $staffQuery = Staff::where('admin_id', $adminId);

        // فیلتر جستجو
        if (!empty($this->search)) {
            $staffQuery->where('fullname', 'like', '%' . $this->search . '%');
        }

        // فیلتر کارمند
        if (!empty($this->filterStaffId)) {
            $staffQuery->where('id', $this->filterStaffId);
        }

        $staffs = $staffQuery->get();

        $reports = [];
        $totalWithdrawals = [];
        $totalSalaries = [];

        // تبدیل تاریخ‌های شمسی به میلادی (با اصلاح فرمت)
        $startDateCarbon = null;
        $endDateCarbon = null;
        if (!empty($this->startDate)) {
            try {
                $dateString = str_replace('-', '/', $this->startDate);
                $startDateCarbon = Jalalian::fromFormat('Y/m/d', $dateString)->toCarbon()->startOfDay();
            } catch (\Exception $e) {
                Log::warning('Invalid startDate format: ' . $this->startDate);
            }
        }
        if (!empty($this->endDate)) {
            try {
                $dateString = str_replace('-', '/', $this->endDate);
                $endDateCarbon = Jalalian::fromFormat('Y/m/d', $dateString)->toCarbon()->endOfDay();
            } catch (\Exception $e) {
                Log::warning('Invalid endDate format: ' . $this->endDate);
            }
        }

        foreach ($staffs as $staff) {
            // ===== برداشت‌ها =====
            $withdrawQuery = WithdrawLog::where('admin_id', $adminId)
                ->where('staff_id', $staff->id);

            if ($startDateCarbon) {
                $withdrawQuery->where('created_at', '>=', $startDateCarbon);
            }
            if ($endDateCarbon) {
                $withdrawQuery->where('created_at', '<=', $endDateCarbon);
            }
            if (!empty($this->filterCurrency)) {
                $withdrawQuery->where('currency', $this->filterCurrency);
            }
            $withdrawals = $withdrawQuery->get();

            // ===== حقوق‌ها =====
            $salaryQuery = Salary::where('admin_id', $adminId)
                ->where('staff_id', $staff->id);

            if ($startDateCarbon) {
                $salaryQuery->where('created_at', '>=', $startDateCarbon);
            }
            if ($endDateCarbon) {
                $salaryQuery->where('created_at', '<=', $endDateCarbon);
            }
            if (!empty($this->filterCurrency)) {
                $salaryQuery->where('currency', $this->filterCurrency);
            }
            $salaries = $salaryQuery->get();

            // ===== جمع‌آوری بر اساس ارز =====
            $withdrawByCurrency = [];
            $salaryByCurrency = [];

            foreach ($withdrawals as $w) {
                $currency = $w->currency;
                if (!isset($withdrawByCurrency[$currency])) {
                    $withdrawByCurrency[$currency] = 0;
                }
                $withdrawByCurrency[$currency] += $w->amount;
            }

            foreach ($salaries as $s) {
                $currency = $s->currency;
                if (!isset($salaryByCurrency[$currency])) {
                    $salaryByCurrency[$currency] = 0;
                }
                $salaryByCurrency[$currency] += $s->salary;
            }

            $reports[] = [
                'id' => $staff->id,
                'fullname' => $staff->fullname,
                'withdrawals' => $withdrawByCurrency,
                'salaries' => $salaryByCurrency,
            ];

            // جمع کل
            foreach ($withdrawByCurrency as $currency => $amount) {
                if (!isset($totalWithdrawals[$currency])) {
                    $totalWithdrawals[$currency] = 0;
                }
                $totalWithdrawals[$currency] += $amount;
            }

            foreach ($salaryByCurrency as $currency => $amount) {
                if (!isset($totalSalaries[$currency])) {
                    $totalSalaries[$currency] = 0;
                }
                $totalSalaries[$currency] += $amount;
            }
        }

        $this->reports = $reports;
        $this->totalWithdrawals = $totalWithdrawals;
        $this->totalSalaries = $totalSalaries;
    }

    // ==================== دریافت تراکنش‌ها با صفحه‌بندی (اصلاح‌شده) ====================
    public function getTransactionsPaginated()
    {
        $adminId = $this->getAdminId();

        // تبدیل تاریخ‌های شمسی به میلادی با اصلاح فرمت
        $startDateCarbon = null;
        $endDateCarbon = null;
        if (!empty($this->startDate)) {
            try {
                $dateString = str_replace('-', '/', $this->startDate);
                $startDateCarbon = Jalalian::fromFormat('Y/m/d', $dateString)->toCarbon()->startOfDay();
            } catch (\Exception $e) {}
        }
        if (!empty($this->endDate)) {
            try {
                $dateString = str_replace('-', '/', $this->endDate);
                $endDateCarbon = Jalalian::fromFormat('Y/m/d', $dateString)->toCarbon()->endOfDay();
            } catch (\Exception $e) {}
        }

        // ====== برداشت‌ها ======
        $withdrawQuery = WithdrawLog::with('staff')
            ->where('admin_id', $adminId)
            ->whereNotNull('staff_id');

        if (!empty($this->filterStaffId)) {
            $withdrawQuery->where('staff_id', $this->filterStaffId);
        }

        if ($startDateCarbon) {
            $withdrawQuery->where('created_at', '>=', $startDateCarbon);
        }
        if ($endDateCarbon) {
            $withdrawQuery->where('created_at', '<=', $endDateCarbon);
        }
        if (!empty($this->filterCurrency)) {
            $withdrawQuery->where('currency', $this->filterCurrency);
        }

        if ($this->filterTransactionType === 'salary') {
            $withdrawals = collect();
        } else {
            $withdrawals = $withdrawQuery->get()->map(function ($item) {
                return [
                    'type' => $item->expanses_type ?? 'برداشت',
                    'staff_name' => $item->staff ? $item->staff->fullname : 'نامشخص',
                    'amount' => $item->amount,
                    'currency' => $item->currency,
                    'description' => $item->description ?? '-',
                    'created_at' => $item->created_at,
                    'date_fa' => Jalalian::fromCarbon($item->created_at)->format('Y/m/d H:i'),
                    'transaction_type' => 'withdrawal',
                ];
            });
        }

        // ====== حقوق‌ها ======
        $salaryQuery = Salary::with('staff')
            ->where('admin_id', $adminId)
            ->whereNotNull('staff_id');

        if (!empty($this->filterStaffId)) {
            $salaryQuery->where('staff_id', $this->filterStaffId);
        }

        if ($startDateCarbon) {
            $salaryQuery->where('created_at', '>=', $startDateCarbon);
        }
        if ($endDateCarbon) {
            $salaryQuery->where('created_at', '<=', $endDateCarbon);
        }
        if (!empty($this->filterCurrency)) {
            $salaryQuery->where('currency', $this->filterCurrency);
        }

        if ($this->filterTransactionType === 'withdrawal') {
            $salaries = collect();
        } else {
            $salaries = $salaryQuery->get()->map(function ($item) {
                return [
                    'type' => $item->reduce_from ?? 'حقوق',
                    'staff_name' => $item->staff ? $item->staff->fullname : 'نامشخص',
                    'amount' => $item->salary,
                    'currency' => $item->currency,
                    'description' => $item->description ?? '-',
                    'created_at' => $item->created_at,
                    'date_fa' => Jalalian::fromCarbon($item->created_at)->format('Y/m/d H:i'),
                    'transaction_type' => 'salary',
                ];
            });
        }

        $all = $withdrawals->concat($salaries);
        $all = $all->sortByDesc('created_at')->values();

        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;
        $items = $all->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $all->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    // ==================== دریافت همه تراکنش‌ها (برای چاپ PDF) اصلاح‌شده ====================
    public function getAllTransactions()
    {
        $adminId = $this->getAdminId();

        $startDateCarbon = null;
        $endDateCarbon = null;
        if (!empty($this->startDate)) {
            try {
                $dateString = str_replace('-', '/', $this->startDate);
                $startDateCarbon = Jalalian::fromFormat('Y/m/d', $dateString)->toCarbon()->startOfDay();
            } catch (\Exception $e) {}
        }
        if (!empty($this->endDate)) {
            try {
                $dateString = str_replace('-', '/', $this->endDate);
                $endDateCarbon = Jalalian::fromFormat('Y/m/d', $dateString)->toCarbon()->endOfDay();
            } catch (\Exception $e) {}
        }

        // ====== برداشت‌ها ======
        $withdrawQuery = WithdrawLog::with('staff')
            ->where('admin_id', $adminId)
            ->whereNotNull('staff_id');

        if (!empty($this->filterStaffId)) {
            $withdrawQuery->where('staff_id', $this->filterStaffId);
        }

        if ($startDateCarbon) {
            $withdrawQuery->where('created_at', '>=', $startDateCarbon);
        }
        if ($endDateCarbon) {
            $withdrawQuery->where('created_at', '<=', $endDateCarbon);
        }
        if (!empty($this->filterCurrency)) {
            $withdrawQuery->where('currency', $this->filterCurrency);
        }

        if ($this->filterTransactionType === 'salary') {
            $withdrawals = collect();
        } else {
            $withdrawals = $withdrawQuery->get()->map(function ($item) {
                return [
                    'type' => $item->expanses_type ?? 'برداشت',
                    'staff_name' => $item->staff ? $item->staff->fullname : 'نامشخص',
                    'amount' => $item->amount,
                    'currency' => $item->currency,
                    'description' => $item->description ?? '-',
                    'created_at' => $item->created_at,
                    'date_fa' => Jalalian::fromCarbon($item->created_at)->format('Y/m/d H:i'),
                    'transaction_type' => 'withdrawal',
                ];
            });
        }

        // ====== حقوق‌ها ======
        $salaryQuery = Salary::with('staff')
            ->where('admin_id', $adminId)
            ->whereNotNull('staff_id');

        if (!empty($this->filterStaffId)) {
            $salaryQuery->where('staff_id', $this->filterStaffId);
        }

        if ($startDateCarbon) {
            $salaryQuery->where('created_at', '>=', $startDateCarbon);
        }
        if ($endDateCarbon) {
            $salaryQuery->where('created_at', '<=', $endDateCarbon);
        }
        if (!empty($this->filterCurrency)) {
            $salaryQuery->where('currency', $this->filterCurrency);
        }

        if ($this->filterTransactionType === 'withdrawal') {
            $salaries = collect();
        } else {
            $salaries = $salaryQuery->get()->map(function ($item) {
                return [
                    'type' => $item->reduce_from ?? 'حقوق',
                    'staff_name' => $item->staff ? $item->staff->fullname : 'نامشخص',
                    'amount' => $item->salary,
                    'currency' => $item->currency,
                    'description' => $item->description ?? '-',
                    'created_at' => $item->created_at,
                    'date_fa' => Jalalian::fromCarbon($item->created_at)->format('Y/m/d H:i'),
                    'transaction_type' => 'salary',
                ];
            });
        }

        $all = $withdrawals->concat($salaries);
        return $all->sortByDesc('created_at')->values();
    }

    // ==================== دکمه‌های کنترلی ====================
    public function resetFilters()
    {
        $this->search = '';
        $this->filterStaffId = '';
        $this->filterCurrency = '';
        $this->filterTransactionType = 'all';
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();
        $this->generateReport();
        session()->flash('message', 'فیلترها بازنشانی شدند.');
    }

    public function refreshReport()
    {
        $this->resetPage();
        $this->generateReport();
        session()->flash('message', 'گزارش به‌روزرسانی شد.');
    }

    // ==================== پرینت PDF ====================
    public function printReport()
    {
        try {
            $reports = $this->reports;
            $currencies = $this->currencies;
            $totalWithdrawals = $this->totalWithdrawals;
            $totalSalaries = $this->totalSalaries;
            $transactions = $this->getAllTransactions();

            $filterInfo = [
                'staff' => $this->filterStaffId ? Staff::find($this->filterStaffId)->fullname ?? 'همه' : 'همه',
                'currency' => $this->filterCurrency ? ($this->currencies[$this->filterCurrency] ?? $this->filterCurrency) : 'همه',
                'type' => $this->filterTransactionType === 'all' ? 'همه' : ($this->filterTransactionType === 'withdrawal' ? 'برداشت‌ها' : 'معاش‌ها'),
                'startDate' => $this->startDate ?: 'نامحدود',
                'endDate' => $this->endDate ?: 'نامحدود',
            ];

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'directionality' => 'rtl',
                'margin_top' => 15,
                'margin_bottom' => 15,
                'margin_left' => 10,
                'margin_right' => 10,
                'fontDir' => array_merge(
                    (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                    [public_path('fonts/vazir/')]
                ),
                'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                    'vazir' => [
                        'R' => 'Vazir-Light.ttf',
                        'B' => 'Vazir-Bold.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ],
                ],
                'default_font' => 'vazir',
                'tempDir' => storage_path('app/mpdf'),
            ]);

            $mpdf->SetAutoPageBreak(true, 15);

            $html = view('print.staff-profile-pdf', compact(
                'reports',
                'currencies',
                'totalWithdrawals',
                'totalSalaries',
                'transactions',
                'filterInfo'
            ))->render();

            $mpdf->WriteHTML($html);

            $fileName = 'گزارش_کارمندان_' . Jalalian::now()->format('Y_m_d_H_i') . '.pdf';
            $path = storage_path('app/public/' . $fileName);
            $mpdf->Output($path, 'F');

            $this->dispatch('print-pdf', url: asset('storage/' . $fileName));
        } catch (\Exception $e) {
            Log::error('PDF generation error for staff profile: ' . $e->getMessage());
            session()->flash('error', 'خطا در ایجاد PDF: ' . $e->getMessage());
        }
    }

    // ==================== رندر ====================
    public function render()
    {
        return view('livewire.market.staff-profile', [
            'staffs' => $this->staffs,
            'currencies' => $this->currencies,
            'reports' => $this->reports,
            'totalWithdrawals' => $this->totalWithdrawals,
            'totalSalaries' => $this->totalSalaries,
            'transactions' => $this->getTransactionsPaginated(),
        ]);
    }
}