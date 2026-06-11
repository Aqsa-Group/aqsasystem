<?php

namespace App\Livewire\Import;

use App\Exports\Import\GeneralReportExport;
use App\Exports\Import\GeneralReportPdfExport;
use App\Models\Import\Buy;
use App\Models\Import\Company;
use App\Models\Import\CompanyPayment;
use App\Models\Import\Customer;
use App\Models\Import\CustomerStory;
use App\Models\Import\Sale;
use App\Models\Import\Staff;
use App\Models\Import\Transaction;
use App\Models\Import\Withdraw;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

class GeneralReports extends Component
{
    use WithPagination;

    public $reportType = 'withdraw_log';
    public $startDate;
    public $endDate;
    public $startDateJalali;
    public $endDateJalali;
    public $customerId;
    public $staffId;
    public $companyId;
    public $currency;
    public $type;
    public $search = '';
    public $amountMin;
    public $amountMax;

    protected $queryString = [
        'reportType'      => ['except' => 'withdraw_log'],
        'startDate'       => ['except' => ''],
        'endDate'         => ['except' => ''],
        'startDateJalali' => ['except' => ''],
        'endDateJalali'   => ['except' => ''],
        'customerId'      => ['except' => ''],
        'staffId'         => ['except' => ''],
        'companyId'       => ['except' => ''],
        'currency'        => ['except' => ''],
        'type'            => ['except' => ''],
        'search'          => ['except' => ''],
        'amountMin'       => ['except' => ''],
        'amountMax'       => ['except' => ''],
    ];

    public function mount()
    {
        $this->setDefaultJalaliDates();
    }

    private function setDefaultJalaliDates()
    {
        $today = Jalalian::now();
        $this->endDateJalali = $today->format('Y/m/d');
        $this->endDate = $today->toCarbon()->format('Y-m-d');

        $oneMonthAgo = $today->subMonths(1);
        $this->startDateJalali = $oneMonthAgo->format('Y/m/d');
        $this->startDate = $oneMonthAgo->toCarbon()->format('Y-m-d');
    }

    public function updatedStartDateJalali($value)
    {
        if ($value) {
            try {
                $date = Jalalian::fromFormat('Y/m/d', $value)->toCarbon();
                $this->startDate = $date->format('Y-m-d');
                $this->clearError('startDateJalali');
            } catch (\Exception $e) {
                $this->addError('startDateJalali', 'تاریخ شروع معتبر نیست');
                $this->startDate = null;
            }
        } else {
            $this->startDate = null;
        }
        $this->resetPage();
    }

    public function updatedEndDateJalali($value)
    {
        if ($value) {
            try {
                $date = Jalalian::fromFormat('Y/m/d', $value)->toCarbon();
                $this->endDate = $date->format('Y-m-d');
                $this->clearError('endDateJalali');
            } catch (\Exception $e) {
                $this->addError('endDateJalali', 'تاریخ پایان معتبر نیست');
                $this->endDate = null;
            }
        } else {
            $this->endDate = null;
        }
        $this->resetPage();
    }

    public function updatedStartDate($value)
    {
        if ($value) {
            try {
                $date = Carbon::parse($value);
                $this->startDateJalali = Jalalian::fromCarbon($date)->format('Y/m/d');
            } catch (\Exception $e) {
                $this->startDateJalali = null;
            }
        } else {
            $this->startDateJalali = null;
        }
        $this->resetPage();
    }

    public function updatedEndDate($value)
    {
        if ($value) {
            try {
                $date = Carbon::parse($value);
                $this->endDateJalali = Jalalian::fromCarbon($date)->format('Y/m/d');
            } catch (\Exception $e) {
                $this->endDateJalali = null;
            }
        } else {
            $this->endDateJalali = null;
        }
        $this->resetPage();
    }

    public function updated($property)
    {
        if ($property !== 'reports' && !str_contains($property, 'DateJalali')) {
            $this->resetPage();
        }
    }

    // =============================================
    // خروجی‌ها
    // =============================================

