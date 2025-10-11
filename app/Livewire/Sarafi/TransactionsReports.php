<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;

class TransactionsReports extends Component
{
    public $search = '';
    public $selectedCustomer = null;
    public $selectedCustomerName = '';
    public $startDate;
    public $endDate;
    public $startDateDisplay;
    public $endDateDisplay;
    public $customers;
    public $customer_id;
    public $selectedAccount;
    public $accountSearch = '';
    public $selectedCustomerId = null;
    public $filteredCustomers;
    public $additionalCustomers = [];

    public $currencies = [
        ['code' => 'usd', 'name_fa' => 'دالر'],
        ['code' => 'afn', 'name_fa' => 'افغانی'],
        ['code' => 'eur', 'name_fa' => 'یورو'],
        ['code' => 'irr', 'name_fa' => 'تومان'],
        ['code' => 'aed', 'name_fa' => 'درهم'],
        ['code' => 'try', 'name_fa' => 'لیره'],
    ];

    public $selectedCurrencies = [];
    public $typeTransaction = '';
    public $typeDocument = '';
    public $zone = '';
    public $by = '';
    public $description = '';

    public $transactions = [];

    public $previousBalances = [];
    public $totalBalances = [];

    public function mount()
    {
        $this->loadCustomers();
        $this->setDefaultDates();
        if ($this->selectedCustomer) {
            $this->calculatePreviousBalances();
            $this->loadTransactions();
        }

           $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->customers = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = \App\Models\Sarafi\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

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
     * normalizeDate: تبدیل هر نوع رشتهٔ تاریخ به عدد YYYYMMDD
     * - پشتیبانی از '/' و '-' و ارقام فارسی/انگلیسی
     * - خروجی integer یا null
     */
    private function normalizeDate($date)
    {
        if (!$date) return null;

        // تبدیل کاراکترهای فارسی ارقام به انگلیسی (۰۱۲۳... -> 0123...)
        $persianNums = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $latinNums   = ['0','1','2','3','4','5','6','7','8','9'];
        $date = str_replace($persianNums, $latinNums, $date);

        // استانداردسازی جداکننده‌ها
        $date = trim($date);
        $date = str_replace('/', '-', $date);
        $date = preg_replace('/\s+/', '', $date);

        $parts = explode('-', $date);
        if (count($parts) === 3) {
            [$year, $month, $day] = $parts;
            $year  = preg_replace('/\D/', '', $year);
            $month = preg_replace('/\D/', '', $month);
            $day   = preg_replace('/\D/', '', $day);

            if ($year === '' || $month === '' || $day === '') {
                return null;
            }

            $month = str_pad($month, 2, '0', STR_PAD_LEFT);
            $day   = str_pad($day,   2, '0', STR_PAD_LEFT);

            return (int) ("{$year}{$month}{$day}");
        }

        // fallback کلی: فقط ارقام را بردار و برگردان
        $digits = preg_replace('/\D/', '', $date);
        return $digits ? (int) $digits : null;
    }

    /**
     * loadTransactions
     * ----------------
     * توجه: فیلترهای غیرتاریخی را در SQL اعمال می‌کنیم، اما فیلتر تاریخ را
     * در PHP (با normalizeDate) اعمال می‌کنیم تا هرگونه فرمت یا یونیکد سازگار شود.
     */
    public function loadTransactions()
    {
        if (!$this->selectedCustomer) return;

        // ابتدا کوئری پایه با فیلترهای غیرتاریخی
        $baseQuery = Transaction::query()
            ->where('customer_id', $this->selectedCustomer);

        if (!empty($this->selectedCurrencies)) {
            $baseQuery->whereIn('currency', $this->selectedCurrencies);
        }

        if ($this->typeTransaction) {
            $baseQuery->where('type', $this->typeTransaction);
        }

        if ($this->typeDocument) {
            $baseQuery->where('description', 'like', '%' . $this->typeDocument . '%');
        }

        if ($this->zone) {
            $baseQuery->where('zone', $this->zone);
        }

        if ($this->by) {
            $baseQuery->where('by', 'like', '%' . $this->by . '%');
        }

           if ($this->description) {
            $baseQuery->where('description', 'like', '%' . $this->description . '%');
        }


        // اگر کاربر تاریخ مشخص کرده، کوئری را بدون فیلتر تاریخ بگیریم و سپس در PHP فیلتر کنیم
        if ($this->startDate && $this->endDate) {
            $rows = $baseQuery->orderBy('date')->get();

            $startNum = $this->normalizeDate($this->startDate);
            $endNum   = $this->normalizeDate($this->endDate);

            if ($startNum === null || $endNum === null) {
                // اگر یکی از تاریخ‌ها نامعتبر بود، نتیجهٔ خالی یا همهٔ ردیف‌ها را برگردان
                $this->transactions = collect([]);
                $this->calculateTotalBalances();
                $this->debugDateFilter();
                return;
            }

            // فیلتر درونی با normalizeDate خودِ سطرها
            $filtered = $rows->filter(function ($t) use ($startNum, $endNum) {
                $num = $this->normalizeDate($t->date);
                return $num !== null && $num >= $startNum && $num <= $endNum;
            })->values();

            $this->transactions = $filtered;
            $this->calculateTotalBalances();
            $this->debugDateFilter();
            return;
        }

        // اگر فیلتر تاریخی نداشته باشیم، فقط کوئری ساده را بگیریم
        $this->transactions = $baseQuery->orderBy('date')->get();
        $this->calculateTotalBalances();
        $this->debugDateFilter();
    }

    /**
     * debugDateFilter
     */
    public function debugDateFilter()
    {
        if (!$this->selectedCustomer) return;

        $startDateNumber = $this->normalizeDate($this->startDate);
        $endDateNumber   = $this->normalizeDate($this->endDate);

        Log::debug("=== TransactionsReports::debugDateFilter ===");
        Log::debug("startDate: {$this->startDate} -> {$startDateNumber}");
        Log::debug("endDate:   {$this->endDate} -> {$endDateNumber}");

        $allDates = Transaction::where('customer_id', $this->selectedCustomer)
            ->pluck('date')
            ->map(function ($d) {
                return [
                    'original' => $d,
                    'numeric'  => $this->normalizeDate($d),
                ];
            });

        foreach ($allDates as $d) {
            $inRange = ($startDateNumber && $endDateNumber && $d['numeric'] !== null && $d['numeric'] >= $startDateNumber && $d['numeric'] <= $endDateNumber) ? '✅' : '❌';
            Log::debug("{$inRange} {$d['original']} -> {$d['numeric']}");
        }

        Log::debug("Filtered count (from property): " . (is_countable($this->transactions) ? count($this->transactions) : $this->transactions->count()));
    }

    /**
     * calculatePreviousBalances
     * -------------------------
     * محاسبهٔ موجودی قبل از تاریخ شروع با فیلتر PHP (نرمال‌شده)
     */
    private function calculatePreviousBalances()
    {
        if (!$this->selectedCustomer) return;

        foreach ($this->currencies as $currency) {
            $code = $currency['code'];

            $query = Transaction::query()
                ->where('customer_id', $this->selectedCustomer)
                ->where('currency', $code);

            $all = $query->get();

            if ($this->startDate) {
                $startNum = $this->normalizeDate($this->startDate);
                if ($startNum === null) {
                    $transactionsBefore = collect([]);
                } else {
                    $transactionsBefore = $all->filter(function ($t) use ($startNum) {
                        $num = $this->normalizeDate($t->date);
                        return $num !== null && $num < $startNum;
                    });
                }
            } else {
                $transactionsBefore = $all;
            }

            $received = $transactionsBefore->where('type', 'رسید')->sum('amount');
            $spent    = $transactionsBefore->where('type', 'برد')->sum('amount');

            $this->previousBalances[$code] = $received - $spent;
        }
    }

    /**
     * calculateTotalBalances
     */
    private function calculateTotalBalances()
    {
        $transactions = collect($this->transactions);

        foreach ($this->currencies as $currency) {
            $code = $currency['code'];

            $received = $transactions->where('currency', $code)->where('type', 'رسید')->sum('amount');
            $spent    = $transactions->where('currency', $code)->where('type', 'برد')->sum('amount');

            $balance = $received - $spent;
            $currentBalance = ($this->previousBalances[$code] ?? 0) + $balance;

            $this->totalBalances[$code] = [
                'received' => $received,
                'spent' => $spent,
                'balance' => $balance,
                'current_balance' => $currentBalance,
                'status' => $currentBalance >= 0 ? 'طلبکار' : 'بدهکار'
            ];
        }
    }

    /**
     * تنظیم تاریخ‌های پیش‌فرض (۷ روزه اخیر)
     */
    private function setDefaultDates()
    {
        $today = Jalalian::now();

        // clone کردن امن
        $start = Jalalian::fromFormat('Y-m-d', $today->format('Y-m-d'))->subDays(7);
        $end = Jalalian::fromFormat('Y-m-d', $today->format('Y-m-d'));

        $this->startDate = $start->format('Y-m-d');
        $this->endDate = $end->format('Y-m-d');

        $this->startDateDisplay = $this->formatWithAfghanMonth($start);
        $this->endDateDisplay = $this->formatWithAfghanMonth($end);

        Log::debug("TransactionsReports: default dates set", ['start' => $this->startDate, 'end' => $this->endDate]);
    }

    private function formatWithAfghanMonth(Jalalian $date)
    {
        $afghanMonths = ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'];
        return $date->getYear() . '/' . $afghanMonths[$date->getMonth() - 1] . '/' . str_pad($date->getDay(), 2, '0', STR_PAD_LEFT);
    }

    public function setStartDate($dateString)
    {
        $this->startDate = str_replace('/', '-', trim($dateString));
        $this->startDateDisplay = $this->formatWithAfghanMonth(Jalalian::fromFormat('Y-m-d', $this->startDate));
        $this->calculatePreviousBalances();
        $this->loadTransactions();
    }

    public function setEndDate($dateString)
    {
        $this->endDate = str_replace('/', '-', trim($dateString));
        $this->endDateDisplay = $this->formatWithAfghanMonth(Jalalian::fromFormat('Y-m-d', $this->endDate));
        $this->loadTransactions();
    }

    public function updatedSelectedCurrencies() { $this->loadTransactions(); }
    public function updatedTypeTransaction() { $this->loadTransactions(); }
    public function updatedTypeDocument() { $this->loadTransactions(); }
    public function updatedZone() { $this->loadTransactions(); }
    public function updatedBy() { $this->loadTransactions(); }

    /**
     * print (PDF) — با استفاده از normalizeDate برای محاسبات قبلی
     */
    public function print()
    {
        if (!$this->selectedCustomer) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'لطفاً ابتدا یک مشتری را انتخاب کنید'
            ]);
            return;
        }

        if (count($this->transactions) === 0) {
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'message' => 'هیچ تراکنشی برای چاپ وجود ندارد'
            ]);
            return;
        }

        try {
            $customer = Customer::find($this->selectedCustomer);

            $defaultCurrencyCodes = ['usd', 'afn', 'irr' ,'eur'];
            $activeCurrencies = [];

            foreach ($this->currencies as $currency) {
                if (in_array($currency['code'], $defaultCurrencyCodes)) {
                    $activeCurrencies[$currency['code']] = $currency;
                }
            }

            $transactions = collect($this->transactions);
            $pdfBalances = [];

            foreach ($activeCurrencies as $code => $currency) {
                $received = $transactions->where('currency', $code)->where('type', 'رسید')->sum('amount');
                $spent = $transactions->where('currency', $code)->where('type', 'برد')->sum('amount');
                $balance = $received - $spent;

                $previousAll = Transaction::where('customer_id', $this->selectedCustomer)
                    ->where('currency', $code)
                    ->get();

                if ($this->startDate) {
                    $startNum = $this->normalizeDate($this->startDate);
                    $previous = $previousAll->filter(function ($t) use ($startNum) {
                        $n = $this->normalizeDate($t->date);
                        return $n !== null && $n < $startNum;
                    });
                } else {
                    $previous = $previousAll;
                }

                $previousReceived = $previous->where('type', 'رسید')->sum('amount');
                $previousSpent = $previous->where('type', 'برد')->sum('amount');
                $previousBalance = $previousReceived - $previousSpent;

                $currentBalance = $previousBalance + $balance;

                $pdfBalances[$code] = [
                    'received' => $received,
                    'spent' => $spent,
                    'balance' => $balance,
                    'previous_balance' => $previousBalance,
                    'current_balance' => $currentBalance,
                    'status' => $currentBalance >= 0 ? 'طلبکار' : 'بدهکار',
                    'name_fa' => $currency['name_fa']
                ];
            }

            $mpdf = new Mpdf([
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

            $mpdf->SetAutoPageBreak(true, 12);
            $mpdf->SetDisplayMode('fullpage');

            $data = [
                'transactions' => $this->transactions,
                'customer_name' => $this->selectedCustomerName,
                'customer' => $customer,
                'start_date' => $this->startDateDisplay,
                'end_date' => $this->endDateDisplay,
                'currencies' => $this->currencies,
                'active_currencies' => $activeCurrencies,
                'generated_at' => Jalalian::now()->format('Y/m/d H:i:s'),
                'filters' => [
                    'type_transaction' => $this->typeTransaction,
                    'type_document' => $this->typeDocument,
                    'zone' => $this->zone,
                    'selected_currencies' => $this->selectedCurrencies
                ],
                'balances' => $pdfBalances
            ];

            $html = view('pdf.Sarafi.transactions-report', $data)->render();
            $mpdf->WriteHTML($html);

            $fileName = 'گزارش_تراکنش_' . ($customer->fullname ?? 'report') . '.pdf';

            return response()->streamDownload(function () use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $fileName);
        } catch (\Exception $e) {
            Log::error("TransactionsReports: print error - " . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'خطا در تولید گزارش: ' . $e->getMessage()
            ]);
        }
    }


        public function updatedSearch($value)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        if (empty($value)) {
            $this->selectedCustomerId = null;
            $this->filteredCustomers = [];
            $this->updateTransactions();
            return;
        }

        $this->filteredCustomers = Customer::where(function ($query) use ($value) {
            $query->where('fullname', 'like', "%{$value}%")
                ->orWhere('account_number', 'like', "%{$value}%");
        })
            ->limit(15)
            ->get();


        if ($this->filteredCustomers->count() === 1) {
            $this->selectCustomer($this->filteredCustomers->first()->id);
        } else {
            $this->selectedCustomerId = null;
        }
    }


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

    public function updatedAccountSearch($value)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $relatedUserIds = \App\Models\Sarafi\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

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


    public function render()
    {

         $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = \App\Models\Sarafi\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

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

        $transactions = collect($this->transactions);

        $defaultCurrencies = ['usd', 'afn', 'eur' , 'irr', ];
        $activeCurrencies = [];

        foreach ($this->currencies as $currency) {
            if (in_array($currency['code'], $defaultCurrencies)) {
                $activeCurrencies[$currency['code']] = $currency;
            }
        }

        foreach ($this->currencies as $currency) {
            $code = $currency['code'];
            $hasTransactions = $transactions->where('currency', $code)->count() > 0;
            if ($hasTransactions && !in_array($code, $defaultCurrencies)) {
                $activeCurrencies[$code] = $currency;
            }
        }

        $balances = [];
        foreach ($activeCurrencies as $code => $currency) {
            $received = $transactions->where('currency', $code)->where('type', 'رسید')->sum('amount');
            $spent = $transactions->where('currency', $code)->where('type', 'برد')->sum('amount');
            $balance = $received - $spent;
            $currentBalance = ($this->previousBalances[$code] ?? 0) + $balance;

            $balances[$code] = [
                'received' => $received,
                'spent' => $spent,
                'balance' => $balance,
                'previous_balance' => $this->previousBalances[$code] ?? 0,
                'current_balance' => $currentBalance,
                'status' => $currentBalance >= 0 ? 'طلبکار' : 'بدهکار',
                'name_fa' => $currency['name_fa']
            ];
        }

        return view('livewire.sarafi.transactions-reports', [
            'customers' => $this->customers,
            'balances' => $balances,
            'active_currencies' => $activeCurrencies,
            'currencies' => collect($this->currencies),
            'transactions_count' => is_countable($this->transactions) ? count($this->transactions) : $this->transactions->count(),
            'has_filters' => !empty(array_filter([
                $this->selectedCurrencies,
                $this->typeTransaction,
                $this->typeDocument,
                $this->zone,
                $this->by,
                $this->description

            ]))
        ]);
    }

    /**
     * debugDates (کامل)
     */
    public function debugDates()
    {
        if (!$this->selectedCustomer) {
            dd('مشتری انتخاب نشده است');
        }

        $debugData = [
            'مشتری' => $this->selectedCustomerName,
            'تاریخ شروع فیلتر' => $this->startDate,
            'تاریخ پایان فیلتر' => $this->endDate,
            'تاریخ شروع نمایش' => $this->startDateDisplay,
            'تاریخ پایان نمایش' => $this->endDateDisplay,
            'تاریخ شروع (عدد)' => $this->startDate ? $this->normalizeDate($this->startDate) : null,
            'تاریخ پایان (عدد)' => $this->endDate ? $this->normalizeDate($this->endDate) : null,
        ];

        $allTransactions = Transaction::where('customer_id', $this->selectedCustomer)
            ->select('id', 'date', 'description')
            ->orderBy('date')
            ->get();

        $debugData['تمام تاریخ‌های موجود'] = $allTransactions->map(function ($t) {
            return [
                'id' => $t->id,
                'date' => $t->date,
                'date_number' => $this->normalizeDate($t->date),
                'description' => $t->description
            ];
        });

        $filteredQuery = Transaction::where('customer_id', $this->selectedCustomer);

        if ($this->startDate && $this->endDate) {
            $startDateNumber = $this->normalizeDate($this->startDate);
            $endDateNumber = $this->normalizeDate($this->endDate);

            if ($startDateNumber && $endDateNumber) {
                $filteredQuery->whereRaw("
                    CAST(REPLACE(REPLACE(date, '-', ''), '/', '') AS UNSIGNED) BETWEEN ? AND ?
                ", [$startDateNumber, $endDateNumber]);
            }
        }

        $filteredTransactions = $filteredQuery->get();

        $debugData['تراکنش‌های فیلتر شده (SQL raw)'] = $filteredTransactions->map(function ($t) {
            return [
                'id' => $t->id,
                'date' => $t->date,
                'date_number' => $this->normalizeDate($t->date),
                'description' => $t->description
            ];
        });

        $debugData['تراکنش‌های فیلتر شده (PHP filter)'] = collect($this->transactions)->map(function ($t) {
            return [
                'id' => $t->id,
                'date' => $t->date,
                'date_number' => $this->normalizeDate($t->date),
                'description' => $t->description
            ];
        });

        dd($debugData);
    }
}
