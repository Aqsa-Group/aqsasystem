<?php

namespace App\Livewire\ToolsPanel;

use App\Models\Tools\Customer;
use App\Models\Tools\Transaction;
use App\Models\Tools\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;

class TransactionsReports extends Component
{
    // Search and customer properties
    public $search = '';
    public $selectedCustomer = null;
    public $selectedCustomerName = '';
    public $selectedCustomerId = null;
    public $selectedAccount;
    public $accountSearch = '';
    public $filteredCustomers = [];
    public $additionalCustomers = [];
    public $customers;

    // Date properties
    public $startDate;
    public $endDate;
    public $startDateDisplay;
    public $endDateDisplay;

    // Filter properties
    public $selectedCurrencies = [];
    public $typeTransaction = '';
    public $typeTransaction2 = '';
    public $typeDocument = '';
    public $zone = '';
    public $by = '';
    public $description = '';

    // Data properties
    public $transactions = [];
    public $previousBalances = [];
    public $totalBalances = [];

    // Currency configuration
    public $currencies = [
        ['code' => 'usd', 'name_fa' => 'دالر'],
        ['code' => 'afn', 'name_fa' => 'افغانی'],
        ['code' => 'eur', 'name_fa' => 'یورو'],
        ['code' => 'irr', 'name_fa' => 'تومان'],
        ['code' => 'aed', 'name_fa' => 'درهم'],
        ['code' => 'try', 'name_fa' => 'لیره'],
    ];

    /**
     * Initialize component
     */
    public function mount()
    {
        $this->loadCustomers();
        $this->setDefaultDates();

        // Check if customer is selected from main page
        if (session()->has('selected_customer_id')) {
            $customerId = session('selected_customer_id');
            $this->selectCustomer($customerId);
            session()->forget('selected_customer_id');
        }

        if ($this->selectedCustomer) {
            $this->calculatePreviousBalances();
            $this->loadTransactions();
        }

        $this->initializeUserCustomers();
    }

