<?php

namespace App\Livewire\ToolsPanel;

use App\Exports\Tools\GeneralReportExport;
use App\Exports\Tools\GeneralReportPdfExport;
use App\Models\Tools\Inventories;
use App\Models\Tools\InventoryHistory;
use App\Models\Tools\Loan;
use App\Models\Tools\Salarys;
use App\Models\Tools\Sale;
use App\Models\Tools\SaleItem;
use App\Models\Tools\User;
use App\Models\Tools\Warehouses;
use App\Models\Tools\WarehousesHistory;
use App\Models\Tools\Withdrawals;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

class GeneralReports extends Component
{
    use WithPagination;

    public $reportType = 'salary';
    public $startDate;
    public $endDate;
    public $startDateJalali;
    public $endDateJalali;
    public $currency;
    public $type;
    public $status;
    public $search = '';
    public $amountMin;
    public $amountMax;
    public $saleType;
    public $productName;
    public $category;
    public $supplierName;
    public $packageType;
    public $isActive = '';

    protected $queryString = [
        'reportType' => ['except' => 'salary'],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'startDateJalali' => ['except' => ''],
        'endDateJalali' => ['except' => ''],
        'currency' => ['except' => ''],
        'type' => ['except' => ''],
        'status' => ['except' => ''],
        'search' => ['except' => ''],
        'amountMin' => ['except' => ''],
        'amountMax' => ['except' => ''],
        'saleType' => ['except' => ''],
        'productName' => ['except' => ''],
        'category' => ['except' => ''],
        'supplierName' => ['except' => ''],
        'packageType' => ['except' => ''],
        'isActive' => ['except' => ''],
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
            'currency' => $this->currency,
            'type' => $this->type,
            'search' => $this->search,
            'amountMin' => $this->amountMin,
            'amountMax' => $this->amountMax,
            'saleType' => $this->saleType,
            'productName' => $this->productName,
            'category' => $this->category,
            'supplierName' => $this->supplierName,
            'packageType' => $this->packageType,
            'isActive' => $this->isActive,
        ];
    }

    private function getReportData($forExport = false)
    {
        $user = Auth::guard('tools')->user();

        // 🔥 دیباگ: اطلاعات کاربر
        Log::info('🔍 GeneralReports - User Info', [
            'user_id' => $user->id ?? 'null',
            'role' => $user->role ?? 'null',
            'admin_id' => $user->admin_id ?? 'null',
            'report_type' => $this->reportType
        ]);

        if (!$user) {
            Log::warning('🚫 User not authenticated in GeneralReports');
            return $forExport ? collect() : collect()->paginate(20);
        }

        try {
            switch ($this->reportType) {
                case 'salary':
                    $query = $this->buildSalaryQuery();
                    Log::info('💰 Salary Query Built', ['table' => 'salary']);
                    break;
                case 'withdrawal':
                    $query = $this->buildWithdrawalsQuery();
                    Log::info('💸 Withdrawal Query Built', ['table' => 'withdrawal']);
                    break;
                case 'loan':
                    $query = $this->buildLoanQuery();
                    Log::info('🏦 Loan Query Built', ['table' => 'loans']);
                    break;
                case 'inventory':
                    $query = $this->buildInventoriesQuery();
                    break;
                case 'warehouse':
                    $query = $this->buildWarehousesQuery();
                    break;
                case 'sale':
                    $query = $this->buildSaleQuery();
                    break;
                case 'sale_items':
                    $query = $this->buildSaleItemsQuery();
                    break;
                case 'inventory_history':
                    $query = $this->buildInventoryHistoryQuery();
                    break;
                case 'warehouse_history':
                    $query = $this->buildWarehousesHistoryQuery();
                    break;
                default:
                    $query = $this->buildSalaryQuery();
            }

            // 🔥 دیباگ: قبل از اعمال دسترسی
            Log::info('📊 Query BEFORE access control', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'report_type' => $this->reportType
            ]);

            $query = $this->applyAccessControl($query);

            // 🔥 دیباگ: بعد از اعمال دسترسی
            Log::info('🔒 Query AFTER access control', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'report_type' => $this->reportType
            ]);

            $result = $forExport ? $query->get() : $query->paginate(20);

            Log::info('✅ Final result count', [
                'count' => $forExport ? $result->count() : $result->total(),
                'report_type' => $this->reportType
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('❌ Error in getReportData: ' . $e->getMessage(), [
                'reportType' => $this->reportType,
                'user_id' => $user->id ?? 'null',
                'trace' => $e->getTraceAsString()
            ]);

            return $forExport ? collect() : collect()->paginate(20);
        }
    }

    private function applyAccessControl($query)
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        if (!$user) {
            return $query->whereRaw('1=0');
        }

        // سوپر ادمین همه چیز را می‌بیند
        if ($user->role === 'superadmin') {
            return $query;
        }

        $model = $query->getModel();
        $table = $model->getTable();

        // استفاده از connection صحیح برای گرفتن ستون‌ها
        $columns = DB::connection('tools')->getSchemaBuilder()->getColumnListing($table);

        // 🔥 دیباگ: اطلاعات جدول
        Log::info('📋 Table structure check', [
            'table' => $table,
            'columns' => $columns,
            'has_admin_id' => in_array('admin_id', $columns),
            'has_user_id' => in_array('user_id', $columns)
        ]);

        // اگر جدول هم admin_id و هم user_id دارد
        if (in_array('admin_id', $columns) && in_array('user_id', $columns)) {
            if ($user->role === 'admin') {
                // ادمین: داده‌های خودش + زیرکاربرهایش + داده‌های با admin_id خالی
                $subUserIds = User::where('admin_id', $user->id)->pluck('id')->toArray();
                return $query->where(function ($q) use ($user, $subUserIds) {
                    $q->where('admin_id', $user->id)
                        ->orWhereIn('user_id', array_merge([$user->id], $subUserIds))
                        ->orWhereNull('admin_id'); // 🔥 اضافه کردن این خط
                });
            } else {
                // کاربر معمولی: داده‌های خودش + داده‌های مربوط به ادمینش
                return $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhere('admin_id', $user->admin_id)
                        ->orWhereNull('admin_id'); // در صورت داده‌های عمومی
                });
            }
        }

        // اگر فقط admin_id دارد
        if (in_array('admin_id', $columns)) {
            $adminId = $user->role === 'admin' ? $user->id : ($user->admin_id ?? $user->id);
            return $query->where(function ($q) use ($adminId) {
                $q->where('admin_id', $adminId)
                    ->orWhereNull('admin_id'); // 🔥 اضافه کردن این خط
            });
        }

        // اگر فقط user_id دارد
        if (in_array('user_id', $columns)) {
            return $query->where('user_id', $user->id);
        }

        // اگر هیچکدام از ستون‌ها وجود ندارد
        Log::warning('⚠️ No access control columns found', ['table' => $table]);
        return $query;
    }

    private function buildSalaryQuery()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        return Salarys::query()
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->where('description', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildWithdrawalsQuery()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        return Withdrawals::query()
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, fn($q) => $q->where('description', 'like', "%{$this->search}%"))
            ->orderBy('created_at', 'desc');
    }

    private function buildLoanQuery()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        return Loan::with('customer')
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->currency, fn($q) => $q->where('currency', $this->currency))
            ->when($this->startDate, fn($q) => $q->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('date', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('amount', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->where('description', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    // سایر متدهای buildQuery (inventory, warehouse, sale, etc.) همانند قبل باقی می‌مانند
    private function buildInventoriesQuery()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        return Inventories::query()
            ->when($this->productName, fn($q) => $q->where('product_name', 'like', "%{$this->productName}%"))
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->when($this->supplierName, fn($q) => $q->where('supplier_name', 'like', "%{$this->supplierName}%"))
            ->when($this->packageType, fn($q) => $q->where('package_type', $this->packageType))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->isActive !== '', fn($q) => $q->where('is_active', $this->isActive))
            ->when($this->search, function ($q) {
                $q->where('barcode', 'like', "%{$this->search}%")
                    ->orWhere('product_name', 'like', "%{$this->search}%")
                    ->orWhere('category', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildWarehousesQuery()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        return Warehouses::query()
            ->when($this->productName, fn($q) => $q->where('product_name', 'like', "%{$this->productName}%"))
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->when($this->supplierName, fn($q) => $q->where('supplier_name', 'like', "%{$this->supplierName}%"))
            ->when($this->packageType, fn($q) => $q->where('package_type', $this->packageType))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->isActive !== '', fn($q) => $q->where('is_active', $this->isActive))
            ->when($this->search, function ($q) {
                $q->where('barcode', 'like', "%{$this->search}%")
                    ->orWhere('product_name', 'like', "%{$this->search}%")
                    ->orWhere('category', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildSaleQuery()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        return Sale::query()
            ->when($this->saleType, fn($q) => $q->where('sale_type', $this->saleType))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->amountMin, fn($q) => $q->where('total_price', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('total_price', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->where('buyer_name', 'like', "%{$this->search}%")
                    ->orWhere('invoice_number', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildSaleItemsQuery()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        return SaleItem::with(['sale', 'warehouse'])
            ->when($this->startDate, fn($q) => $q->whereHas('sale', fn($q2) => $q2->whereDate('created_at', '>=', $this->startDate)))
            ->when($this->endDate, fn($q) => $q->whereHas('sale', fn($q2) => $q2->whereDate('created_at', '<=', $this->endDate)))
            ->when($this->amountMin, fn($q) => $q->where('total_price', '>=', $this->amountMin))
            ->when($this->amountMax, fn($q) => $q->where('total_price', '<=', $this->amountMax))
            ->when($this->search, function ($q) {
                $q->whereHas('warehouse', fn($q2) => $q2->where('product_name', 'like', "%{$this->search}%"))
                    ->orWhereHas('sale', fn($q2) => $q2->where('buyer_name', 'like', "%{$this->search}%"));
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildInventoryHistoryQuery()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        return InventoryHistory::with(['inventory'])
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->search, function ($q) {
                $q->whereHas('inventory', fn($q2) => $q2->where('product_name', 'like', "%{$this->search}%"))
                    ->orWhere('reference_number', 'like', "%{$this->search}%")
                    ->orWhere('notes', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    private function buildWarehousesHistoryQuery()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        return WarehousesHistory::with(['warehouse'])
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->search, function ($q) {
                $q->whereHas('warehouse', fn($q2) => $q2->where('product_name', 'like', "%{$this->search}%"))
                    ->orWhere('reference_number', 'like', "%{$this->search}%")
                    ->orWhere('notes', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc');
    }

    public function getReportsProperty()
    {
        return $this->getReportData();
    }

    public function getSummaryProperty()
    {
        $data = $this->getReportData(true);

        $currencyTotals = $this->calculateCurrencyTotals($data);

        $totalAmount = match ($this->reportType) {
            'salary' => $data->sum('amount'),
            'withdrawal' => $data->sum('amount'),
            'sale' => $data->sum('total_price'),
            'sale_items' => $data->sum('total_price'),
            'loan' => $data->sum('amount'),
            'inventory' => $data->sum('total_purchase_amount'),
            'warehouse' => $data->sum('total_purchase_amount'),
            default => 0
        };

        return [
            'total_count' => $data->count(),
            'total_amount' => $totalAmount,
            'currency_totals' => $currencyTotals,
            'report_type' => $this->getReportTypeLabel(),
            'current_date' => Jalalian::now()->format('Y/m/d'),
        ];
    }

    private function calculateCurrencyTotals($data)
    {
        $currencyTotals = [];

        foreach ($data as $item) {
            $currency = $item->currency ?? 'نامشخص';

            $amount = match ($this->reportType) {
                'salary' => $item->amount ?? 0,
                'withdrawal' => $item->amount ?? 0,
                'sale' => $item->total_price ?? 0,
                'sale_items' => $item->total_price ?? 0,
                'loan' => $item->amount ?? 0,
                'inventory' => $item->total_purchase_amount ?? 0,
                'warehouse' => $item->total_purchase_amount ?? 0,
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
            'salary' => 'معاش کارمندان',
            'withdrawal' => 'برداشت‌ها',
            'inventory' => 'موجودی انبار',
            'warehouse' => 'موجودی دوکان',
            'sale' => 'فروش‌ها',
            'sale_items' => 'آیتم‌های فروش',
            'loan' => 'قرض‌ها',
            'inventory_history' => 'تاریخچه انبار',
            'warehouse_history' => 'تاریخچه دوکان',
        ];

        return $types[$this->reportType] ?? 'نامشخص';
    }

    public function getCategoriesProperty()
    {
        try {
            $user = Auth::guard('tools')->user();
            if (!$user) return collect();

            $adminId = $user->role === 'admin' ? $user->id : ($user->admin_id ?? $user->id);

            return Inventories::where('admin_id', $adminId)
                ->distinct()
                ->pluck('category')
                ->filter();
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function getPackageTypesProperty()
    {
        return ['کارتن', 'بسته', 'دانه'];
    }

    public function getStatusesProperty()
    {
        return ['موجود', 'ناموجود', 'در حال تکمیل'];
    }

    public function resetFilters()
    {
        $this->reset([
            'startDate',
            'endDate',
            'startDateJalali',
            'endDateJalali',
            'currency',
            'type',
            'status',
            'search',
            'amountMin',
            'amountMax',
            'saleType',
            'productName',
            'category',
            'supplierName',
            'packageType',
            'isActive'
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
        return view('livewire.tools-panel.general-reports', [
            'reports' => $this->reports,
            'summary' => $this->summary,
            'categories' => $this->categories,
            'packageTypes' => $this->packageTypes,
            'statuses' => $this->statuses,
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
