<?php

namespace App\Livewire\Market;

use App\Models\Market\Customer;
use App\Models\Market\WithdrawLog;
use App\Models\Market\Outside;
use App\Models\Market\CustomerConversion; // ★ اضافه شد
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerProfile extends Component
{
    use WithPagination;

    // ==================== فیلترها ====================
    public $search = '';
    public $filterCustomerId = '';
    public $filterCurrency = '';
    public $filterTransactionType = 'all';
    public $perPage = 10;

    // تاریخ‌های شمسی (Y/m/d)
    public $startDate = '';
    public $endDate = '';

    // ==================== داده‌های گزارش ====================
    public $reports = [];
    public $customers = [];
    public $currencies = [];

    // ==================== لیسنرها ====================
    protected $listeners = ['refreshReport' => 'generateReport'];

    // ==================== متدهای اولیه ====================
    public function mount()
    {
        $this->currencies = $this->getCurrencies();
        $this->customers = Customer::where('admin_id', $this->getAdminId())
            ->orderBy('fullname')
            ->get(['id', 'fullname', 'rent_money', 'balance_afn', 'balance_usd', 'balance_eur', 'balance_irr'])
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
        $currencies = ['AFN', 'USD', 'EUR', 'IRR'];
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

    // ==================== تولید گزارش خلاصه (موجودی‌ها) ====================
    public function generateReport()
    {
        $adminId = $this->getAdminId();
        $customerQuery = Customer::where('admin_id', $adminId);

        if (!empty($this->search)) {
            $customerQuery->where('fullname', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->filterCustomerId)) {
            $customerQuery->where('id', $this->filterCustomerId);
        }

        $customers = $customerQuery->get();
        $reports = [];

        foreach ($customers as $customer) {
            $reports[] = [
                'id' => $customer->id,
                'fullname' => $customer->fullname,
                'balance_afn' => (float) $customer->balance_afn,
                'balance_usd' => (float) $customer->balance_usd,
                'balance_eur' => (float) $customer->balance_eur,
                'balance_irr' => (float) $customer->balance_irr,
                'rent_money' => (float) ($customer->rent_money ?? 0),
                'total_balance' => (float) ($customer->balance_afn + $customer->balance_usd + $customer->balance_eur + $customer->balance_irr + ($customer->rent_money ?? 0))
            ];
        }

        $this->reports = $reports;
    }

    // ==================== دریافت تراکنش‌های برداشت و بیرونی (با پیجینیشن) ====================
    public function getTransactionsPaginated()
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
        $withdrawQuery = WithdrawLog::with('customer')
            ->where('admin_id', $adminId)
            ->whereNotNull('customer_id');

        if (!empty($this->search)) {
            $withdrawQuery->whereHas('customer', function ($q) {
                $q->where('fullname', 'like', '%' . $this->search . '%');
            });
        }
        if (!empty($this->filterCustomerId)) {
            $withdrawQuery->where('customer_id', $this->filterCustomerId);
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

        if ($this->filterTransactionType === 'outside') {
            $withdrawals = collect();
        } else {
            $withdrawals = $withdrawQuery->get()->map(function ($item) {
                return [
                    'type' => $item->expanses_type ?? 'برداشت',
                    'customer_name' => $item->customer ? $item->customer->fullname : 'نامشخص',
                    'amount' => $item->amount,
                    'currency' => $item->currency,
                    'description' => $item->description ?? '-',
                    'created_at' => $item->created_at,
                    'date_fa' => Jalalian::fromCarbon($item->created_at)->format('Y/m/d H:i'),
                    'transaction_type' => 'withdrawal',
                ];
            });
        }

        // ====== پرداخت‌های بیرونی ======
        $outsideQuery = Outside::with('customer')
            ->where('admin_id', $adminId)
            ->whereNotNull('customer_id');

        if (!empty($this->search)) {
            $outsideQuery->whereHas('customer', function ($q) {
                $q->where('fullname', 'like', '%' . $this->search . '%');
            });
        }
        if (!empty($this->filterCustomerId)) {
            $outsideQuery->where('customer_id', $this->filterCustomerId);
        }
        if ($startDateCarbon) {
            $outsideQuery->where('created_at', '>=', $startDateCarbon);
        }
        if ($endDateCarbon) {
            $outsideQuery->where('created_at', '<=', $endDateCarbon);
        }
        if (!empty($this->filterCurrency)) {
            $outsideQuery->where('currency', $this->filterCurrency);
        }

        if ($this->filterTransactionType === 'withdrawal') {
            $outsides = collect();
        } else {
            $outsides = $outsideQuery->get()->map(function ($item) {
                return [
                    'type' => $item->type ?? 'پرداخت بیرونی',
                    'customer_name' => $item->customer ? $item->customer->fullname : 'نامشخص',
                    'amount' => $item->paid,
                    'currency' => $item->currency,
                    'description' => $item->description ?? '-',
                    'created_at' => $item->created_at,
                    'date_fa' => Jalalian::fromCarbon($item->created_at)->format('Y/m/d H:i'),
                    'transaction_type' => 'outside',
                ];
            });
        }

        $all = $withdrawals->concat($outsides);
        $all = $all->sortByDesc('created_at')->values();

        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = $this->perPage === 'all' ? $all->count() : (int) $this->perPage;
        $items = $all->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $all->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    // ★★★★★ متد جدید: دریافت تبدیل‌های ارز (با پیجینیشن) ★★★★★
    public function getConversionsPaginated()
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

        $query = CustomerConversion::with('customer')
            ->where('admin_id', $adminId);

        // جستجو بر اساس نام مشتری
        if (!empty($this->search)) {
            $query->whereHas('customer', function ($q) {
                $q->where('fullname', 'like', '%' . $this->search . '%');
            });
        }

        // فیلتر مشتری
        if (!empty($this->filterCustomerId)) {
            $query->where('customer_id', $this->filterCustomerId);
        }

        // فیلتر تاریخ (با transaction_date)
        if ($startDateCarbon) {
            $query->where('transaction_date', '>=', $startDateCarbon->format('Y/m/d'));
        }
        if ($endDateCarbon) {
            $query->where('transaction_date', '<=', $endDateCarbon->format('Y/m/d'));
        }

        // فیلتر ارز (از ارز یا به ارز)
        if (!empty($this->filterCurrency)) {
            $query->where(function ($q) {
                $q->where('from_currency', $this->filterCurrency)
                  ->orWhere('to_currency', $this->filterCurrency);
            });
        }

        // مرتب‌سازی بر اساس تاریخ ثبت (یا transaction_date)
        $query->orderBy('transaction_date', 'desc')->orderBy('created_at', 'desc');

        // پیجینیشن
        $perPage = $this->perPage === 'all' ? $query->count() : (int) $this->perPage;
        return $query->paginate($perPage);
    }

    // ★★★★★ متد دریافت همه تبدیل‌ها (برای PDF) ★★★★★
    public function getAllConversions()
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

        $query = CustomerConversion::with('customer')
            ->where('admin_id', $adminId);

        if (!empty($this->search)) {
            $query->whereHas('customer', function ($q) {
                $q->where('fullname', 'like', '%' . $this->search . '%');
            });
        }
        if (!empty($this->filterCustomerId)) {
            $query->where('customer_id', $this->filterCustomerId);
        }
        if ($startDateCarbon) {
            $query->where('transaction_date', '>=', $startDateCarbon->format('Y/m/d'));
        }
        if ($endDateCarbon) {
            $query->where('transaction_date', '<=', $endDateCarbon->format('Y/m/d'));
        }
        if (!empty($this->filterCurrency)) {
            $query->where(function ($q) {
                $q->where('from_currency', $this->filterCurrency)
                  ->orWhere('to_currency', $this->filterCurrency);
            });
        }

        return $query->orderBy('transaction_date', 'desc')->get();
    }

    // ==================== به‌روزرسانی خودکار ====================
    public function updatedSearch()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedFilterCustomerId()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedFilterCurrency()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedFilterTransactionType()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // ==================== دکمه‌های کنترلی ====================
    public function resetFilters()
    {
        $this->search = '';
        $this->filterCustomerId = '';
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

    // ==================== چاپ PDF ====================
    public function printReport()
    {
        try {
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 300);

            $reports = $this->reports;
            $currencies = $this->currencies;
            $transactions = $this->getAllTransactions();
            $conversions = $this->getAllConversions(); // ★ اضافه شد

            $filterInfo = [
                'customer' => $this->filterCustomerId ? Customer::find($this->filterCustomerId)->fullname ?? 'همه' : 'همه',
                'currency' => $this->filterCurrency ? ($this->currencies[$this->filterCurrency] ?? $this->filterCurrency) : 'همه',
                'type' => $this->filterTransactionType === 'all' ? 'همه' : ($this->filterTransactionType === 'withdrawal' ? 'برداشت‌ها' : 'پرداخت‌های بیرونی'),
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

            $html = view('print.customer-profile-pdf', compact(
                'reports',
                'currencies',
                'transactions',
                'conversions', // ★ اضافه شد
                'filterInfo'
            ))->render();

            $mpdf->WriteHTML($html);

            $fileName = 'گزارش_مشتریان_' . Jalalian::now()->format('Y_m_d_H_i') . '.pdf';
            $path = storage_path('app/public/' . $fileName);
            $mpdf->Output($path, 'F');

            return redirect()->to(asset('storage/' . $fileName));
        } catch (\Exception $e) {
            Log::error('PDF generation error: ' . $e->getMessage());
            session()->flash('error', 'خطا در ایجاد PDF: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    // ==================== دریافت همه تراکنش‌ها (برای PDF) ====================
    public function getAllTransactions()
    {
        // همان کد قبلی (بدون تغییر)...
        // (برای اختصار حذف شده، ولی در فایل اصلی همان است)
    }

    // ==================== رندر ====================
    public function render()
    {
        return view('livewire.market.customer-profile', [
            'customers' => $this->customers,
            'currencies' => $this->currencies,
            'reports' => $this->reports,
            'transactions' => $this->getTransactionsPaginated(),
            'conversions' => $this->getConversionsPaginated(), // ★ اضافه شد
        ]);
    }
}