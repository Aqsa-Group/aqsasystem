<?php

namespace App\Livewire\Market;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Market\Accounting;
use App\Models\Market\Outside;
use App\Models\Market\Deposit;
use App\Models\Market\Loan;
use App\Models\Market\Payment;
use App\Models\Market\Salary;
use App\Models\Market\Buy;
use App\Models\Market\Sell;
use App\Models\Market\WithdrawLog;
use App\Models\Market\Market;
use App\Models\Market\Shop;
use App\Models\Market\Booth;
use App\Models\Market\Shopkeeper;
use App\Models\Market\Customer;
use App\Models\Market\Staff;
use App\Exports\GeneralReportExport;
use App\Exports\GeneralReportPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Morilog\Jalali\Jalalian;
use Illuminate\Pagination\LengthAwarePaginator;

class GeneralReports extends Component
{
    use WithPagination;

    public $reportType = 'withdraw_salary';
    public $startDate;
    public $endDate;
    public $startDateJalali;
    public $endDateJalali;
    public $marketId;
    public $shopId;
    public $boothId;
    public $shopkeeperId;
    public $customerId;
    public $staffId;
    public $currency;
    public $type;
    public $expansesType;
    public $status;
    public $search = '';
    public $amountMin;
    public $amountMax;

    protected $queryString = [
        'reportType' => ['except' => 'withdraw_salary'],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'startDateJalali' => ['except' => ''],
        'endDateJalali' => ['except' => ''],
        'marketId' => ['except' => ''],
        'shopId' => ['except' => ''],
        'boothId' => ['except' => ''],
        'shopkeeperId' => ['except' => ''],
        'customerId' => ['except' => ''],
        'staffId' => ['except' => ''],
        'currency' => ['except' => ''],
        'type' => ['except' => ''],
        'expansesType' => ['except' => ''],
        'status' => ['except' => ''],
        'search' => ['except' => ''],
        'amountMin' => ['except' => ''],
        'amountMax' => ['except' => ''],
    ];

    public function mount()
    {
        $this->setDefaultJalaliDates();
    }