    /**
     * Initialize customers based on user permissions
     */
    private function initializeUserCustomers()
    {
        $user = Auth::guard('tools')->user();
        if (!$user) {
            $this->customers = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = $this->getRelatedUserIds($adminId);

        $this->customers = Customer::select('id', 'account_number', 'fullname')
            ->where(function ($query) use ($adminId, $relatedUserIds) {
                $query->where('admin_id', $adminId)
                    ->orWhereHas('transactions', function ($t) use ($relatedUserIds) {
                        $t->whereIn('user_id', $relatedUserIds)
                            ->orWhereIn('admin_id', $relatedUserIds);
                    });
            })
            ->orderBy('fullname')
            ->get();

        $this->customers = collect($this->customers);
    }

    /**
     * Get related user IDs for the current admin
     */
    private function getRelatedUserIds($adminId)
    {
        return User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();
    }

    /**
     * Load customers based on search criteria
     */
    private function loadCustomers()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        $query = Customer::query()->where('admin_id', $adminId);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('account_number', 'like', '%' . $this->search . '%');
            });
        }

        $this->customers = $query->take(10)->get();
    }

    /**
     * Normalize date string to YYYY-MM-DD format
     */
    private function normalizeDate($date)
    {
        if (!$date) return null;

        // Convert Persian numbers to Latin
        $persianNums = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $latinNums   = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $date = str_replace($persianNums, $latinNums, $date);

        // Clean and standardize separators
        $date = trim($date);
        $date = preg_replace('/\s+/', '', $date);

        // Handle YYYY/MM/DD format (from datepicker)
        if (preg_match('/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/', $date, $matches)) {
            $year = $matches[1];
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
            return $year . '-' . $month . '-' . $day;
        }

        // Handle YYYY-MM-DD format
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $date, $matches)) {
            $year = $matches[1];
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
            return $year . '-' . $month . '-' . $day;
        }

        // Handle format with Afghan month names
        if (preg_match('/^(\d{4})\/([^\/]+)\/(\d{1,2})$/', $date, $matches)) {
            $year = $matches[1];
            $monthName = $matches[2];
            $day = str_pad($matches[3], 2, '0', STR_PAD_LEFT);

            $afghanMonths = ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'];
            $monthIndex = array_search($monthName, $afghanMonths);

            if ($monthIndex !== false) {
                $month = str_pad($monthIndex + 1, 2, '0', STR_PAD_LEFT);
                return $year . '-' . $month . '-' . $day;
            }
        }

        return null;
    }

    /**
     * Load transactions with applied filters
     */
    public function loadTransactions()
    {
        if (!$this->selectedCustomer) {
            Log::debug("loadTransactions: No customer selected");
            return;
        }

        $user = Auth::guard('tools')->user();
        if (!$user) {
            $this->transactions = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = $this->getRelatedUserIds($adminId);

        Log::debug("loadTransactions: Starting", [
            'customer_id' => $this->selectedCustomer,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate
        ]);

        $baseQuery = Transaction::query()
            ->where('customer_id', $this->selectedCustomer)
            ->where(function ($query) use ($adminId, $relatedUserIds) {
                $query->where('admin_id', $adminId)
                    ->orWhereIn('user_id', $relatedUserIds);
            });

        // Apply non-date filters
        $this->applyNonDateFilters($baseQuery);

        // Apply date filter if specified
        if ($this->startDate && $this->endDate) {
            $this->applyDateFilter($baseQuery);
        }

        Log::debug("loadTransactions: Before calculating previous balances");

        // First calculate previous balances, then get transactions
        $this->calculatePreviousBalances();

        Log::debug("loadTransactions: After calculating previous balances", [
            'previous_balances' => $this->previousBalances
        ]);

        $this->transactions = $baseQuery->orderBy('date')->get();

        Log::debug("loadTransactions: Transactions loaded", [
            'transactions_count' => $this->transactions->count()
        ]);

        $this->calculateTotalBalances();

        Log::debug("loadTransactions: Completed", [
            'total_balances' => $this->totalBalances
        ]);
    }


   public function testPreviousBalance()
{
    if (!$this->selectedCustomer) {
        dd('لطفاً ابتدا یک مشتری انتخاب کنید');
    }

    $user = Auth::guard('tools')->user();
    $adminId = $user->admin_id ?? $user->id;
    $relatedUserIds = $this->getRelatedUserIds($adminId);

    // محاسبه دستی موجودی قبلی
    $manualPreviousBalances = [];
    foreach ($this->currencies as $currency) {
        $code = $currency['code'];

        $query = Transaction::where('customer_id', $this->selectedCustomer)
            ->where('currency', $code)
            ->where(function ($q) use ($adminId, $relatedUserIds) {
                $q->where('admin_id', $adminId)
                    ->orWhereIn('user_id', $relatedUserIds);
            });

        // اگر تاریخ شروع مشخص است
        if ($this->startDate) {
            $startNormalized = $this->normalizeDate($this->startDate);
            if ($startNormalized) {
                $query->where('date', '<', $startNormalized);
            }
        } else {
            // اگر تاریخ شروع مشخص نیست، از امروز استفاده کن
            $today = Jalalian::now()->format('Y-m-d');
            $query->where('date', '<', $today);
        }

        $previousTransactions = $query->get();
        $received = $previousTransactions->where('type', 'رسید')->sum('amount');
        $spent = $previousTransactions->where('type', 'برد')->sum('amount');
        $manualPreviousBalances[$code] = $received - $spent;
    }

    $debugInfo = [
        'selected_customer' => $this->selectedCustomerName,
        'start_date' => $this->startDate,
        'calculated_previous_balances' => $this->previousBalances,
        'manual_previous_balances' => $manualPreviousBalances,
        'match' => $this->previousBalances == $manualPreviousBalances
    ];

    dd($debugInfo);
}
    /**
     * Apply non-date filters to query
     */
    private function applyNonDateFilters($query)
    {
        if (!empty($this->selectedCurrencies)) {
            $query->whereIn('currency', $this->selectedCurrencies);
        }

        if ($this->typeTransaction) {
            $query->where('type', $this->typeTransaction);
        }

        if ($this->typeTransaction2) {
            $query->where('type', $this->typeTransaction2);
        }

        if ($this->typeDocument) {
            $query->where('description', 'like', '%' . $this->typeDocument . '%');
        }

        if ($this->zone) {
            $query->where('zone', $this->zone);
        }

        if ($this->by) {
            $query->where('by', 'like', '%' . $this->by . '%');
        }

        if ($this->description) {
            $query->where('description', 'like', '%' . $this->description . '%');
        }
    }

    /**
     * Apply date filter to query
     */
    private function applyDateFilter($query)
    {
        $startNormalized = $this->normalizeDate($this->startDate);
        $endNormalized = $this->normalizeDate($this->endDate);

        if ($startNormalized && $endNormalized) {
            $query->whereBetween('date', [$startNormalized, $endNormalized]);
        }
    }

    /**
     * Set default dates (last 7 days)
     */
    private function setDefaultDates()
    {
        $today = Jalalian::now();
        $start = Jalalian::fromFormat('Y-m-d', $today->format('Y-m-d'))->subDays(7);
        $end = Jalalian::fromFormat('Y-m-d', $today->format('Y-m-d'));

        $this->startDate = $start->format('Y-m-d');
        $this->endDate = $end->format('Y-m-d');

        $this->startDateDisplay = $this->formatWithAfghanMonth($start);
        $this->endDateDisplay = $this->formatWithAfghanMonth($end);
    }

    /**
     * Format date with Afghan month names
     */
    private function formatWithAfghanMonth(Jalalian $date)
    {
        $afghanMonths = [
            'حمل',
            'ثور',
            'جوزا',
            'سرطان',
            'اسد',
            'سنبله',
            'میزان',
            'عقرب',
            'قوس',
            'جدی',
            'دلو',
            'حوت'
        ];

        return $date->getYear() . '/' .
            $afghanMonths[$date->getMonth() - 1] . '/' .
            str_pad($date->getDay(), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Set start date from datepicker
     */
    public function setStartDate($dateString)
    {
        $this->startDate = $this->normalizeDate($dateString);
        $this->updateDateDisplay('start');

        $this->calculatePreviousBalances();
        $this->loadTransactions();
    }

    /**
     * Set end date from datepicker
     */
    public function setEndDate($dateString)
    {
        $this->endDate = $this->normalizeDate($dateString);
        $this->updateDateDisplay('end');

        $this->loadTransactions();
    }

    /**
     * Update date display with Afghan month names
     */
    private function updateDateDisplay($type)
    {
        $date = $type === 'start' ? $this->startDate : $this->endDate;

        if ($date) {
            $parts = explode('-', $date);
            if (count($parts) === 3) {
                $year = $parts[0];
                $month = (int)$parts[1];
                $day = $parts[2];

                $afghanMonths = [
                    'حمل',
                    'ثور',
                    'جوزا',
                    'سرطان',
                    'اسد',
                    'سنبله',
                    'میزان',
                    'عقرب',
                    'قوس',
                    'جدی',
                    'دلو',
                    'حوت'
                ];

                $displayValue = $year . '/' . $afghanMonths[$month - 1] . '/' . $day;

                if ($type === 'start') {
                    $this->startDateDisplay = $displayValue;
                } else {
                    $this->endDateDisplay = $displayValue;
                }
            }
        }
    }


/**
 * Calculate previous balances (balance before the filter period)
 */
private function calculatePreviousBalances()
{
    if (!$this->selectedCustomer) {
        Log::debug("calculatePreviousBalances: No customer selected");
        return;
    }

    $user = Auth::guard('tools')->user();
    if (!$user) {
        Log::debug("calculatePreviousBalances: No user found");
        return;
    }

    $adminId = $user->admin_id ?? $user->id;
    $relatedUserIds = $this->getRelatedUserIds($adminId);

    // Reset previous balances
    $this->previousBalances = [];

    // دریافت ارزهای فعال برای این مشتری
    $customerCurrencies = Transaction::where('customer_id', $this->selectedCustomer)
        ->where(function ($q) use ($adminId, $relatedUserIds) {
            $q->where('admin_id', $adminId)
                ->orWhereIn('user_id', $relatedUserIds);
        })
        ->distinct()
        ->pluck('currency')
        ->toArray();

    foreach ($this->currencies as $currency) {
        $code = $currency['code'];
        
        // فقط ارزهایی که برای این مشتری تراکنش دارند را محاسبه کن
        if (!in_array($code, $customerCurrencies)) {
            continue;
        }

        // Query for all transactions
        $query = Transaction::query()
            ->where('customer_id', $this->selectedCustomer)
            ->where('currency', $code)
            ->where(function ($q) use ($adminId, $relatedUserIds) {
                $q->where('admin_id', $adminId)
                    ->orWhereIn('user_id', $relatedUserIds);
            });

        Log::debug("calculatePreviousBalances: Before date filter", [
            'customer_id' => $this->selectedCustomer,
            'currency' => $code,
            'start_date' => $this->startDate,
            'query_count' => $query->count()
        ]);

        // اگر تاریخ شروع مشخص است، تراکنش‌های قبل از تاریخ شروع را محاسبه کن
        if ($this->startDate) {
            $startNormalized = $this->normalizeDate($this->startDate);
            Log::debug("calculatePreviousBalances: Date normalization", [
                'start_date_original' => $this->startDate,
                'start_date_normalized' => $startNormalized
            ]);

            if ($startNormalized) {
                $query->where('date', '<', $startNormalized);
                
                Log::debug("calculatePreviousBalances: After date filter", [
                    'currency' => $code,
                    'start_date_normalized' => $startNormalized,
                    'filtered_count' => $query->count()
                ]);
            }
        } else {
            // اگر تاریخ شروع مشخص نیست، موجودی قبلی = مجموع تمام تراکنش‌های قبل از امروز
            $today = Jalalian::now()->format('Y-m-d');
            $query->where('date', '<', $today);
            
            Log::debug("calculatePreviousBalances: No start date - using today as boundary", [
                'currency' => $code,
                'today' => $today,
                'filtered_count' => $query->count()
            ]);
        }

        $transactionsBefore = $query->get();

        $received = $transactionsBefore->where('type', 'رسید')->sum('amount');
        $spent = $transactionsBefore->where('type', 'برد')->sum('amount');

        $this->previousBalances[$code] = $received - $spent;

        Log::debug("calculatePreviousBalances: Final calculation", [
            'currency' => $code,
            'transactions_before_count' => $transactionsBefore->count(),
            'transactions' => $transactionsBefore->map(function($t) {
                return [
                    'id' => $t->id,
                    'date' => $t->date,
                    'type' => $t->type,
                    'amount' => $t->amount
                ];
            })->values(),
            'received' => $received,
            'spent' => $spent,
            'previous_balance' => $this->previousBalances[$code]
        ]);
    }

    Log::debug("calculatePreviousBalances: Final result", [
        'all_previous_balances' => $this->previousBalances
    ]);
}

/**
 * Debug method for testing previous balances
 */
public function debugPreviousBalance()
{
    if (!$this->selectedCustomer) {
        dd('لطفاً ابتدا یک مشتری انتخاب کنید');
    }

    $user = Auth::guard('tools')->user();
    $adminId = $user->admin_id ?? $user->id;
    $relatedUserIds = $this->getRelatedUserIds($adminId);

    $debugInfo = [
        'selected_customer' => $this->selectedCustomerName,
        'customer_id' => $this->selectedCustomer,
        'start_date' => $this->startDate,
        'start_date_normalized' => $this->normalizeDate($this->startDate),
        'end_date' => $this->endDate,
    ];

    // تمام تراکنش‌های مشتری
    $allTransactions = Transaction::where('customer_id', $this->selectedCustomer)
        ->where(function ($q) use ($adminId, $relatedUserIds) {
            $q->where('admin_id', $adminId)
                ->orWhereIn('user_id', $relatedUserIds);
        })
        ->orderBy('date')
        ->get();

    $debugInfo['all_transactions'] = $allTransactions->map(function($t) {
        return [
            'id' => $t->id,
            'date' => $t->date,
            'type' => $t->type,
            'amount' => $t->amount,
            'currency' => $t->currency,
            'description' => $t->description
        ];
    });

    // محاسبه دستی موجودی قبلی برای افغانی
    $startNormalized = $this->normalizeDate($this->startDate);
    $previousQuery = Transaction::where('customer_id', $this->selectedCustomer)
        ->where('currency', 'afn')
        ->where(function ($q) use ($adminId, $relatedUserIds) {
            $q->where('admin_id', $adminId)
                ->orWhereIn('user_id', $relatedUserIds);
        });

    if ($startNormalized) {
        $previousQuery->where('date', '<', $startNormalized);
    } else {
        $today = Jalalian::now()->format('Y-m-d');
        $previousQuery->where('date', '<', $today);
    }

    $previousTransactions = $previousQuery->get();

    $debugInfo['previous_transactions'] = $previousTransactions->map(function($t) {
        return [
            'id' => $t->id,
            'date' => $t->date,
            'type' => $t->type,
            'amount' => $t->amount
        ];
    });

    $debugInfo['manual_calculation'] = [
        'transactions_count' => $previousTransactions->count(),
        'received' => $previousTransactions->where('type', 'رسید')->sum('amount'),
        'spent' => $previousTransactions->where('type', 'برد')->sum('amount'),
        'balance' => $previousTransactions->where('type', 'رسید')->sum('amount') - $previousTransactions->where('type', 'برد')->sum('amount')
    ];

    $debugInfo['calculated_previous_balance'] = $this->previousBalances['afn'] ?? 'Not calculated';

    dd($debugInfo);
}











/**
 * Calculate total balances for current period
 */
private function calculateTotalBalances()
{
    $transactions = collect($this->transactions);

    // دریافت ارزهای فعال برای این مشتری
    $user = Auth::guard('tools')->user();
    $adminId = $user->admin_id ?? $user->id;
    $relatedUserIds = $this->getRelatedUserIds($adminId);

    $customerCurrencies = Transaction::where('customer_id', $this->selectedCustomer)
        ->where(function ($q) use ($adminId, $relatedUserIds) {
            $q->where('admin_id', $adminId)
                ->orWhereIn('user_id', $relatedUserIds);
        })
        ->distinct()
        ->pluck('currency')
        ->toArray();

    foreach ($this->currencies as $currency) {
        $code = $currency['code'];
        
        // فقط ارزهایی که برای این مشتری تراکنش دارند را محاسبه کن
        if (!in_array($code, $customerCurrencies)) {
            continue;
        }

        $received = $transactions->where('currency', $code)
            ->where('type', 'رسید')
            ->sum('amount');

        $spent = $transactions->where('currency', $code)
            ->where('type', 'برد')
            ->sum('amount');

        $balance = $received - $spent;
        
        // موجودی فعلی = موجودی قبلی + (دریافت‌های دوره - برداشت‌های دوره)
        $currentBalance = ($this->previousBalances[$code] ?? 0) + $balance;

        $this->totalBalances[$code] = [
            'received' => $received,
            'spent' => $spent,
            'balance' => $balance,
            'current_balance' => $currentBalance,
            'status' => $currentBalance >= 0 ? 'طلبکار' : 'بدهکار'
        ];

        Log::debug("Total balance calculated", [
            'currency' => $code,
            'received' => $received,
            'spent' => $spent,
            'balance' => $balance,
            'previous_balance' => $this->previousBalances[$code] ?? 0,
            'current_balance' => $currentBalance
        ]);
    }
}

/**
 * Get customer's currencies from transactions
 */
private function getCustomerCurrencies()
{
    if (!$this->selectedCustomer) {
        return [];
    }

    $user = Auth::guard('tools')->user();
    $adminId = $user->admin_id ?? $user->id;
    $relatedUserIds = $this->getRelatedUserIds($adminId);

    return Transaction::where('customer_id', $this->selectedCustomer)
        ->where(function ($q) use ($adminId, $relatedUserIds) {
            $q->where('admin_id', $adminId)
                ->orWhereIn('user_id', $relatedUserIds);
        })
        ->distinct()
        ->pluck('currency')
        ->toArray();
}


    /**
     * Handle customer selection
     */
    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->selectedAccount = $customerId;
        $this->selectedCustomer = $customerId;
        $this->filteredCustomers = [];

        $customer = Customer::find($customerId);
        if ($customer) {
            $this->search = $customer->fullname;
            $this->selectedCustomerName = $customer->fullname;

            if (!$this->customers->contains('id', $customer->id)) {
                $this->customers->push($customer);
            }

            $this->calculatePreviousBalances();
            $this->loadTransactions();

            $this->dispatch('account-selected', [
                'id' => $customer->id,
                'text' => $customer->account_number . ' - ' . $customer->fullname,
            ]);
        }
    }

 
   /**
 * Generate PDF report
 */
/**
 * Generate PDF report
 */
public function print()
{
    if (!$this->selectedCustomer) {
        $this->dispatchToast('error', 'لطفاً ابتدا یک مشتری را انتخاب کنید');
        return;
    }

    try {
        $pdfData = $this->preparePdfData();
        
        // بررسی اینکه آیا تراکنشی برای نمایش وجود دارد
        $hasTransactions = count($pdfData['transactions']) > 0;
        $hasBalances = count($pdfData['balances']) > 0;
        
        if (!$hasTransactions && !$hasBalances) {
            $this->dispatchToast('warning', 'هیچ تراکنشی برای چاپ وجود ندارد');
            return;
        }

        $mpdf = $this->initializeMpdfA4();
        $html = view('pdf.Tools.transactions-report', $pdfData)->render();

        $mpdf->WriteHTML($html);

        $fileName = 'گزارش_تراکنش_' . ($pdfData['customer_name'] ?? 'report') . '_' . Jalalian::now()->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
        
    } catch (\Exception $e) {
        Log::error("TransactionsReports: print error - " . $e->getMessage(), [
            'exception' => $e,
            'customer_id' => $this->selectedCustomer,
            'trace' => $e->getTraceAsString()
        ]);
        $this->dispatchToast('error', 'خطا در تولید گزارش: ' . $e->getMessage());
    }
}

/**
 * Initialize mPDF configuration for A4
 */
private function initializeMpdfA4()
{
    return new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'orientation' => 'P', // Portrait (عمودی)
        'default_font_size' => 9,
        'default_font' => 'Shabnam',
        'directionality' => 'rtl',
        'margin_top' => 15,
        'margin_bottom' => 15,
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_header' => 5,
        'margin_footer' => 5,
        'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
            public_path('fonts'),
        ]),
        'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
            'Shabnam' => [
                'R' => 'Shabnam-FD.ttf',
                'B' => 'Shabnam-Bold-FD.ttf',
                'I' => 'Shabnam-Light-FD.ttf',
                'BI' => 'Shabnam-Medium-FD.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
        ],
        'tempDir' => storage_path('app/mpdf/tmp'),
        'autoScriptToLang' => true,
        'autoLangToFont' => true,
    ]);
}