    public function exportToExcel()
    {
        try {
            $data = $this->getReportData(true);
            return Excel::download(
                new GeneralReportExport($data, $this->reportType),
                'general_report_' . $this->reportType . '_' . now()->format('Y_m_d') . '.xlsx'
            );
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'خطا در تولید فایل Excel: ' . $e->getMessage()
            ]);
        }
    }

    public function exportToPdf()
    {
        try {
            $data = $this->getReportData(true);
            $filters = $this->getFiltersForExport();
            $pdfExport = new GeneralReportPdfExport($data, $this->reportType, $filters);
            return $pdfExport->download();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'خطا در تولید فایل PDF: ' . $e->getMessage()
            ]);
            Log::error('PDF Export Error: ' . $e->getMessage());
        }
    }

    public function previewPdf()
    {
        try {
            $data = $this->getReportData(true);
            $filters = $this->getFiltersForExport();
            $pdfExport = new GeneralReportPdfExport($data, $this->reportType, $filters);
            return response()->streamDownload(function () use ($pdfExport) {
                echo $pdfExport->stream();
            }, 'preview.pdf');
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'خطا در پیش نمایش PDF: ' . $e->getMessage()
            ]);
            Log::error('PDF Preview Error: ' . $e->getMessage());
        }
    }

    public function printReport()
    {
        return $this->previewPdf();
    }

    // =============================================
    // فیلترها
    // =============================================

    private function getFiltersForExport()
    {
        return [
            'startDate'       => $this->startDate,
            'endDate'         => $this->endDate,
            'startDateJalali' => $this->startDateJalali,
            'endDateJalali'   => $this->endDateJalali,
            'customerId'      => $this->customerId,
            'customerName'    => $this->customerId ? Customer::find($this->customerId)->name ?? null : null,
            'staffId'         => $this->staffId,
            'staffName'       => $this->staffId ? Staff::find($this->staffId)->fullname ?? null : null,
            'companyId'       => $this->companyId,
            'companyName'     => $this->companyId ? Company::find($this->companyId)->name ?? null : null,
            'currency'        => $this->currency,
            'type'            => $this->type,
            'search'          => $this->search,
            'amountMin'       => $this->amountMin,
            'amountMax'       => $this->amountMax,
        ];
    }

    // =============================================
    // کوئری‌های گزارش
    // =============================================

    private function getReportData($forExport = false)
    {
        try {
            switch ($this->reportType) {
                case 'withdraw_log':
                    $query = $this->buildWithdrawQuery();
                    break;
                case 'loan':
                    $query = $this->buildLoanQuery();
                    break;
                case 'sell':
                    $query = $this->buildSaleQuery();
                    break;
                case 'buy':
                    $query = $this->buildBuyQuery();
                    break;
                case 'transaction':
                    $query = $this->buildTransactionQuery();
                    break;
                case 'company_payment':
                    $query = $this->buildCompanyPaymentQuery();
                    break;
                default:
                    $query = $this->buildWithdrawQuery();
            }

            return $forExport ? $query->get() : $query->paginate(20);
        } catch (\Exception $e) {
            Log::error('Error in getReportData: ' . $e->getMessage(), [
                'reportType' => $this->reportType
            ]);

            if ($forExport) {
                return collect();
            } else {
                return new \Illuminate\Pagination\LengthAwarePaginator(
                    [],
                    0,
                    20,
                    \Illuminate\Pagination\Paginator::resolveCurrentPage()
                );
            }
        }
    }

    private function buildWithdrawQuery()
    {
        return Withdraw::with(['staff', 'user'])
            ->when($this->staffId, fn($q) => $q->where('staff_id', $this->staffId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('staff', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"))
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildLoanQuery()
    {
        return CustomerStory::with(['customer', 'user'])
            ->when($this->customerId, fn($q) => $q->where('customer_id', $this->customerId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->type, function ($q) {
                if ($this->type === 'برد') {
                    $q->where('type', 'برد');
                } elseif ($this->type === 'رسید') {
                    $q->where('type', 'رسید');
                }
            })
            ->when($this->startDate, fn($q) => $q->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $search = "%{$this->search}%";
                $q->where(function ($q2) use ($search) {
                    $q2->whereHas('customer', function ($c) use ($search) {
                        $c->where('name', 'like', $search);
                    })
                    ->orWhere('description', 'like', $search);
                });
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildSaleQuery()
    {
        return Sale::with(['customer', 'user', 'items'])
            ->when($this->customerId, fn($q) => $q->where('customer_id', $this->customerId))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('total_price', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('total_price', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"))
                    ->orWhere('invoice_number', 'like', "%{$this->search}%")
                    ->orWhere('sale_type', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildBuyQuery()
    {
        return Buy::with(['company', 'user'])
            ->when($this->companyId, fn($q) => $q->where('company_id', $this->companyId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('import_date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('import_date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('total_price', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('total_price', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('company', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"))
                    ->orWhere('name', 'like', "%{$this->search}%")
                    ->orWhere('barcode', 'like', "%{$this->search}%")
                    ->orWhere('brand', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildTransactionQuery()
    {
        return Transaction::with(['customer', 'staff', 'sarafi', 'user'])
            ->when($this->customerId, fn($q) => $q->where('customer_id', $this->customerId))
            ->when($this->staffId, fn($q) => $q->where('staff_id', $this->staffId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->startDate, function ($q) {
                $gregorianDate = Jalalian::fromFormat('Y/m/d', $this->startDate)->toCarbon();
                $q->whereDate('created_at', '>=', $gregorianDate);
            })
            ->when($this->endDate, function ($q) {
                $gregorianDate = Jalalian::fromFormat('Y/m/d', $this->endDate)->toCarbon();
                $q->whereDate('created_at', '<=', $gregorianDate);
            })
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->whereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('staff', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                        ->orWhereHas('sarafi', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildCompanyPaymentQuery()
    {
        return CompanyPayment::with(['company'])
            ->when($this->companyId, fn($q) => $q->where('company_id', $this->companyId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('total_debt', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('total_debt', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('company', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"));
            })
            ->orderBy('created_at', 'desc');
    }

    // =============================================
    // محاسبات گزارش
    // =============================================

    public function getReportsProperty()
    {
        return $this->getReportData();
    }

    public function getCustomersProperty()
    {
        return Customer::pluck('name', 'id');
    }

    public function getStaffsProperty()
    {
        return Staff::pluck('name', 'id');
    }

    public function getCompaniesProperty()
    {
        return Company::pluck('name', 'id');
    }

    // =============================================
    // خلاصه گزارش (Summary)
    // =============================================

    public function getSummaryProperty()
    {
        $data = $this->getReportData(true);

        $totalCount = $data->count();
        $currencyTotals = [];

        foreach ($data as $item) {
            $currency = $item->currency ?? 'نامشخص';
            $amount = match ($this->reportType) {
                'withdraw_log'   => $item->amount ?? 0,
                'loan'           => $item->amount ?? 0,
                'sell'           => $item->total_price ?? 0,
                'buy'            => $item->total_price ?? 0,
                'transaction'    => $item->amount ?? 0,
                'company_payment'=> $item->total_debt ?? 0,
                default          => 0
            };

            if (!isset($currencyTotals[$currency])) {
                $currencyTotals[$currency] = 0;
            }
            $currencyTotals[$currency] += $amount;
        }

        return [
            'total_count'     => $totalCount,
            'currency_totals' => $currencyTotals,
            'report_type'     => $this->getReportTypeLabel(),
            'current_date'    => Jalalian::now()->format('Y/m/d'),
        ];
    }

    // =============================================
    // محاسبه قرض مشتری‌ها (ویژه گزارش بردگی)
    // =============================================

    public function getLoanSummaryProperty()
    {
        if ($this->reportType !== 'loan') {
            return null;
        }

        // قرض خالص هر مشتری - گروه‌بندی شده بر اساس ارز
        $customerLoans = CustomerStory::query()
            ->selectRaw("
                customer_id,
                currency,
                SUM(CASE WHEN type = 'برد' THEN amount ELSE 0 END) AS total_borrow,
                SUM(CASE WHEN type = 'رسید' THEN amount ELSE 0 END) AS total_receipt
            ")
            ->when($this->customerId, fn($q) => $q->where('customer_id', $this->customerId))
            ->when($this->startDate, fn($q) => $q->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('date', '<=', $this->endDate))
            ->groupBy('customer_id', 'currency')
            ->with('customer')
            ->get()
            ->map(function ($item) {
                $item->net_loan = $item->total_borrow - $item->total_receipt;
                $item->customer_name = $item->customer->name ?? 'ناشناس';
                return $item;
            });

        // جمع کل برای هر ارز
        $currencyTotals = $customerLoans->groupBy('currency')->map(function ($items) {
            return [
                'total_borrow'  => $items->sum('total_borrow'),
                'total_receipt' => $items->sum('total_receipt'),
                'net_loan'      => $items->sum('net_loan'),
            ];
        });

        // جمع کل همه ارزها
        $grandTotal = [
            'total_borrow'  => $customerLoans->sum('total_borrow'),
            'total_receipt' => $customerLoans->sum('total_receipt'),
            'net_loan'      => $customerLoans->sum('net_loan'),
        ];

        return [
            'customer_loans'  => $customerLoans,
            'currency_totals' => $currencyTotals,
            'grand_total'     => $grandTotal,
        ];
    }

    // =============================================
    // متدهای کمکی
    // =============================================

    private function getReportTypeLabel()
    {
        $types = [
            'withdraw_log'    => 'برداشت‌ها',
            'loan'            => 'بردگی‌ها',
            'sell'            => 'فروش‌ها',
            'buy'             => 'خریدها',
            'transaction'     => 'تراکنش‌ها',
            'company_payment' => 'پرداخت‌های شرکت',
        ];

        return $types[$this->reportType] ?? 'نامشخص';
    }

    public function resetFilters()
    {
        $this->reset([
            'startDate',
            'endDate',
            'startDateJalali',
            'endDateJalali',
            'customerId',
            'staffId',
            'companyId',
            'currency',
            'type',
            'search',
            'amountMin',
            'amountMax'
        ]);

        $this->setDefaultJalaliDates();
        $this->resetPage();

        $this->dispatch('notify', [
            'type'    => 'success',
            'message' => 'تمامی فیلترها بازنشانی شدند'
        ]);
    }

    private function clearError($field)
    {
        $errors = $this->getErrorBag();
        if ($errors->has($field)) {
            $errors->forget($field);
        }
    }

    // =============================================
    // رندر
    // =============================================

    public function render()
    {
        return view('livewire.import.general-reports', [
            'reports'      => $this->reports,
            'customers'    => $this->customers,
            'staffs'       => $this->staffs,
            'companies'    => $this->companies,
            'summary'      => $this->summary,
            'loanSummary'  => $this->loanSummary,
        ]);
    }
}