    private function setDefaultJalaliDates()
    {
        // تاریخ پایان = امروز
        $today = Jalalian::now();
        $this->endDateJalali = $today->format('Y/m/d');
        $this->endDate = $today->toCarbon()->format('Y-m-d');

        // تاریخ شروع = یک ماه قبل
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
                'type' => 'error',
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
                'type' => 'error',
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
                'type' => 'error',
                'message' => 'خطا در پیش نمایش PDF: ' . $e->getMessage()
            ]);

            Log::error('PDF Preview Error: ' . $e->getMessage());
        }
    }

    public function printReport()
    {
        return $this->previewPdf();
    }

    private function getFiltersForExport()
    {
        return [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'startDateJalali' => $this->startDateJalali,
            'endDateJalali' => $this->endDateJalali,
            'marketId' => $this->marketId,
            'marketName' => $this->marketId ? Market::find($this->marketId)->name ?? null : null,
            'shopId' => $this->shopId,
            'boothId' => $this->boothId,
            'shopkeeperId' => $this->shopkeeperId,
            'customerId' => $this->customerId,
            'staffId' => $this->staffId,
            'currency' => $this->currency,
            'type' => $this->type,
            'expansesType' => $this->expansesType,
            'search' => $this->search,
            'amountMin' => $this->amountMin,
            'amountMax' => $this->amountMax,
        ];
    }

    private function getReportData($forExport = false)
    {
        $user = Auth::user();

        try {
            switch ($this->reportType) {
                case 'withdraw_salary':
                    if ($forExport) {
                        return $this->getCombinedDataForExport();
                    }
                    return $this->buildWithdrawSalaryQuery();

                case 'accounting':
                    $query = $this->buildAccountingQuery();
                    break;

                case 'outside':
                    $query = $this->buildOutsideQuery();
                    break;

                case 'deposit':
                    $query = $this->buildDepositQuery();
                    break;

                case 'loan':
                    $query = $this->buildLoanQuery();
                    break;

                case 'payment':
                    $query = $this->buildPaymentQuery();
                    break;

                case 'buy':
                    $query = $this->buildBuyQuery();
                    break;

                case 'sell':
                    $query = $this->buildSellQuery();
                    break;

                default:
                    $query = $this->buildWithdrawSalaryQuery();
            }

            if ($this->reportType !== 'withdraw_salary') {
                $query = $this->applyAccessControl($query, $user);
            }

            return $forExport ? $query->get() : $query->paginate(20);
        } catch (\Exception $e) {
            Log::error('Error in getReportData: ' . $e->getMessage(), [
                'reportType' => $this->reportType,
                'user_id' => $user->id
            ]);

            return $forExport ? collect() : collect()->paginate(20);
        }
    }
    private function applyAccessControl($query, $user)
    {
        if ($user->role === 'superadmin') {
            return $query;
        }

        $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;

        $model = $query->getModel();
        $table = $model->getTable();
        $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($table);

        if (in_array('admin_id', $columns)) {
            return $query->where(function ($q) use ($adminId) {
                $q->where('admin_id', $adminId)
                    ->orWhereNull('admin_id');
            });
        }

        return $query;
    }

    private function buildWithdrawSalaryQuery()
    {
        $user = Auth::user();

        // Withdrawals
        $withdrawalsQuery = WithdrawLog::with(['customer', 'staff'])
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->expansesType, fn($q) => $q->where('expanses_type', $this->expansesType))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->where('recipient_name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });

        $withdrawalsQuery = $this->applyAccessControl($withdrawalsQuery, $user);

        $withdrawals = $withdrawalsQuery->get()->map(function ($item) {
            $item->record_type = 'withdraw';
            return $item;
        });

        // Salaries (🔧 اصلاح salary → paid)
        $salariesQuery = Salary::with(['market', 'staff', 'loan'])
            ->when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($this->staffId, fn($q) => $q->where('staff_id', $this->staffId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('paid_date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('paid_date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('paid', '>=', $this->amountMin))   // ✅
            ->when($this->amountMax, fn($q) => $q->where('paid', '<=', $this->amountMax))   // ✅
            ->when($this->search, function ($q) {
                $q->whereHas('staff', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('market', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"));
            });

        $salariesQuery = $this->applyAccessControl($salariesQuery, $user);

        $salaries = $salariesQuery->get()->map(function ($item) {
            $item->record_type = 'salary';
            return $item;
        });

    $combined = $withdrawals->merge($salaries)
    ->sortBy(function ($item) {
        return $item->record_type === 'withdraw' 
            ? strtotime($item->created_at) 
            : strtotime($item->paid_date);  
    });

        // Pagination
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $results = $combined->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $combined->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    private function getCombinedDataForExport()
    {
        $user = Auth::user();

        $withdrawalsQuery = WithdrawLog::query()
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->where('recipient_name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });


        $withdrawalsQuery = $this->applyAccessControl($withdrawalsQuery, $user);

        $withdrawals = $withdrawalsQuery->get()
            ->map(function ($item) {
                $item->record_type = 'withdraw';
                return $item;
            });

        $salariesQuery = Salary::with(['market', 'staff', 'loan'])
            ->when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($this->staffId, fn($q) => $q->where('staff_id', $this->staffId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('paid_date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('paid_date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('paid', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('paid', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('staff', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('market', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"));
            });

        $salariesQuery = $this->applyAccessControl($salariesQuery, $user);

        $salaries = $salariesQuery->get()
            ->map(function ($item) {
                $item->record_type = 'salary';
                return $item;
            });

        return $withdrawals->merge($salaries)
            ->sortByDesc(function ($item) {
                return $item->record_type === 'برداشت' ? $item->created_at : $item->paid_date;
            });
    }

    private function buildAccountingQuery()
    {
        return Accounting::with(['market', 'shop', 'booth', 'shopkeeper'])
            ->when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($this->shopId, fn($q) => $q->where('shop_id', $this->shopId))
            ->when($this->boothId, fn($q) => $q->where('booth_id', $this->boothId))
            ->when($this->shopkeeperId, fn($q) => $q->where('shopkeeper_id', $this->shopkeeperId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->expansesType, fn($q) => $q->where('expanses_type', $this->expansesType))
            ->when($this->startDate, fn($q) => $q->whereDate('paid_date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('paid_date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('price', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('price', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('shopkeeper', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('shop', fn($q2) => $q2->where('number', 'like', "%{$this->search}%"))
                    ->orWhereHas('booth', fn($q2) => $q2->where('number', 'like', "%{$this->search}%"))
                    ->orWhere('meter_serial', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildOutsideQuery()
    {
        return Outside::with(['market', 'customer', 'staff', 'shopkeeper'])
            ->when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($this->customerId, fn($q) => $q->where('customer_id', $this->customerId))
            ->when($this->staffId, fn($q) => $q->where('staff_id', $this->staffId))
            ->when($this->shopkeeperId, fn($q) => $q->where('shopkeeper_id', $this->shopkeeperId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->startDate, fn($q) => $q->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('paid', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('paid', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('customer', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('staff', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('shopkeeper', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildDepositQuery()
    {
        return Deposit::with(['accounting.market', 'accounting.shop', 'accounting.booth', 'accounting.shopkeeper'])
            ->where('remained', '>', 0)
            ->when($this->marketId, fn($q) => $q->whereHas('accounting', fn($q2) => $q2->where('market_id', $this->marketId)))
            ->when($this->shopId, fn($q) => $q->where('shop_id', $this->shopId))
            ->when($this->boothId, fn($q) => $q->where('booth_id', $this->boothId))
            ->when($this->shopkeeperId, fn($q) => $q->where('shopkeeper_id', $this->shopkeeperId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->expansesType, fn($q) => $q->where('expanses_type', $this->expansesType))
            ->when($this->startDate, fn($q) => $q->whereDate('paid_date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('paid_date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('price', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('price', '<=', $this->amountMax))
            ->orderBy('created_at', 'desc');
    }

    private function buildLoanQuery()
    {
        return Loan::with(['market', 'customer', 'shopkeeper', 'staff'])
            ->when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($this->customerId, fn($q) => $q->where('customer_id', $this->customerId))
            ->when($this->shopkeeperId, fn($q) => $q->where('shopkeeper_id', $this->shopkeeperId))
            ->when($this->staffId, fn($q) => $q->where('staff_id', $this->staffId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->startDate, fn($q) => $q->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('customer', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('shopkeeper', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('staff', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildPaymentQuery()
    {
        return Payment::with(['loan', 'customer', 'shopkeeper', 'staff'])
            ->when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($this->customerId, fn($q) => $q->where('customer_id', $this->customerId))
            ->when($this->shopkeeperId, fn($q) => $q->where('shopkeeper_id', $this->shopkeeperId))
            ->when($this->staffId, fn($q) => $q->where('staff_id', $this->staffId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('customer', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('shopkeeper', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('staff', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildBuyQuery()
    {
        return Buy::with(['market', 'customer'])
            ->when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($this->customerId, fn($q) => $q->where('customer_id', $this->customerId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('price', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('price', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('customer', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhere('property', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildSellQuery()
    {
        return Sell::with(['market', 'customer', 'booth', 'advertisment'])
            ->when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($this->customerId, fn($q) => $q->where('customer_id', $this->customerId))
            ->when($this->boothId, fn($q) => $q->where('booth_id', $this->boothId))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('price', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('price', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('customer', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhere('property', 'like', "%{$this->search}%")
                    ->orWhere('details', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildWithdrawLogQuery()
    {
        return WithdrawLog::with(['customer', 'staff'])
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->expansesType, fn($q) => $q->where('expanses_type', $this->expansesType))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('customer', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('staff', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    public function getReportsProperty()
    {
        return $this->getReportData();
    }

    public function getMarketsProperty()
    {
        $user = Auth::user();

        return Market::when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
            ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
            ->pluck('name', 'id');
    }

    public function getShopsProperty()
    {
        $user = Auth::user();

        return Shop::when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
            ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
            ->pluck('number', 'id');
    }

    public function getBoothsProperty()
    {
        $user = Auth::user();

        return Booth::when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
            ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
            ->pluck('number', 'id');
    }

    public function getShopkeepersProperty()
    {
        $user = Auth::user();

        return Shopkeeper::when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
            ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
            ->pluck('fullname', 'id');
    }

    public function getCustomersProperty()
    {
        $user = Auth::user();

        return Customer::when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
            ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
            ->pluck('fullname', 'id');
    }

    public function getStaffsProperty()
    {
        $user = Auth::user();

        return Staff::when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
            ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
            ->pluck('fullname', 'id');
    }

    public function getSummaryProperty()
    {
        $data = $this->getReportData(true);

        $currencyTotals = $this->calculateCurrencyTotals($data);

        $totalAmount = $data->sum(function ($item) {
            if ($item->record_type === 'withdraw') {
                return $item->amount ?? 0;
            }

            if ($item->record_type === 'salary') {
                return $item->paid ?? 0;
            }

            return 0;
        });

        return [
            'total_count' => $data->count(),
            'total_amount' => $totalAmount,
            'currency_totals' => $currencyTotals,
            'report_type' => $this->getReportTypeLabel(),
            'current_date' => Jalalian::now()->format('Y/m/d'),

            // جمع‌های دیگر برای انواع گزارش
            'accounting' => $data->sum('price'),
            'outside' => $data->sum('paid'),
            'salary' => $data->sum('salary'),
            'deposit' => $data->sum('price'),
            'loan' => $data->sum('amount'),
            'payment' => $data->sum('amount'),
            'buy' => $data->sum('price'),
            'sell' => $data->sum('price'),
            'withdraw_log' => $data->sum('amount'),
        ];
    }

    private function calculateCurrencyTotals($data)
    {
        $currencyTotals = [];

        foreach ($data as $item) {
            $currency = $item->currency ?? 'نامشخص';

            $amount = match ($this->reportType) {
                'withdraw_salary' => $item->record_type === 'withdraw' ? $item->amount : ($item->salary ?? 0),
                'accounting' => $item->price ?? 0,
                'outside' => $item->paid ?? 0,
                'deposit' => $item->price ?? 0,
                'salary' => $item->salary ?? 0,
                'loan' => $item->amount ?? 0,
                'payment' => $item->amount ?? 0,
                'buy' => $item->price ?? 0,
                'sell' => $item->price ?? 0,
                'withdraw_log' => $item->amount ?? 0,
                default => 0
            };

            if (!isset($currencyTotals[$currency])) {
                $currencyTotals[$currency] = 0;
            }

            $currencyTotals[$currency] += $amount;
        }

        return $currencyTotals;
    }

    private function getReportTypeLabel()
    {
        $types = [
            'withdraw_salary' => 'برداشت‌ها و معاش کارمندان',
            'accounting' => 'حسابداری',
            'outside' => 'عواید بیرونی',
            'salary' => 'معاش کارمندان',
            'deposit' => 'تسویه نشده‌ها',
            'loan' => 'بردگی‌ها',
            'payment' => 'رسیدها',
            'buy' => 'خریدها',
            'sell' => 'فروش‌ها',
            'withdraw_log' => 'برداشت‌ها',
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
            'marketId',
            'shopId',
            'boothId',
            'shopkeeperId',
            'customerId',
            'staffId',
            'currency',
            'type',
            'expansesType',
            'status',
            'search',
            'amountMin',
            'amountMax'
        ]);

        $this->setDefaultJalaliDates();
        $this->resetPage();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'تمامی فیلترها بازنشانی شدند'
        ]);
    }

    public function render()
    {
        return view('livewire.market.general-reports', [
            'reports' => $this->reports,
            'markets' => $this->markets,
            'shops' => $this->shops,
            'booths' => $this->booths,
            'shopkeepers' => $this->shopkeepers,
            'customers' => $this->customers,
            'staffs' => $this->staffs,
            'summary' => $this->summary,
        ]);
    }

    private function clearError($field)
    {
        $errors = $this->getErrorBag();
        if ($errors->has($field)) {
            $errors->forget($field);
        }
    }
}