/**
 * Prepare data for PDF generation
 */
private function preparePdfData()
{
    $customer = Customer::find($this->selectedCustomer);
    
    // دریافت ارزهای فعال برای این مشتری (همان منطق صفحه اصلی)
    $activeCurrencies = $this->getActiveCurrenciesForPdf();
    
    // محاسبه موجودی‌ها برای PDF
    $pdfBalances = [];
    $transactions = collect($this->transactions);

    foreach ($activeCurrencies as $code => $currency) {
        $pdfBalances[] = $this->calculateCurrencyBalanceForPdf($code, $transactions, $currency);
    }

    return [
        'transactions' => $this->transactions,
        'customer_name' => $this->selectedCustomerName,
        'customer' => $customer, 
        'start_date' => $this->startDateDisplay,
        'end_date' => $this->endDateDisplay,
        'active_currencies' => $activeCurrencies,
        'generated_at' => Jalalian::now()->format('Y/m/d H:i:s'),
        'filters' => [
            'type_transaction' => $this->typeTransaction,
            'type_document' => $this->typeDocument,
            'zone' => $this->zone,
            'selected_currencies' => $this->selectedCurrencies
        ],
        'balances' => $pdfBalances,
        'has_data' => count($this->transactions) > 0 || count($pdfBalances) > 0
    ];
}

