<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
    public $accountType;
    public $documentNumber;

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
        $this->generateDocumentNumber();
        $this->initializeUserCustomers();

        // Check if customer is selected from main page
        if (session()->has('selected_customer_id')) {
            $customerId = session('selected_customer_id');
            $this->selectCustomer($customerId);
            session()->forget('selected_customer_id');
        }

        // Only set end date to today if not already set
        if (!$this->endDate) {
            $todayJalali = Jalalian::now();
            $this->endDate = $todayJalali->format('Y-m-d');
            $this->updateDateDisplay('end');
        }

        // Only load transactions if customer is selected
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
    }

    /**
     * Generate document number for transactions
     */
    public function generateDocumentNumber()
    {
        $this->documentNumber = 'TR-' . date('Ymd') . '-' . rand(1000, 9999);
    }

    /**
     * Initialize customers based on user permissions
     */
    private function initializeUserCustomers()
    {
        $user = Auth::guard('sarafi')->user();
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
        $user = Auth::guard('sarafi')->user();
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
        if (!$date || trim($date) === '') return null;

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
            $this->transactions = collect();
            $this->previousBalances = [];
            $this->totalBalances = [];
            return;
        }

        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->transactions = collect();
            $this->previousBalances = [];
            $this->totalBalances = [];
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = $this->getRelatedUserIds($adminId);

        Log::debug("loadTransactions: Starting", [
            'customer_id' => $this->selectedCustomer,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'selected_currencies' => $this->selectedCurrencies
        ]);

        $baseQuery = Transaction::query()
            ->where('customer_id', $this->selectedCustomer)
            ->where(function ($query) use ($adminId, $relatedUserIds) {
                $query->where('admin_id', $adminId)
                    ->orWhereIn('user_id', $relatedUserIds);
            })
            ->whereNotNull('amount')
            ->where('amount', '>', 0);

        // Apply non-date filters
        $this->applyNonDateFilters($baseQuery);

        // Apply date filter
        $this->applyDateFilter($baseQuery);

        Log::debug("loadTransactions: Before calculating previous balances", [
            'query_count' => $baseQuery->count()
        ]);

        // First calculate previous balances
        $this->calculatePreviousBalances();

        Log::debug("loadTransactions: Previous balances calculated", [
            'previous_balances' => $this->previousBalances
        ]);

        // Get transactions
        $this->transactions = $baseQuery->orderBy('date')->get();

        Log::debug("loadTransactions: Transactions retrieved", [
            'count' => $this->transactions->count(),
            'sample' => $this->transactions->take(3)->map(function($t) {
                return [
                    'id' => $t->id,
                    'date' => $t->date,
                    'type' => $t->type,
                    'amount' => $t->amount,
                    'currency' => $t->currency
                ];
            })->values()
        ]);

        // Calculate total balances
        $this->calculateTotalBalances();

        Log::debug("loadTransactions: Total balances calculated", [
            'total_balances' => $this->totalBalances
        ]);
    }

    /**
     * Apply non-date filters to query
     */
    private function applyNonDateFilters($query)
    {
        if (!empty($this->selectedCurrencies)) {
            $query->whereIn('currency', $this->selectedCurrencies);
            Log::debug("Applied currency filter", ['currencies' => $this->selectedCurrencies]);
        }

        if ($this->typeTransaction) {
            $query->where('type', $this->typeTransaction);
            Log::debug("Applied typeTransaction filter", ['type' => $this->typeTransaction]);
        }

        if ($this->typeTransaction2) {
            $query->where('type', $this->typeTransaction2);
            Log::debug("Applied typeTransaction2 filter", ['type' => $this->typeTransaction2]);
        }

        if ($this->accountType) {
            $query->where('account_type', $this->accountType);
            Log::debug("Applied accountType filter", ['account_type' => $this->accountType]);
        }

        if ($this->typeDocument) {
            $query->where('description', 'like', '%' . $this->typeDocument . '%');
            Log::debug("Applied typeDocument filter", ['type_document' => $this->typeDocument]);
        }

        if ($this->zone) {
            $query->where('zone', $this->zone);
            Log::debug("Applied zone filter", ['zone' => $this->zone]);
        }

        if ($this->by) {
            $query->where('by', 'like', '%' . $this->by . '%');
            Log::debug("Applied by filter", ['by' => $this->by]);
        }

        if ($this->description) {
            $query->where('description', 'like', '%' . $this->description . '%');
            Log::debug("Applied description filter", ['description' => $this->description]);
        }
    }

    /**
     * Apply date filter to query
     */
    private function applyDateFilter($query)
    {
        $startNormalized = $this->normalizeDate($this->startDate);
        $endNormalized = $this->normalizeDate($this->endDate);

        Log::debug("applyDateFilter: Dates normalized", [
            'start_original' => $this->startDate,
            'end_original' => $this->endDate,
            'start_normalized' => $startNormalized,
            'end_normalized' => $endNormalized
        ]);

        if ($startNormalized && $endNormalized) {
            // Both dates selected
            $query->whereBetween('date', [$startNormalized, $endNormalized]);
            Log::debug("Applied date filter: BETWEEN", [
                'start' => $startNormalized,
                'end' => $endNormalized
            ]);
        } elseif ($startNormalized) {
            // Only start date selected
            $query->where('date', '>=', $startNormalized);
            Log::debug("Applied date filter: >= start", ['start' => $startNormalized]);
        } elseif ($endNormalized) {
            // Only end date selected
            $query->where('date', '<=', $endNormalized);
            Log::debug("Applied date filter: <= end", ['end' => $endNormalized]);
        } else {
            // No dates selected - show all
            Log::debug("No date filter applied - showing all transactions");
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
            'حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله',
            'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'
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
        Log::debug("setStartDate called", ['input' => $dateString]);
        
        if (empty($dateString) || $dateString === 'null' || $dateString === '') {
            $this->startDate = null;
            $this->startDateDisplay = '';
            Log::debug("Start date cleared");
        } else {
            $normalized = $this->normalizeDate($dateString);
            
            if ($normalized) {
                $this->startDate = $normalized;
                $this->updateDateDisplay('start');
                Log::debug("Start date updated", [
                    'normalized' => $normalized,
                    'display' => $this->startDateDisplay
                ]);
            } else {
                Log::error("Failed to normalize start date", ['input' => $dateString]);
                $this->startDate = null;
                $this->startDateDisplay = '';
            }
        }

        // Reload transactions if customer is selected
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
    }

    /**
     * Set end date from datepicker
     */
    public function setEndDate($dateString)
    {
        Log::debug("setEndDate called", ['input' => $dateString]);
        
        if (empty($dateString) || $dateString === 'null' || $dateString === '') {
            $this->endDate = null;
            $this->endDateDisplay = '';
            Log::debug("End date cleared");
        } else {
            $normalized = $this->normalizeDate($dateString);
            
            if ($normalized) {
                $this->endDate = $normalized;
                $this->updateDateDisplay('end');
                Log::debug("End date updated", [
                    'normalized' => $normalized,
                    'display' => $this->endDateDisplay
                ]);
            } else {
                Log::error("Failed to normalize end date", ['input' => $dateString]);
                $this->endDate = null;
                $this->endDateDisplay = '';
            }
        }

        // Reload transactions if customer is selected
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
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
                    'حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله',
                    'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'
                ];

                if ($month >= 1 && $month <= 12) {
                    $displayValue = $year . '/' . $afghanMonths[$month - 1] . '/' . $day;

                    if ($type === 'start') {
                        $this->startDateDisplay = $displayValue;
                    } else {
                        $this->endDateDisplay = $displayValue;
                    }
                }
            }
        } else {
            if ($type === 'start') {
                $this->startDateDisplay = '';
            } else {
                $this->endDateDisplay = '';
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
            $this->previousBalances = [];
            return;
        }

        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            Log::debug("calculatePreviousBalances: No user found");
            $this->previousBalances = [];
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = $this->getRelatedUserIds($adminId);

        $this->previousBalances = [];

        Log::debug("calculatePreviousBalances: Starting calculation", [
            'start_date' => $this->startDate,
            'customer_id' => $this->selectedCustomer
        ]);

        // If start date is empty, previous balance = 0 for all currencies
        if (!$this->startDate) {
            Log::debug("calculatePreviousBalances: startDate is empty, setting all to 0");
            foreach ($this->currencies as $currency) {
                $this->previousBalances[$currency['code']] = 0;
            }
            return;
        }

        $startNormalized = $this->normalizeDate($this->startDate);
        if (!$startNormalized) {
            Log::debug("calculatePreviousBalances: Could not normalize startDate, setting all to 0");
            foreach ($this->currencies as $currency) {
                $this->previousBalances[$currency['code']] = 0;
            }
            return;
        }

        Log::debug("calculatePreviousBalances: Calculating with startDate", [
            'start_date_original' => $this->startDate,
            'start_date_normalized' => $startNormalized
        ]);

        // Calculate previous balances for each currency
        foreach ($this->currencies as $currency) {
            $code = $currency['code'];

            $previousTransactions = Transaction::where('customer_id', $this->selectedCustomer)
                ->where('currency', $code)
                ->where(function ($q) use ($adminId, $relatedUserIds) {
                    $q->where('admin_id', $adminId)
                        ->orWhereIn('user_id', $relatedUserIds);
                })
                ->where('date', '<', $startNormalized)
                ->get();

            $received = $previousTransactions->where('type', 'رسید')->sum('amount');
            $spent = $previousTransactions->where('type', 'برد')->sum('amount');
            $balance = $received - $spent;

            $this->previousBalances[$code] = $balance;

            Log::debug("calculatePreviousBalances: Currency calculation", [
                'currency' => $code,
                'transactions_before_count' => $previousTransactions->count(),
                'received' => $received,
                'spent' => $spent,
                'previous_balance' => $balance
            ]);
        }

        Log::debug("calculatePreviousBalances: Final previous balances", $this->previousBalances);
    }

    /**
     * Calculate total balances for current period
     */
   private function calculateTotalBalances()
{
    $transactions = collect($this->transactions);

    Log::debug("calculateTotalBalances: Starting", [
        'transactions_count' => $transactions->count(),
        'previous_balances' => $this->previousBalances
    ]);

    $this->totalBalances = [];

    // Process all currencies (even those with no transactions in current period)
    foreach ($this->currencies as $currency) {
        $code = $currency['code'];
        $name_fa = $currency['name_fa'];

        // Calculate received and spent for CURRENT PERIOD ONLY
        $received = $transactions->where('currency', $code)
            ->where('type', 'رسید')
            ->sum('amount');

        $spent = $transactions->where('currency', $code)
            ->where('type', 'برد')
            ->sum('amount');

        $balance = $received - $spent; // This is ONLY for the current period

        // Get previous balance (calculated in calculatePreviousBalances())
        $previousBalance = $this->previousBalances[$code] ?? 0;
        
        // Current balance = previous balance + balance of current period
        $currentBalance = $previousBalance + $balance;

        // Always add to totalBalances if there's any data
        // (either previous balance or transactions in current period)
        if ($previousBalance != 0 || $received > 0 || $spent > 0) {
            $this->totalBalances[$code] = [
                'name_fa' => $name_fa,
                'received' => $received,
                'spent' => $spent,
                'balance' => $balance,
                'previous_balance' => $previousBalance,
                'current_balance' => $currentBalance,
                'status' => $currentBalance >= 0 ? 'طلبکار' : 'بدهکار'
            ];

            Log::debug("calculateTotalBalances: Currency {$code}", [
                'previous_balance' => $previousBalance,
                'received_in_period' => $received,
                'spent_in_period' => $spent,
                'balance_in_period' => $balance,
                'current_balance' => $currentBalance
            ]);
        }
    }

    Log::debug("calculateTotalBalances: Final result", $this->totalBalances);
}

    /**
     * Get customer's currencies from transactions
     */
    private function getCustomerCurrencies()
    {
        if (!$this->selectedCustomer) {
            return [];
        }

        $user = Auth::guard('sarafi')->user();
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
        Log::debug("selectCustomer called", ['customer_id' => $customerId]);

        $customer = Customer::find($customerId);
        if (!$customer) {
            Log::error("Customer not found", ['customer_id' => $customerId]);
            return;
        }

        $this->selectedCustomerId = $customerId;
        $this->selectedAccount = $customerId;
        $this->selectedCustomer = $customerId;
        $this->selectedCustomerName = $customer->fullname;
        $this->search = $customer->fullname;
        $this->filteredCustomers = [];

        if (!$this->customers->contains('id', $customer->id)) {
            $this->customers->push($customer);
        }

        Log::debug("Customer selected", [
            'customer_id' => $customerId,
            'customer_name' => $customer->fullname
        ]);

        // Load transactions for selected customer
        $this->loadTransactions();

        $this->dispatch('account-selected', [
            'id' => $customer->id,
            'text' => $customer->account_number . ' - ' . $customer->fullname,
        ]);
    }

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
            Log::debug("PDF Generation: Starting", [
                'customer_id' => $this->selectedCustomer,
                'customer_name' => $this->selectedCustomerName
            ]);

            $pdfData = $this->preparePdfData();

            // Log PDF data for debugging
            Log::debug("PDF Generation Data", [
                'customer_id' => $this->selectedCustomer,
                'customer_name' => $pdfData['customer_name'],
                'transactions_count' => count($pdfData['transactions']),
                'balances_count' => count($pdfData['balances']),
                'balances' => $pdfData['balances'],
                'previous_balances' => $this->previousBalances,
                'total_balances_in_component' => $this->totalBalances,
                'start_date' => $pdfData['start_date'],
                'end_date' => $pdfData['end_date']
            ]);

            // Check if there's any data to print
            if (count($pdfData['transactions']) === 0 && count($pdfData['balances']) === 0) {
                $this->dispatchToast('warning', 'هیچ تراکنشی برای چاپ وجود ندارد');
                return;
            }

            $mpdf = $this->initializeMpdfA4();
            $html = view('pdf.Sarafi.transactions-report', $pdfData)->render();

            $mpdf->WriteHTML($html);

            $fileName = 'گزارش_تراکنش_' . Str::slug($pdfData['customer_name']) . '_' . Jalalian::now()->format('Y-m-d') . '.pdf';

            Log::debug("PDF Generation: File created", ['file_name' => $fileName]);

            return response()->streamDownload(
                function () use ($mpdf) {
                    echo $mpdf->Output('', 'S');
                },
                $fileName,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
                ]
            );

        } catch (\Exception $e) {
            Log::error("PDF Generation Error: " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'customer_id' => $this->selectedCustomer
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
            'orientation' => 'P',
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
    
    if (!$customer) {
        throw new \Exception('مشتری یافت نشد');
    }

    $activeCurrencies = $this->getActiveCurrenciesForPdf();

    $pdfBalances = [];
    $transactions = collect($this->transactions);

    foreach ($activeCurrencies as $code => $currency) {
        $received = $transactions->where('currency', $code)
            ->where('type', 'رسید')
            ->sum('amount');

        $spent = $transactions->where('currency', $code)
            ->where('type', 'برد')
            ->sum('amount');

        $balance = $received - $spent;

        // استفاده از previous_balance که قبلاً در کامپوننت محاسبه شده
        $previousBalance = $this->previousBalances[$code] ?? 0;
        $currentBalance = $previousBalance + $balance;

        $pdfBalances[] = [
            'name_fa' => $currency['name_fa'],
            'previous_balance' => $previousBalance,
            'received' => $received,
            'spent' => $spent,
            'balance' => $balance,
            'current_balance' => $currentBalance,
            'status' => $currentBalance >= 0 ? 'طلبکار' : 'بدهکار'
        ];
    }

    return [
        'transactions' => $this->transactions,
        'customer_name' => $this->selectedCustomerName,
        'customer' => $customer,
        'start_date' => $this->startDateDisplay ?? '---',
        'end_date' => $this->endDateDisplay ?? '---',
        'active_currencies' => $activeCurrencies,
        'generated_at' => Jalalian::now()->format('Y/m/d H:i:s'),
        'balances' => $pdfBalances,
        'has_data' => count($this->transactions) > 0 || count($pdfBalances) > 0
    ];
}

    /**
     * Get active currencies for PDF
     */
    private function getActiveCurrenciesForPdf()
    {
        if (!$this->selectedCustomer) {
            return [];
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = $this->getRelatedUserIds($adminId);

        // Get all currencies that this customer has transactions for
        $customerCurrencies = Transaction::where('customer_id', $this->selectedCustomer)
            ->where(function ($q) use ($adminId, $relatedUserIds) {
                $q->where('admin_id', $adminId)
                    ->orWhereIn('user_id', $relatedUserIds);
            })
            ->distinct()
            ->pluck('currency')
            ->toArray();

        $activeCurrencies = [];

        // Only include currencies that the customer actually has
        foreach ($this->currencies as $currency) {
            if (in_array($currency['code'], $customerCurrencies)) {
                $activeCurrencies[$currency['code']] = $currency;
            }
        }

        return $activeCurrencies;
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
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
    }
    
    public function updatedTypeTransaction()
    {
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
    }
    
    public function updatedTypeTransaction2()
    {
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
    }
    
    public function updatedAccountType()
    {
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
    }
    
    public function updatedTypeDocument()
    {
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
    }
    
    public function updatedZone()
    {
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
    }
    
    public function updatedBy()
    {
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
    }
    
    public function updatedDescription()
    {
        if ($this->selectedCustomer) {
            $this->loadTransactions();
        }
    }

    public function updatedSearch($value)
    {
        $user = Auth::guard('sarafi')->user();
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

        if (count($this->filteredCustomers) === 1) {
            $this->selectCustomer($this->filteredCustomers[0]['id']);
        } else {
            $this->selectedCustomerId = null;
        }
    }

    public function updatedAccountSearch($value)
    {
        $user = Auth::guard('sarafi')->user();
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
     * Test method to verify calculations
     */
    public function testCalculations()
    {
        if (!$this->selectedCustomer) {
            dd('لطفاً ابتدا یک مشتری انتخاب کنید');
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = $this->getRelatedUserIds($adminId);

        $testData = [
            'customer_id' => $this->selectedCustomer,
            'customer_name' => $this->selectedCustomerName,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'normalized_start' => $this->normalizeDate($this->startDate),
            'normalized_end' => $this->normalizeDate($this->endDate),
            'transactions_in_component' => count($this->transactions),
            'previous_balances_in_component' => $this->previousBalances,
            'total_balances_in_component' => $this->totalBalances,
        ];

        // Manual calculation for verification
        $manualCalculations = [];
        foreach ($this->currencies as $currency) {
            $code = $currency['code'];
            
            // Calculate previous balance manually
            $prevQuery = Transaction::where('customer_id', $this->selectedCustomer)
                ->where('currency', $code)
                ->where(function ($q) use ($adminId, $relatedUserIds) {
                    $q->where('admin_id', $adminId)
                        ->orWhereIn('user_id', $relatedUserIds);
                });

            if ($this->startDate) {
                $startNormalized = $this->normalizeDate($this->startDate);
                if ($startNormalized) {
                    $prevQuery->where('date', '<', $startNormalized);
                }
            }

            $prevTransactions = $prevQuery->get();
            $prevReceived = $prevTransactions->where('type', 'رسید')->sum('amount');
            $prevSpent = $prevTransactions->where('type', 'برد')->sum('amount');
            $manualPrevious = $prevReceived - $prevSpent;

            // Calculate current period manually
            $currentQuery = Transaction::where('customer_id', $this->selectedCustomer)
                ->where('currency', $code)
                ->where(function ($q) use ($adminId, $relatedUserIds) {
                    $q->where('admin_id', $adminId)
                        ->orWhereIn('user_id', $relatedUserIds);
                });

            // Apply date filters
            $startNormalized = $this->normalizeDate($this->startDate);
            $endNormalized = $this->normalizeDate($this->endDate);
            
            if ($startNormalized && $endNormalized) {
                $currentQuery->whereBetween('date', [$startNormalized, $endNormalized]);
            } elseif ($startNormalized) {
                $currentQuery->where('date', '>=', $startNormalized);
            } elseif ($endNormalized) {
                $currentQuery->where('date', '<=', $endNormalized);
            }

            $currentTransactions = $currentQuery->get();
            $currentReceived = $currentTransactions->where('type', 'رسید')->sum('amount');
            $currentSpent = $currentTransactions->where('type', 'برد')->sum('amount');
            $currentBalance = $currentReceived - $currentSpent;
            $totalBalance = $manualPrevious + $currentBalance;

            $manualCalculations[$code] = [
                'manual_previous' => $manualPrevious,
                'component_previous' => $this->previousBalances[$code] ?? 0,
                'manual_current_received' => $currentReceived,
                'manual_current_spent' => $currentSpent,
                'manual_current_balance' => $currentBalance,
                'manual_total_balance' => $totalBalance,
                'component_total_balance' => $this->totalBalances[$code]['current_balance'] ?? 0,
                'match_previous' => $manualPrevious == ($this->previousBalances[$code] ?? 0),
                'match_total' => $totalBalance == ($this->totalBalances[$code]['current_balance'] ?? 0)
            ];
        }

        $testData['manual_calculations'] = $manualCalculations;
        
        dd($testData);
    }

    /**
     * Debug PDF data
     */
    public function debugPdf()
    {
        if (!$this->selectedCustomer) {
            dd('لطفاً ابتدا یک مشتری انتخاب کنید');
        }

        $pdfData = $this->preparePdfData();
        
        dd([
            'selected_customer' => $this->selectedCustomer,
            'customer_name' => $this->selectedCustomerName,
            'pdf_data' => [
                'transactions_count' => count($pdfData['transactions']),
                'balances' => $pdfData['balances'],
                'start_date' => $pdfData['start_date'],
                'end_date' => $pdfData['end_date'],
                'active_currencies' => array_keys($pdfData['active_currencies']),
            ],
            'component_data' => [
                'previous_balances' => $this->previousBalances,
                'total_balances' => $this->totalBalances,
                'transactions_count' => count($this->transactions),
            ],
            'verification' => [
                'balances_match' => $this->verifyBalancesMatch($pdfData['balances'])
            ]
        ]);
    }

    /**
     * Verify that PDF balances match component balances
     */
    private function verifyBalancesMatch($pdfBalances)
    {
        $matches = [];
        
        foreach ($pdfBalances as $pdfBalance) {
            $code = $this->getCurrencyCodeByName($pdfBalance['name_fa']);
            if ($code && isset($this->totalBalances[$code])) {
                $componentBalance = $this->totalBalances[$code];
                $matches[$code] = [
                    'pdf' => [
                        'received' => $pdfBalance['received'],
                        'spent' => $pdfBalance['spent'],
                        'balance' => $pdfBalance['balance'],
                        'previous' => $pdfBalance['previous_balance'],
                        'current' => $pdfBalance['current_balance']
                    ],
                    'component' => [
                        'received' => $componentBalance['received'],
                        'spent' => $componentBalance['spent'],
                        'balance' => $componentBalance['balance'],
                        'previous' => $componentBalance['previous_balance'],
                        'current' => $componentBalance['current_balance']
                    ],
                    'matches' => [
                        'received' => $pdfBalance['received'] == $componentBalance['received'],
                        'spent' => $pdfBalance['spent'] == $componentBalance['spent'],
                        'balance' => $pdfBalance['balance'] == $componentBalance['balance'],
                        'previous' => $pdfBalance['previous_balance'] == $componentBalance['previous_balance'],
                        'current' => $pdfBalance['current_balance'] == $componentBalance['current_balance']
                    ]
                ];
            }
        }
        
        return $matches;
    }

    /**
     * Get currency code by Persian name
     */
    private function getCurrencyCodeByName($name_fa)
    {
        foreach ($this->currencies as $currency) {
            if ($currency['name_fa'] == $name_fa) {
                return $currency['code'];
            }
        }
        return null;
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

        return view('livewire.sarafi.transactions-reports', [
            'customers' => $this->customers,
            'balances' => $balances,
            'active_currencies' => $activeCurrencies,
            'currencies' => collect($this->currencies),
            'transactions_count' => count($this->transactions),
            'has_filters' => $this->hasActiveFilters()
        ]);
    }

    /**
     * Ensure customers are loaded
     */
    private function ensureCustomersLoaded()
    {
        $user = Auth::guard('sarafi')->user();
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
    private function getActiveCurrencies($transactions)
    {
        if (!$this->selectedCustomer) {
            return [];
        }

        // Use totalBalances to determine active currencies
        $activeCurrencies = [];
        
        foreach ($this->totalBalances as $code => $balanceData) {
            $currencyInfo = collect($this->currencies)->firstWhere('code', $code);
            if ($currencyInfo) {
                $activeCurrencies[$code] = $currencyInfo;
            }
        }

        return $activeCurrencies;
    }

    /**
     * Calculate balances for render
     */
   private function calculateRenderBalances($transactions, $activeCurrencies)
{
    $balances = [];

    // Use totalBalances for rendering
    foreach ($this->totalBalances as $code => $balanceData) {
        $balances[] = [
            'name_fa' => $balanceData['name_fa'],
            'code' => $code,
            'previous_balance' => $balanceData['previous_balance'],
            'received' => $balanceData['received'],
            'spent' => $balanceData['spent'],
            'balance' => $balanceData['balance'],
            'current_balance' => $balanceData['current_balance'],
            'status' => $balanceData['status']
        ];
    }

    return $balances;
}
    // در کلاس TransactionsReports این متد را اضافه کنید:

/**
 * Generate PDF report for summary only
 */
/**
 * Generate PDF report for summary table
 */
public function printSummary()
{
    if (!$this->selectedCustomer) {
        $this->dispatchToast('error', 'لطفاً ابتدا یک مشتری را انتخاب کنید');
        return;
    }

    if (empty($this->totalBalances)) {
        $this->dispatchToast('warning', 'هیچ موجودی فعالی برای چاپ وجود ندارد');
        return;
    }

    try {
        Log::debug("PDF Summary Generation: Starting", [
            'customer_id' => $this->selectedCustomer,
            'customer_name' => $this->selectedCustomerName,
            'balances_count' => count($this->totalBalances)
        ]);

        $pdfData = $this->prepareSummaryPdfData();
        
        $mpdf = $this->initializeMpdfA4();
        $html = view('pdf.Sarafi.summary-report', $pdfData)->render();

        $mpdf->WriteHTML($html);

        $fileName = 'خلاصه_موجودی_' . Str::slug($pdfData['customer_name']) . '_' . Jalalian::now()->format('Y-m-d') . '.pdf';

        Log::debug("PDF Summary Generation: File created", ['file_name' => $fileName]);

        return response()->streamDownload(
            function () use ($mpdf) {
                echo $mpdf->Output('', 'S');
            },
            $fileName,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ]
        );

    } catch (\Exception $e) {
        Log::error("PDF Summary Generation Error: " . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString(),
            'customer_id' => $this->selectedCustomer
        ]);
        $this->dispatchToast('error', 'خطا در تولید گزارش خلاصه: ' . $e->getMessage());
    }
}

/**
 * Prepare data for summary PDF generation
 */
private function prepareSummaryPdfData()
{
    $customer = Customer::find($this->selectedCustomer);
    
    if (!$customer) {
        throw new \Exception('مشتری یافت نشد');
    }

    // Prepare full balances data for summary table
    $summaryBalances = [];
    $totalPrevious = 0;
    $totalReceived = 0;
    $totalSpent = 0;
    $totalBalance = 0;
    $totalCurrent = 0;
    
    foreach ($this->totalBalances as $code => $balanceData) {
        $summaryBalances[] = [
            'name_fa' => $balanceData['name_fa'],
            'previous_balance' => $balanceData['previous_balance'],
            'received' => $balanceData['received'],
            'spent' => $balanceData['spent'],
            'balance' => $balanceData['balance'],
            'current_balance' => $balanceData['current_balance'],
            'status' => $balanceData['status']
        ];
        
        $totalPrevious += $balanceData['previous_balance'];
        $totalReceived += $balanceData['received'];
        $totalSpent += $balanceData['spent'];
        $totalBalance += $balanceData['balance'];
        $totalCurrent += $balanceData['current_balance'];
    }

    // Sort by currency name
    usort($summaryBalances, function($a, $b) {
        return strcmp($a['name_fa'], $b['name_fa']);
    });

    Log::debug("Summary PDF Data Prepared", [
        'customer_id' => $this->selectedCustomer,
        'customer_name' => $this->selectedCustomerName,
        'balances_count' => count($summaryBalances),
        'totals' => [
            'previous' => $totalPrevious,
            'received' => $totalReceived,
            'spent' => $totalSpent,
            'balance' => $totalBalance,
            'current' => $totalCurrent
        ]
    ]);
    
    return [
        'customer_name' => $this->selectedCustomerName,
        'customer' => $customer,
        'balances' => $summaryBalances,
        'generated_at' => Jalalian::now()->format('Y/m/d H:i:s'),
        'start_date' => $this->startDateDisplay ?: '---',
        'end_date' => $this->endDateDisplay ?: '---',
        'totals' => [
            'previous' => $totalPrevious,
            'received' => $totalReceived,
            'spent' => $totalSpent,
            'balance' => $totalBalance,
            'current' => $totalCurrent
        ]
    ];
}
/**
 * Prepare data for summary PDF generation
 */


    /**
     * Check if any filters are active
     */
    private function hasActiveFilters()
    {
        return !empty(array_filter([
            $this->selectedCurrencies,
            $this->typeTransaction,
            $this->typeTransaction2,
            $this->typeDocument,
            $this->zone,
            $this->by,
            $this->description,
            $this->startDate,
            $this->endDate,
            $this->accountType
        ]));
    }
}