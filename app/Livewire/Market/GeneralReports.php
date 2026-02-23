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
    public $floor;

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
        'floor' => ['except' => ''],
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
            'floor' => $this->floor,
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

                case 'fund':
                    $query = $this->buildFundQuery();
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

        /*
    |--------------------------------------------------------------------------
    | Withdrawals Query
    |--------------------------------------------------------------------------
    */
        $withdrawalsQuery = WithdrawLog::query()
            ->when(
                $this->expansesType,
                fn($q) =>
                $q->where('expanses_type', $this->expansesType)
            )
            ->when(
                $this->currency,
                fn($q) =>
                $q->where('currency', $this->currency)
            )
            ->when(
                $this->startDate,
                fn($q) =>
                $q->whereDate('created_at', '>=', $this->startDate)
            )
            ->when(
                $this->endDate,
                fn($q) =>
                $q->whereDate('created_at', '<=', $this->endDate)
            )
            ->when(
                $this->amountMin,
                fn($q) =>
                $q->where('amount', '>=', $this->amountMin)
            )
            ->when(
                $this->amountMax,
                fn($q) =>
                $q->where('amount', '<=', $this->amountMax)
            )
            ->when(
                $this->search,
                fn($q) =>
                $q->where('description', 'like', "%{$this->search}%")
            );

        // اعمال محدودیت دسترسی
        $withdrawalsQuery = $this->applyAccessControl($withdrawalsQuery, $user);

        $withdrawals = $withdrawalsQuery
            ->get()
            ->map(function ($item) {
                $item->record_type = 'withdraw';
                $item->record_date = $item->created_at;
                return $item;
            });

        /*
    |--------------------------------------------------------------------------
    | Salaries Query (paid + reduce_from)
    |--------------------------------------------------------------------------
    */
        $salariesQuery = Salary::query()
            ->when(
                $this->expansesType,
                fn($q) =>
                $q->where('reduce_from', $this->expansesType)
            )
            ->when(
                $this->currency,
                fn($q) =>
                $q->where('currency', $this->currency)
            )
            ->when(
                $this->startDate,
                fn($q) =>
                $q->whereDate('paid_date', '>=', $this->startDate)
            )
            ->when(
                $this->endDate,
                fn($q) =>
                $q->whereDate('paid_date', '<=', $this->endDate)
            )
            ->when(
                $this->amountMin,
                fn($q) =>
                $q->where('paid', '>=', $this->amountMin)
            )
            ->when(
                $this->amountMax,
                fn($q) =>
                $q->where('paid', '<=', $this->amountMax)
            )
            ->when($this->search, function ($q) {
                $q->whereHas(
                    'staff',
                    fn($q2) =>
                    $q2->where('fullname', 'like', "%{$this->search}%")
                )->orWhereHas(
                    'market',
                    fn($q2) =>
                    $q2->where('name', 'like', "%{$this->search}%")
                );
            });

        // اعمال محدودیت دسترسی
        $salariesQuery = $this->applyAccessControl($salariesQuery, $user);

        $salaries = $salariesQuery
            ->get()
            ->map(function ($item) {
                $item->record_type = 'salary';
                $item->record_date = $item->paid_date;
                return $item;
            });

        /*
    |--------------------------------------------------------------------------
    | Combine, Sort & Paginate
    |--------------------------------------------------------------------------
    */
        $combined = $withdrawals
            ->merge($salaries)
            ->sortByDesc('record_date')
            ->values();

        $page    = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;

        $results = $combined
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

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

        /*
    |--------------------------------------------------------------------------
    | Withdrawals
    |--------------------------------------------------------------------------
    */
        $withdrawalsQuery = WithdrawLog::with(['customer', 'staff'])
            ->when(
                $this->currency,
                fn($q) =>
                $q->where('currency', $this->currency)
            )
            ->when(
                $this->expansesType,
                fn($q) =>
                $q->where('expanses_type', $this->expansesType)
            )
            ->when(
                $this->startDate,
                fn($q) =>
                $q->whereDate('created_at', '>=', $this->startDate)
            )
            ->when(
                $this->endDate,
                fn($q) =>
                $q->whereDate('created_at', '<=', $this->endDate)
            )
            ->when(
                $this->amountMin,
                fn($q) =>
                $q->where('amount', '>=', $this->amountMin)
            )
            ->when(
                $this->amountMax,
                fn($q) =>
                $q->where('amount', '<=', $this->amountMax)
            )
            ->when(
                $this->search,
                fn($q) =>
                $q->where('description', 'like', "%{$this->search}%")
            );

        // محدودیت دسترسی
        $withdrawalsQuery = $this->applyAccessControl($withdrawalsQuery, $user);

        $withdrawals = $withdrawalsQuery
            ->get()
            ->map(function ($item) {
                $item->record_type = 'withdraw';
                $item->sort_date   = $item->created_at;
                return $item;
            });


        /*
    |--------------------------------------------------------------------------
    | Salaries
    | نکته مهم: فیلتر نوع هزینه از طریق reduce_from
    |--------------------------------------------------------------------------
    */
        $salariesQuery = Salary::with(['market', 'staff', 'loan'])
            ->when(
                $this->marketId,
                fn($q) =>
                $q->where('market_id', $this->marketId)
            )
            ->when(
                $this->staffId,
                fn($q) =>
                $q->where('staff_id', $this->staffId)
            )
            ->when(
                $this->currency,
                fn($q) =>
                $q->where('currency', $this->currency)
            )

            // ✅ اصلاح اصلی: هماهنگی با نوع هزینه (کرایه، صندوق، ...)
            ->when(
                $this->expansesType,
                fn($q) =>
                $q->where('reduce_from', $this->expansesType)
            )

            ->when(
                $this->startDate,
                fn($q) =>
                $q->whereDate('paid_date', '>=', $this->startDate)
            )
            ->when(
                $this->endDate,
                fn($q) =>
                $q->whereDate('paid_date', '<=', $this->endDate)
            )
            ->when(
                $this->amountMin,
                fn($q) =>
                $q->where('paid', '>=', $this->amountMin)
            )
            ->when(
                $this->amountMax,
                fn($q) =>
                $q->where('paid', '<=', $this->amountMax)
            )
            ->when($this->search, function ($q) {
                $q->whereHas(
                    'staff',
                    fn($q2) =>
                    $q2->where('fullname', 'like', "%{$this->search}%")
                )->orWhereHas(
                    'market',
                    fn($q2) =>
                    $q2->where('name', 'like', "%{$this->search}%")
                );
            });

        // محدودیت دسترسی
        $salariesQuery = $this->applyAccessControl($salariesQuery, $user);

        $salaries = $salariesQuery
            ->get()
            ->map(function ($item) {
                $item->record_type   = 'salary';
                $item->sort_date     = $item->paid_date;
                $item->expanses_type = $item->reduce_from ?? '-';
                return $item;
            });

        /*
    |--------------------------------------------------------------------------
    | Merge & Sort
    |--------------------------------------------------------------------------
    */
        return $withdrawals
            ->merge($salaries)
            ->sortByDesc('sort_date')
            ->values();
    }

    public function getFloorsProperty()
    {
        $user = Auth::user();

        // گرفتن floorهای منحصر به فرد از shops
        $shopFloors = Shop::when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
            ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
            ->whereNotNull('floor')
            ->distinct()
            ->pluck('floor')
            ->toArray();

        // گرفتن floorهای منحصر به فرد از booths
        $boothFloors = Booth::when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
            ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
            ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
            ->whereNotNull('floor')
            ->distinct()
            ->pluck('floor')
            ->toArray();

        // ادغام و حذف موارد تکراری
        $allFloors = array_unique(array_merge($shopFloors, $boothFloors));

        // تعریف ترتیب دلخواه
        $order = [
            'یک',
            'دو',
            'سه',
            'چهار',
            'زیرزمینی یک',
            'زیرزمینی دو',
            'زیرزمینی سه',
        ];

        // مرتب‌سازی بر اساس آرایه order
        usort($allFloors, function ($a, $b) use ($order) {
            $posA = array_search($a, $order);
            $posB = array_search($b, $order);

            // اگر یکی پیدا نشد، آخر لیست قرار می‌گیرد
            $posA = $posA === false ? PHP_INT_MAX : $posA;
            $posB = $posB === false ? PHP_INT_MAX : $posB;

            return $posA <=> $posB;
        });

        return array_values($allFloors);
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

            ->when($this->floor, function ($q) {
                $q->where(function ($query) {
                    $query->whereHas('shop', function ($q2) {
                        $q2->where('floor', $this->floor); // دقیقاً برابر با حروف
                    })->orWhereHas('booth', function ($q2) {
                        $q2->where('floor', $this->floor); // دقیقاً برابر با حروف
                    });
                });
            })


            ->when($this->search, function ($q) {
                $q->whereHas('shopkeeper', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                    ->orWhereHas('shop', fn($q2) => $q2->where('number', 'like', "%{$this->search}%"))
                    ->orWhereHas('booth', fn($q2) => $q2->where('number', 'like', "%{$this->search}%"))
                    ->orWhere('meter_serial', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }
private function buildFundQuery()
{
    return Accounting::with(['market', 'shop', 'booth', 'shopkeeper'])
        ->when($this->marketId, fn($q) => $q->where('market_id', $this->marketId))
        ->when($this->shopId, fn($q) => $q->where('shop_id', $this->shopId))
        ->when($this->boothId, fn($q) => $q->where('booth_id', $this->boothId))
        ->when($this->shopkeeperId, fn($q) => $q->where('shopkeeper_id', $this->shopkeeperId))
        ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
        ->when($this->expansesType, fn($q) => $q->where('expanses_type', $this->expansesType))
        // فیلتر تاریخ ترکیبی (اگر paid_date موجود باشد از آن استفاده کن، در غیر این صورت created_at)
        ->when($this->startDate, function ($q) {
            $q->where(function ($query) {
                $query->whereDate('paid_date', '>=', $this->startDate)
                      ->orWhereDate('created_at', '>=', $this->startDate);
            });
        })
        ->when($this->endDate, function ($q) {
            $q->where(function ($query) {
                $query->whereDate('paid_date', '<=', $this->endDate)
                      ->orWhereDate('created_at', '<=', $this->endDate);
            });
        })
        // فیلتر مبلغ بر اساس قدر مطلق (هم مثبت و هم منفی)
        // ->when($this->amountMin !== null && $this->amountMin !== '', fn($q) => $q->whereRaw('ABS(paid) >= ?', [$this->amountMin]))
        // ->when($this->amountMax !== null && $this->amountMax !== '', fn($q) => $q->whereRaw('ABS(paid) <= ?', [$this->amountMax]))
        ->when($this->floor, function ($q) {
            $q->where(function ($query) {
                $query->whereHas('shop', fn($q2) => $q2->where('floor', $this->floor))
                    ->orWhereHas('booth', fn($q2) => $q2->where('floor', $this->floor));
            });
        })
        ->when($this->search, function ($q) {
            $q->whereHas('shopkeeper', fn($q2) => $q2->where('fullname', 'like', "%{$this->search}%"))
                ->orWhereHas('shop', fn($q2) => $q2->where('number', 'like', "%{$this->search}%"))
                ->orWhereHas('booth', fn($q2) => $q2->where('number', 'like', "%{$this->search}%"))
                ->orWhere('meter_serial', 'like', "%{$this->search}%");
        })
        ->orderBy('paid_date', 'desc')
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

    private function calculateAccountingTotals($data)
    {
        $totalPrice = 0;
        $totalPaid = 0;
        $totalRemained = 0;
        $totalAll = 0;

        foreach ($data as $item) {
            $totalPrice += $item->price ?? 0;
            $totalPaid += $item->paid ?? 0;
            $totalRemained += $item->remained ?? 0;
            $totalAll += ($item->price ?? 0) + ($item->remained ?? 0);
        }

        return [
            'total_price' => $totalPrice,
            'total_paid' => $totalPaid,
            'total_remained' => $totalRemained,
            'total_all' => $totalAll,
        ];
    }

    public function getSummaryProperty()
    {
        $data = $this->getReportData(true);

        $currencyTotals = $this->calculateCurrencyTotals($data);

        $totalAmount = 0;
        $currencyReceipts = [];
        $currencyWithdrawals = [];

        // محاسبات برای گزارش حسابداری
        $accountingTotals = [];
        if ($this->reportType === 'accounting') {
            $accountingTotals = $this->calculateAccountingTotals($data);
            $totalAmount = $accountingTotals['total_price'] ?? 0;
        } else {
            switch ($this->reportType) {
                case 'withdraw_salary':
                    $totalAmount = $data->sum(function ($item) {
                        if ($item->record_type === 'withdraw') {
                            return $item->amount ?? 0;
                        }
                        return $item->paid ?? 0;
                    });
                    break;

                case 'outside':
                    $totalAmount = $data->sum('paid');
                    break;

                case 'salary':
                    $totalAmount = $data->sum('paid');
                    break;

                case 'deposit':
                    $totalAmount = $data->sum('price');
                    break;

                case 'loan':
                    $totalAmount = $data->sum('amount');
                    break;

                case 'payment':
                    $totalAmount = $data->sum('amount');
                    break;

                case 'buy':
                    $totalAmount = $data->sum('price');
                    break;

                case 'fund':
                    $totalAmount = $data->sum('paid');
                    foreach ($data as $item) {
                        $currency = $item->currency ?? 'نامشخص';
                        $paid = (float) ($item->paid ?? 0);
                        if ($paid > 0) {
                            $currencyReceipts[$currency] = ($currencyReceipts[$currency] ?? 0) + $paid;
                        } elseif ($paid < 0) {
                            $currencyWithdrawals[$currency] = ($currencyWithdrawals[$currency] ?? 0) + $paid;
                        }
                    }
                    foreach ($currencyWithdrawals as $cur => $val) {
                        $currencyWithdrawals[$cur] = abs($val);
                    }
                    break;

                case 'sell':
                    $totalAmount = $data->sum('price');
                    break;

                case 'withdraw_log':
                    $totalAmount = $data->sum('amount');
                    break;

                default:
                    $totalAmount = 0;
            }
        }

        return [
            'total_count' => $data->count(),
            'total_amount' => $totalAmount,
            'currency_totals' => $currencyTotals,
            'accounting_totals' => $accountingTotals,
            'report_type' => $this->getReportTypeLabel(),
            'current_date' => Jalalian::now()->format('Y/m/d'),
            'fund_receipts' => $currencyReceipts,
            'fund_withdrawals' => $currencyWithdrawals,
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
                'fund' => $item->paid ?? 0,
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
            'fund' => 'گزارش صندوق',

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
            'amountMax',
            'floor',
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
            'floors' => $this->floors,
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