/**
 * Get active currencies for PDF - only currencies that have transactions for this customer
 */
private function getActiveCurrenciesForPdf()
{
    if (!$this->selectedCustomer) {
        return [];
    }

    $user = Auth::guard('tools')->user();
    $adminId = $user->admin_id ?? $user->id;
    $relatedUserIds = $this->getRelatedUserIds($adminId);

    // دریافت تمام ارزهایی که برای این مشتری تراکنش دارند
    $customerCurrencies = Transaction::where('customer_id', $this->selectedCustomer)
        ->where(function ($q) use ($adminId, $relatedUserIds) {
            $q->where('admin_id', $adminId)
                ->orWhereIn('user_id', $relatedUserIds);
        })
        ->distinct()
        ->pluck('currency')
        ->toArray();

    $activeCurrencies = [];

    foreach ($this->currencies as $currency) {
        if (in_array($currency['code'], $customerCurrencies)) {
            $activeCurrencies[$currency['code']] = $currency;
        }
    }

    Log::debug("Active currencies for PDF", [
        'customer_id' => $this->selectedCustomer,
        'customer_currencies' => $customerCurrencies,
        'active_currencies' => array_keys($activeCurrencies)
    ]);

    return $activeCurrencies;
}

/**
 * Calculate balance for specific currency for PDF
 */
private function calculateCurrencyBalanceForPdf($code, $transactions, $currency)
{
    $received = $transactions->where('currency', $code)
        ->where('type', 'رسید')
        ->sum('amount');

    $spent = $transactions->where('currency', $code)
        ->where('type', 'برد')
        ->sum('amount');

    $balance = $received - $spent;

    $user = Auth::guard('tools')->user();
    $adminId = $user->admin_id ?? $user->id;
    $relatedUserIds = $this->getRelatedUserIds($adminId);

    // محاسبه موجودی قبلی
    $previousQuery = Transaction::where('customer_id', $this->selectedCustomer)
        ->where('currency', $code)
        ->where(function ($q) use ($adminId, $relatedUserIds) {
            $q->where('admin_id', $adminId)
                ->orWhereIn('user_id', $relatedUserIds);
        });

    if ($this->startDate) {
        $startNormalized = $this->normalizeDate($this->startDate);
        if ($startNormalized) {
            $previousQuery->where('date', '<', $startNormalized);
        }
    } else {
        $today = Jalalian::now()->format('Y-m-d');
        $previousQuery->where('date', '<', $today);
    }

    $previousTransactions = $previousQuery->get();
    $previousReceived = $previousTransactions->where('type', 'رسید')->sum('amount');
    $previousSpent = $previousTransactions->where('type', 'برد')->sum('amount');
    $previousBalance = $previousReceived - $previousSpent;

    $currentBalance = $previousBalance + $balance;

    return [
        'name_fa' => $currency['name_fa'],
        'received' => $received,
        'spent' => $spent,
        'balance' => $balance,
        'previous_balance' => $previousBalance,
        'current_balance' => $currentBalance,
        'status' => $currentBalance >= 0 ? 'طلبکار' : 'بدهکار'
    ];
}



    /**
     * Calculate balance for specific currency
     */
    private function calculateCurrencyBalance($code, $transactions)
    {
        $received = $transactions->where('currency', $code)
            ->where('type', 'رسید')
            ->sum('amount');

        $spent = $transactions->where('currency', $code)
            ->where('type', 'برد')
            ->sum('amount');

        $balance = $received - $spent;

        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = $this->getRelatedUserIds($adminId);

        $previousAll = Transaction::where('customer_id', $this->selectedCustomer)
            ->where('currency', $code)
            ->where(function ($q) use ($adminId, $relatedUserIds) {
                $q->where('admin_id', $adminId)
                    ->orWhereIn('user_id', $relatedUserIds);
            })
            ->get();

        $previous = $this->getPreviousTransactions($previousAll);
        $previousReceived = $previous->where('type', 'رسید')->sum('amount');
        $previousSpent = $previous->where('type', 'برد')->sum('amount');
        $previousBalance = $previousReceived - $previousSpent;

        $currentBalance = $previousBalance + $balance;

        return [
            'received' => $received,
            'spent' => $spent,
            'balance' => $balance,
            'previous_balance' => $previousBalance,
            'current_balance' => $currentBalance,
            'status' => $currentBalance >= 0 ? 'طلبکار' : 'بدهکار',
            'name_fa' => $this->currencies[array_search($code, array_column($this->currencies, 'code'))]['name_fa']
        ];
    }

    /**
     * Get previous transactions before start date
     */
    private function getPreviousTransactions($transactions)
    {
        if (!$this->startDate) {
            return $transactions;
        }

        $startNum = $this->normalizeDate($this->startDate);
        return $transactions->filter(function ($transaction) use ($startNum) {
            $transactionDate = $this->normalizeDate($transaction->date);
            return $transactionDate !== null && $transactionDate < $startNum;
        });
    }

    /**
     * Initialize mPDF configuration
     */
    private function initializeMpdf()
    {
        return new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 9,
            'default_font' => 'Shabnam',
            'directionality' => 'rtl',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 8,
            'margin_right' => 8,
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'Shabnam' => [
                    'R' => 'Shabnam-FD.ttf',
                    'B' => 'Shabnam-FD.ttf',
                    'I' => 'Shabnam-FD.ttf',
                    'BI' => 'Shabnam-FD.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'tempDir' => storage_path('app/mpdf/tmp'),
        ]);
    }

    /**
     * Dispatch toast notification
     */
    private function dispatchToast($type, $message)
    {
        $this->dispatch('show-toast', [
            'type' => $type,
            'message' => $message
        ]);
    }

    // Livewire property update handlers
    public function updatedSelectedCurrencies()
    {
        $this->loadTransactions();
    }
    public function updatedTypeTransaction()
    {
        $this->loadTransactions();
    }
    public function updatedTypeDocument()
    {
        $this->loadTransactions();
    }
    public function updatedZone()
    {
        $this->loadTransactions();
    }
    public function updatedBy()
    {
        $this->loadTransactions();
    }
    public function updatedDescription()
    {
        $this->loadTransactions();
    }

    public function updatedSearch($value)
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        if (empty($value)) {
            $this->selectedCustomerId = null;
            $this->filteredCustomers = [];
            return;
        }

        $this->filteredCustomers = Customer::where(function ($query) use ($value) {
            $query->where('fullname', 'like', "%{$value}%")
                ->orWhere('account_number', 'like', "%{$value}%");
        })->limit(15)->get();

        if ($this->filteredCustomers->count() === 1) {
            $this->selectCustomer($this->filteredCustomers->first()->id);
        } else {
            $this->selectedCustomerId = null;
        }
    }

    public function updatedAccountSearch($value)
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        $relatedUserIds = $this->getRelatedUserIds($adminId);

        $this->filteredCustomers = Customer::where(function ($query) use ($adminId, $relatedUserIds) {
            $query->where('admin_id', $adminId)
                ->orWhereHas('transactions', function ($t) use ($relatedUserIds) {
                    $t->whereIn('user_id', $relatedUserIds)
                        ->orWhereIn('admin_id', $relatedUserIds);
                });
        })
            ->where(function ($q) use ($value) {
                $q->where('fullname', 'like', "%{$value}%")
                    ->orWhere('account_number', 'like', "%{$value}%");
            })
            ->orderBy('fullname')
            ->limit(15)
            ->get();
    }

    /**
     * Debug method for balances
     */
    public function debugBalances()
    {
        if (!$this->selectedCustomer) {
            dd('لطفاً ابتدا یک مشتری انتخاب کنید');
        }

        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = $this->getRelatedUserIds($adminId);

        // All customer transactions
        $allTransactions = Transaction::where('customer_id', $this->selectedCustomer)
            ->where(function ($q) use ($adminId, $relatedUserIds) {
                $q->where('admin_id', $adminId)
                    ->orWhereIn('user_id', $relatedUserIds);
            })
            ->get();

        $debugInfo = [
            'selected_customer' => $this->selectedCustomerName,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'all_transactions_count' => $allTransactions->count(),
            'calculated_previous_balances' => $this->previousBalances,
            'calculated_total_balances' => $this->totalBalances,
        ];

        // Manual calculation for verification
        $manualPreviousBalances = [];
        foreach ($this->currencies as $currency) {
            $code = $currency['code'];

            // Manual previous balance calculation
            $previousQuery = Transaction::where('customer_id', $this->selectedCustomer)
                ->where('currency', $code)
                ->where(function ($q) use ($adminId, $relatedUserIds) {
                    $q->where('admin_id', $adminId)
                        ->orWhereIn('user_id', $relatedUserIds);
                });

            if ($this->startDate) {
                $startNormalized = $this->normalizeDate($this->startDate);
                if ($startNormalized) {
                    $previousQuery->where('date', '<', $startNormalized);
                }
            }

            $previousTransactions = $previousQuery->get();
            $received = $previousTransactions->where('type', 'رسید')->sum('amount');
            $spent = $previousTransactions->where('type', 'برد')->sum('amount');
            $manualPreviousBalances[$code] = $received - $spent;
        }

        $debugInfo['manual_previous_balances'] = $manualPreviousBalances;

        dd($debugInfo);
    }

    /**
     * Render component
     */
    public function render()
    {
        $this->ensureCustomersLoaded();

        $transactions = collect($this->transactions);
        $activeCurrencies = $this->getActiveCurrencies($transactions);
        $balances = $this->calculateRenderBalances($transactions, $activeCurrencies);

        return view('livewire.tools-panel.transactions-reports', [
            'customers' => $this->customers,
            'balances' => $balances,
            'active_currencies' => $activeCurrencies,
            'currencies' => collect($this->currencies),
            'transactions_count' => is_countable($this->transactions) ? count($this->transactions) : $this->transactions->count(),
            'has_filters' => $this->hasActiveFilters()
        ]);
    }

    /**
     * Ensure customers are loaded
     */
    private function ensureCustomersLoaded()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        $relatedUserIds = $this->getRelatedUserIds($adminId);

        if (!$this->customers || $this->customers->isEmpty()) {
            $this->customers = Customer::select('id', 'account_number', 'fullname')
                ->where(function ($query) use ($adminId, $relatedUserIds) {
                    $query->where('admin_id', $adminId)
                        ->orWhereHas('transactions', function ($t) use ($relatedUserIds) {
                            $t->whereIn('user_id', $relatedUserIds)
                                ->orWhereIn('admin_id', $relatedUserIds);
                        });
                })
                ->orderBy('fullname')
                ->get();

            $this->customers = collect($this->customers);
        }
    }

    /**
 * Get active currencies for display
 */
/**
 * Get active currencies for display - only currencies that have transactions for this customer
 */
private function getActiveCurrencies($transactions)
{
    $user = Auth::guard('tools')->user();
    $adminId = $user->admin_id ?? $user->id;
    $relatedUserIds = $this->getRelatedUserIds($adminId);

    // دریافت تمام ارزهایی که برای این مشتری تراکنش دارند
    $customerCurrencies = Transaction::where('customer_id', $this->selectedCustomer)
        ->where(function ($q) use ($adminId, $relatedUserIds) {
            $q->where('admin_id', $adminId)
                ->orWhereIn('user_id', $relatedUserIds);
        })
        ->distinct()
        ->pluck('currency')
        ->toArray();

    $activeCurrencies = [];

    // فقط ارزهایی که برای این مشتری تراکنش دارند را نمایش بده
    foreach ($this->currencies as $currency) {
        if (in_array($currency['code'], $customerCurrencies)) {
            $activeCurrencies[$currency['code']] = $currency;
        }
    }

    Log::debug("Active currencies for customer", [
        'customer_id' => $this->selectedCustomer,
        'customer_currencies' => $customerCurrencies,
        'active_currencies' => array_keys($activeCurrencies)
    ]);

    return $activeCurrencies;
}

    /**
 * Calculate balances for render
 */
private function calculateRenderBalances($transactions, $activeCurrencies)
{
    $balances = [];

    foreach ($activeCurrencies as $code => $currency) {
        // محاسبه تراکنش‌های دوره جاری
        $received = $transactions->where('currency', $code)
            ->where('type', 'رسید')
            ->sum('amount');

        $spent = $transactions->where('currency', $code)
            ->where('type', 'برد')
            ->sum('amount');

        $balance = $received - $spent;
        
        // استفاده از موجودی قبلی که قبلاً محاسبه شده
        $previousBalance = $this->previousBalances[$code] ?? 0;
        $currentBalance = $previousBalance + $balance;

        $balances[] = [
            'name_fa' => $currency['name_fa'],
            'code' => $code,
            'received' => $received,
            'spent' => $spent,
            'balance' => $balance,
            'previous_balance' => $previousBalance,
            'current_balance' => $currentBalance,
            'status' => $currentBalance >= 0 ? 'طلبکار' : 'بدهکار'
        ];

        Log::debug("Render balance for currency", [
            'currency' => $code,
            'received' => $received,
            'spent' => $spent,
            'previous_balance' => $previousBalance,
            'current_balance' => $currentBalance
        ]);
    }

    return $balances;
}

    /**
     * Check if any filters are active
     */
    private function hasActiveFilters()
    {
        return !empty(array_filter([
            $this->selectedCurrencies,
            $this->typeTransaction,
            $this->typeDocument,
            $this->zone,
            $this->by,
            $this->description
        ]));
    }
}
