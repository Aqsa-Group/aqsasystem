<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\Journals;
use App\Models\Sarafi\SafeDeal;
use App\Models\Sarafi\SafeDealsRevenue;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use NumberFormatter;
use Picqer\Barcode\BarcodeGeneratorPNG;


class SafeDeals extends Component
{
    use WithPagination, WithFileUploads;

    public $dealId;
    public $from;
    public $to;
    public $transactionType = 'بانکی';

    public $from_currency = '';
    public $to_currency = '';
    public $withdraw_amount = '';
    public $currency_rate = '';
    public $receive_amount = '';
    public $date = '';
    public $description = '';
    public $customer_id = null;
    public $selectedAccount;
    public $selectedCustomer = null;
    public $customers;

    // نمایش حروفی
    public $withdrawalAmountInWords = '';
    public $receivedAmountInWords = '';
    public $currencyRateInWords = '';
    public $calculatingField = null;

    // جستجو و فیلتر
    public $search = '';
    public $selectedCustomerId = null;
    public $searchedCustomer = null;
    public $showCustomerModal = false;
    public $filteredCustomers = [];
    public $transactions = [];

    public $additionalCustomers = [];
    public $accountSearch = '';

    public $customerCashBalances = [];
    public $customerBankBalances = [];
    public $customerTotalBalances = [];

    // ارزهای موجود
    public $currencies = [
        ['code' => 'usd', 'name_fa' => 'دالر'],
        ['code' => 'afn', 'name_fa' => 'افغانی'],
        ['code' => 'irr', 'name_fa' => 'تومان'],
        ['code' => 'eur', 'name_fa' => 'یورو'],
        ['code' => 'pkr', 'name_fa' => 'کلدار'],
        ['code' => 'aed', 'name_fa' => 'درهم'],
        ['code' => 'try', 'name_fa' => 'لیره'],
        ['code' => 'cny', 'name_fa' => 'یوان'],
        ['code' => 'inr', 'name_fa' => 'روپیه'],
        ['code' => 'gbp', 'name_fa' => 'پوند'],
        ['code' => 'jpy', 'name_fa' => 'ین'],
        ['code' => 'sar', 'name_fa' => 'ریال سعودی'],
    ];

    public $confirmDeleteId = null;
    public $originalDealData = null;

    // متغیرهای کش
    protected $cacheKeys = [
        'customer_list' => 'customers_list_safe_',
        'deals_list' => 'safe_deals_list_',
    ];

    protected $rules = [
        'from' => 'required|string|max:255',
        'to' => 'required|string|max:255',
        'from_currency' => 'required|string|max:10',
        'to_currency' => 'required|string|max:10',
        'withdraw_amount' => 'required|numeric|min:0',
        'currency_rate' => 'required|numeric|min:0',
        'receive_amount' => 'required|numeric|min:0',
        'date' => 'required|date',
        'description' => 'nullable|string',
        'customer_id' => 'nullable|exists:sarafi.customers,id',
    ];

    protected $listeners = ['customerSelected' => 'selectCustomer'];

    public function render()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // ساختن query برای معاملات
        $query = SafeDeal::with('customer')
            ->where('admin_id', $adminId)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        // فیلتر بر اساس مشتری
        if ($this->selectedCustomerId) {
            $query->where('customer_id', $this->selectedCustomerId);
        }

        // جستجو
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('from', 'like', '%' . $this->search . '%')
                    ->orWhere('to', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('fullname', 'like', '%' . $this->search . '%')
                            ->orWhere('account_number', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $deals = $query->paginate(10);

        // مشتری انتخاب شده
        $selectedCustomer = $this->selectedCustomerId
            ? Customer::find($this->selectedCustomerId)
            : null;

        return view('livewire.sarafi.safe-deals', [
            'deals' => $deals,
            'customers' => $this->customers,
            'selectedCustomer' => $selectedCustomer,
            'filteredCustomers' => $this->filteredCustomers,
        ]);
    }

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');

        $this->currencies = [
            ['code' => 'usd', 'name_fa' => 'دالر'],
            ['code' => 'afn', 'name_fa' => 'افغانی'],
            ['code' => 'eur', 'name_fa' => 'یورو'],
            ['code' => 'irr', 'name_fa' => 'تومان'],
            ['code' => 'aed', 'name_fa' => 'درهم'],
            ['code' => 'try', 'name_fa' => 'لیره'],
            ['code' => 'cny', 'name_fa' => 'یوان'],
            ['code' => 'pkr', 'name_fa' => 'کلدار'],
            ['code' => 'gbp', 'name_fa' => 'پوند'],
            ['code' => 'jpy', 'name_fa' => 'ین'],
            ['code' => 'sar', 'name_fa' => 'ریال سعودی'],
            ['code' => 'inr', 'name_fa' => 'روپیه'],
        ];

        // مقداردهی اولیه متغیرهای حروفی
        $this->withdrawalAmountInWords = '';
        $this->receivedAmountInWords = '';
        $this->currencyRateInWords = '';

        $this->loadCustomers();

        if ($this->selectedCustomerId) {
            $this->updateCustomerCurrencyBalance();
        }

        $this->syncFromTo();
    }



    public function calculateWithdrawAmount()
    {
        if ($this->receive_amount && $this->currency_rate && $this->from_currency && $this->to_currency) {
            $fromCurrency = $this->from_currency;
            $toCurrency = $this->to_currency;

            $amount = floatval(str_replace(',', '', $this->receive_amount));
            $rate = floatval(str_replace(',', '', $this->currency_rate));

            if ($rate == 0) {
                $this->withdraw_amount = '';
                $this->withdrawalAmountInWords = '';
                return;
            }

            // موارد خاص افغانی و تومان
            if ($fromCurrency === 'afn' && $toCurrency === 'irr') {
                // فرمول اصلی: receive = (withdraw * 1000) / rate
                // پس: withdraw = (receive * rate) / 1000
                $calculatedAmount = ($amount * $rate) / 1000;
            } elseif ($fromCurrency === 'irr' && $toCurrency === 'afn') {
                // فرمول اصلی: receive = (withdraw * rate) / 1000
                // پس: withdraw = (receive * 1000) / rate
                $calculatedAmount = ($amount * 1000) / $rate;
            } else {
                $shouldDivide = $this->shouldUseDivision($fromCurrency, $toCurrency);
                if ($shouldDivide) {
                    // اصلی: تقسیم → حالا: withdraw = receive * rate
                    $calculatedAmount = $amount * $rate;
                } else {
                    // اصلی: ضرب → حالا: withdraw = receive / rate
                    $calculatedAmount = $amount / $rate;
                }
            }

            $calculatedAmount = round($calculatedAmount, 2);
            $this->withdraw_amount = number_format($calculatedAmount, 2, '.', '');
            $this->convertAmountToWords($this->withdraw_amount, 'withdrawalAmountInWords');
        } else {
            $this->withdraw_amount = '';
            $this->withdrawalAmountInWords = '';
        }
    }

    private function recalculateBasedOnLastField()
    {
        if ($this->calculatingField === 'receive' && $this->receive_amount) {
            $this->calculateWithdrawAmount();
        } elseif ($this->withdraw_amount) {
            $this->calculateReceiveAmount();
        }
    }
    public function updatedAccountSearch($value)
    {
        $this->searchCustomers($value);
    }


    private function loadCustomers()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->customers = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        $cacheKey = $this->cacheKeys['customer_list'] . $adminId;

        // استفاده از کش برای لیست مشتریان
        $this->customers = Cache::remember($cacheKey, 300, function () use ($adminId) {
            return Customer::with('admins')
                ->where(function ($query) use ($adminId) {
                    $query->where('admin_id', $adminId)
                        ->orWhereHas('admins', function ($q) use ($adminId) {
                            $q->where('customer_admin.admin_id', $adminId);
                        });
                })
                ->orderBy('fullname')
                ->get(['id', 'account_number', 'fullname', 'phone', 'image']);
        });

        $this->customers = collect($this->customers);
    }

    public function updatedSearch($value)
    {
        if (empty($value)) {
            $this->selectedCustomerId = null;
            $this->filteredCustomers = [];
            $this->searchedCustomer = null;
            return;
        }

        $this->searchCustomers($value);

        if ($this->filteredCustomers->isEmpty()) {
            $this->searchedCustomer = Customer::where('fullname', 'like', "%{$value}%")
                ->orWhere('account_number', 'like', "%{$value}%")
                ->first();

            if ($this->searchedCustomer) {
                $this->showCustomerModal = true;
            }
        } elseif ($this->filteredCustomers->count() === 1) {
            $this->selectCustomer($this->filteredCustomers->first()->id);
        } else {
            $this->selectedCustomerId = null;
        }
    }


    public function setCalculatingField($field)
    {
        if ($field === 'sell') {
            $field = 'receive';
        }
        $this->calculatingField = $field;
    }

    
    private function searchCustomers($value)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $this->filteredCustomers = Customer::with('admins')
            ->where(function ($query) use ($adminId) {
                $query->where('admin_id', $adminId)
                    ->orWhereHas('admins', function ($q) use ($adminId) {
                        $q->where('customer_admin.admin_id', $adminId);
                    });
            })
            ->where(function ($query) use ($value) {
                $query->where('fullname', 'like', "%{$value}%")
                    ->orWhere('account_number', 'like', "%{$value}%");
            })
            ->limit(15)
            ->get(['id', 'account_number', 'fullname', 'phone', 'image']);
    }

    public function addCustomerToAdmin($customerId)
    {
        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            $customer = Customer::find($customerId);

            if (!$customer) {
                session()->flash('error', 'مشتری یافت نشد!');
                return;
            }

            $customer->admin_id = $adminId;
            $customer->save();

            if (!$this->customers->contains('id', $customer->id)) {
                $this->customers->push($customer);
            }

            $this->selectCustomer($customerId);
            $this->showCustomerModal = false;
            $this->searchedCustomer = null;

            // پاک کردن کش
            Cache::forget($this->cacheKeys['customer_list'] . $adminId);

            session()->flash('message', 'مشتری با موفقیت به حساب شما اضافه شد.');

            Log::info("Customer added to admin", [
                'customer_id' => $customerId,
                'admin_id' => $adminId,
                'customer_name' => $customer->fullname
            ]);
        } catch (\Exception $e) {
            Log::error("Error adding customer to admin: " . $e->getMessage());
            session()->flash('error', 'خطا در ثبت مشتری: ' . $e->getMessage());
        }
    }

    public function cancelAddCustomer()
    {
        $this->showCustomerModal = false;
        $this->searchedCustomer = null;
        $this->search = '';
    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->customer_id = $customerId;
        $this->selectedAccount = $customerId;
        $this->selectedCustomer = Customer::find($customerId);
        $this->filteredCustomers = [];

        if ($this->selectedCustomer) {
            $this->search = $this->selectedCustomer->fullname;

            if (!$this->customers->contains('id', $this->selectedCustomer->id)) {
                $this->customers->push($this->selectedCustomer);
            }

            $this->dispatch('account-selected', [
                'id' => $this->selectedCustomer->id,
                'text' => $this->selectedCustomer->account_number . ' - ' . $this->selectedCustomer->fullname,
            ]);

            $this->updateCustomerCurrencyBalance();

            Log::debug("Customer selected", [
                'customer_id' => $customerId,
                'customer_name' => $this->selectedCustomer->fullname,
                'search_value' => $this->search
            ]);
        }
    }

    public function updatedSelectedAccount($value)
    {
        if ($value) {
            $this->selectCustomer($value);
        }
    }

    public function goToCustomers()
    {
        $this->dispatch('redirectToCustomers');
    }

    public function updateCustomerCurrencyBalance()
    {
        if (!$this->selectedCustomerId) {
            $this->resetBalances();
            return;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        list($cashBalances, $bankBalances) = $this->calculateBalances($adminId);
        $totalBalances = $this->calculateTotalBalances($cashBalances, $bankBalances);
        $totalInUsd = $this->convertToUsd($totalBalances);

        $this->setCurrencyDefaults($totalBalances, $totalInUsd);
        $this->setCustomerBalances($cashBalances, $bankBalances, $totalBalances);
    }

    public $currenciesdefault = [
        ['name' => 'افغانی', 'value' => 0],
        ['name' => 'دالر', 'value' => 0],
        ['name' => 'تومان', 'value' => 0],
        ['name' => 'یورو', 'value' => 0],
        ['name' => 'کلدار', 'value' => 0],
        ['name' => 'درهم', 'value' => 0],
        ['name' => 'لیره', 'value' => 0],
        ['name' => 'یوان', 'value' => 0],
        ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
    ];

    public function toggleTransactionType()
    {
        $this->transactionType =
            $this->transactionType === 'بانکی' ? 'نقدی' : 'بانکی';

        $this->syncFromTo();
    }

    private function syncFromTo()
    {
        if ($this->transactionType === 'بانکی') {
            $this->from = 'بانکی';
            $this->to = 'نقدی';
        } else {
            $this->from = 'نقدی';
            $this->to = 'بانکی';
        }
    }

    private function resetBalances()
    {
        $currencies = [
            'افغانی',
            'دالر',
            'تومان',
            'یورو',
            'کلدار',
            'درهم',
            'لیره',
            'یوان',
            'روپیه',
            'خلاصه بیلانس به دالر'
        ];

        $this->currenciesdefault = [];
        foreach ($currencies as $currency) {
            $this->currenciesdefault[] = ['name' => $currency, 'value' => 0];
        }

        $this->customerCashBalances = [];
        $this->customerBankBalances = [];
        $this->customerTotalBalances = [];
    }

    private function calculateBalances($adminId)
    {
        // استفاده از aggregate functions برای محاسبه سریع‌تر
        $cashTransactions = Transaction::where('customer_id', $this->selectedCustomerId)
            ->where('admin_id', $adminId)
            ->where('account_type', 'نقدی')
            ->whereIn('type', ['برد', 'رسید'])
            ->selectRaw('currency, SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance')
            ->groupBy('currency')
            ->get();

        $bankTransactions = Transaction::where('customer_id', $this->selectedCustomerId)
            ->where('admin_id', $adminId)
            ->where('account_type', 'بانکی')
            ->whereIn('type', ['برد', 'رسید'])
            ->selectRaw('currency, SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance')
            ->groupBy('currency')
            ->get();

        $cashBalances = array_fill_keys([
            'افغانی',
            'دالر',
            'تومان',
            'یورو',
            'کلدار',
            'درهم',
            'لیره',
            'یوان',
            'روپیه'
        ], 0);

        $bankBalances = array_fill_keys([
            'افغانی',
            'دالر',
            'تومان',
            'یورو',
            'کلدار',
            'درهم',
            'لیره',
            'یوان',
            'روپیه'
        ], 0);

        foreach ($cashTransactions as $transaction) {
            $currencyName = $this->getCurrencyName($transaction->currency);
            if (isset($cashBalances[$currencyName])) {
                $cashBalances[$currencyName] += $transaction->balance;
            }
        }

        foreach ($bankTransactions as $transaction) {
            $currencyName = $this->getCurrencyName($transaction->currency);
            if (isset($bankBalances[$currencyName])) {
                $bankBalances[$currencyName] += $transaction->balance;
            }
        }

        return [$cashBalances, $bankBalances];
    }

    private function calculateTotalBalances($cashBalances, $bankBalances)
    {
        $totalBalances = [];
        foreach ($cashBalances as $currency => $balance) {
            $totalBalances[$currency] = $balance + ($bankBalances[$currency] ?? 0);
        }
        return $totalBalances;
    }

    private function convertToUsd($totalBalances)
    {
        // استفاده از کش برای نرخ‌های ارز
        $latestExchangeRate = Cache::remember('latest_exchange_rate', 300, function () {
            return ExchangeRates::latest()->first();
        });

        if (!$latestExchangeRate) {
            return 0;
        }

        $exchangeRates = [
            'افغانی' => $latestExchangeRate->afn_buy ?? 66.20,
            'دالر' => 1,
            'تومان' => $latestExchangeRate->irr_buy ?? 110000.00,
            'یورو' => $latestExchangeRate->eur_buy ?? 70.00,
            'کلدار' => $latestExchangeRate->pkr_buy ?? 32.00,
            'درهم' => $latestExchangeRate->aed_buy ?? 44.00,
            'لیره' => $latestExchangeRate->try_buy ?? 60.00,
            'یوان' => $latestExchangeRate->cny_buy ?? 43.00,
            'روپیه' => 7.14,
        ];

        $totalInUsd = 0;
        foreach ($totalBalances as $currency => $balance) {
            if (isset($exchangeRates[$currency]) && $exchangeRates[$currency] > 0) {
                $totalInUsd += $balance / $exchangeRates[$currency];
            }
        }

        return $totalInUsd;
    }

    private function setCurrencyDefaults($totalBalances, $totalInUsd)
    {
        $this->currenciesdefault = [
            ['name' => 'افغانی', 'value' => $totalBalances['افغانی'] ?? 0],
            ['name' => 'دالر', 'value' => $totalBalances['دالر'] ?? 0],
            ['name' => 'تومان', 'value' => $totalBalances['تومان'] ?? 0],
            ['name' => 'یورو', 'value' => $totalBalances['یورو'] ?? 0],
            ['name' => 'کلدار', 'value' => $totalBalances['کلدار'] ?? 0],
            ['name' => 'درهم', 'value' => $totalBalances['درهم'] ?? 0],
            ['name' => 'لیره', 'value' => $totalBalances['لیره'] ?? 0],
            ['name' => 'یوان', 'value' => $totalBalances['یوان'] ?? 0],
            ['name' => 'روپیه', 'value' => $totalBalances['روپیه'] ?? 0],
            ['name' => 'خلاصه بیلانس به دالر', 'value' => $totalInUsd],
        ];
    }

    private function setCustomerBalances($cashBalances, $bankBalances, $totalBalances)
    {
        $this->customerCashBalances = $cashBalances;
        $this->customerBankBalances = $bankBalances;
        $this->customerTotalBalances = $totalBalances;
    }

    private function getCurrencyName($currencyCode)
    {
        $currencyMap = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'irr' => 'تومان',
            'eur' => 'یورو',
            'pkr' => 'کلدار',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'gbp' => 'پوند',
            'jpy' => 'ین',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه',
        ];

        return $currencyMap[$currencyCode] ?? $currencyCode;
    }

    // پاک کردن فیلترها
    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedCustomerId = null;
        $this->customer_id = null;
        $this->filteredCustomers = [];
        $this->searchedCustomer = null;
        $this->showCustomerModal = false;
    }

    // محاسبه خودکار مبلغ دریافتی
    public function updatedWithdrawAmount($value)
    {
        $this->calculateReceiveAmount();

        // تبدیل مبلغ برداشت به حروف
        $this->convertAmountToWords($value, 'withdrawalAmountInWords');
    }

    public function updatedCurrencyRate($value)
    {
        $this->recalculateBasedOnLastField();
        $this->convertAmountToWords($value, 'currencyRateInWords', 4);
    }



    public function updatedReceiveAmount($value)
    {
        // تبدیل مبلغ دریافتی به حروف
        $this->convertAmountToWords($value, 'receivedAmountInWords');
    }

    public function updatedFromCurrency($value)
    {
        $this->recalculateBasedOnLastField();
    }

    public function updatedToCurrency($value)
    {
        $this->recalculateBasedOnLastField();
    }


    public function calculateReceiveAmount()
    {
        if ($this->withdraw_amount && $this->currency_rate && $this->from_currency && $this->to_currency) {
            $fromCurrency = $this->from_currency;
            $toCurrency = $this->to_currency;

            // تبدیل مقادیر به float
            $amount = floatval(str_replace(',', '', $this->withdraw_amount));
            $rate = floatval(str_replace(',', '', $this->currency_rate));

            // جلوگیری از تقسیم بر صفر
            if ($rate == 0) {
                $this->receive_amount = '';
                $this->receivedAmountInWords = '';
                return;
            }

            // محاسبه بر اساس ارزها
            if ($fromCurrency === 'afn' && $toCurrency === 'irr') {
                // افغانی → تومان: (مبلغ × 1000) ÷ نرخ ارز
                $calculatedAmount = ($amount * 1000) / $rate;
            } elseif ($fromCurrency === 'irr' && $toCurrency === 'afn') {
                // تومان → افغانی: (مبلغ × نرخ ارز) ÷ 1000
                $calculatedAmount = ($amount * $rate) / 1000;
            } else {
                // سایر ارزها
                $shouldDivide = $this->shouldUseDivision($fromCurrency, $toCurrency);
                if ($shouldDivide) {
                    $calculatedAmount = $amount / $rate;
                } else {
                    $calculatedAmount = $amount * $rate;
                }
            }

            // محدود کردن به 2 رقم اعشار
            $calculatedAmount = round($calculatedAmount, 2);

            // ذخیره در receive_amount
            $this->receive_amount = number_format($calculatedAmount, 2, '.', '');

            // تبدیل مبلغ دریافتی به حروف
            $this->convertAmountToWords($this->receive_amount, 'receivedAmountInWords');
        } else {
            $this->receive_amount = '';
            $this->receivedAmountInWords = '';
        }
    }

    /**
     * تعیین می‌کند که آیا برای محاسبه باید از تقسیم استفاده کرد
     */
    private function shouldUseDivision($fromCurrency, $toCurrency)
    {
        // اگر ارز مقصد افغانی یا تومان باشد، تقسیم انجام می‌شود
        // همچنین برای محاسبه USD به سایر ارزها
        if ($fromCurrency === 'usd') {
            // از USD به سایر ارزها: ضرب
            return false;
        } elseif ($toCurrency === 'usd') {
            // از سایر ارزها به USD: تقسیم
            return true;
        } elseif (in_array($toCurrency, ['afn', 'irr'])) {
            // به افغانی یا تومان: تقسیم
            return true;
        } else {
            // سایر موارد: ضرب
            return false;
        }
    }

    /**
     * تبدیل عدد به حروف فارسی - نسخه بهبود یافته
     */
    private function convertAmountToWords($value, $property, $decimals = 2)
    {
        if ($value && $value !== '' && is_numeric(str_replace(',', '', $value))) {
            try {
                // حذف کاماها و تبدیل به عدد
                $numericValue = floatval(str_replace(',', '', $value));

                // اگر عدد صفر باشد، حروف صفر نمایش داده شود
                if ($numericValue == 0) {
                    $this->$property = 'صفر';
                    return;
                }

                // گرد کردن به تعداد اعشار مورد نظر
                $roundedValue = round($numericValue, $decimals);

                // جدا کردن قسمت صحیح و اعشار
                $parts = explode('.', (string)$roundedValue);
                $integerPart = intval($parts[0]);

                // قسمت اعشار
                $fractionPart = '';
                if (isset($parts[1])) {
                    // حذف صفرهای اضافی در سمت راست
                    $fractionPart = rtrim($parts[1], '0');
                }

                // استفاده از NumberFormatter فارسی
                $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);

                // تبدیل قسمت صحیح به حروف
                $integerWords = $formatter->format($integerPart);

                // تبدیل قسمت اعشار به حروف
                $fractionWords = '';
                if ($fractionPart !== '' && intval($fractionPart) > 0) {
                    // اضافه کردن صفرهای سمت چپ اگر نیاز باشد
                    $fractionPart = str_pad($fractionPart, $decimals, '0', STR_PAD_RIGHT);
                    $fractionWords = $formatter->format(intval($fractionPart));
                }

                // ترکیب کلمات
                $words = $integerWords;
                if ($fractionWords !== '') {
                    $words .= ' ممیز ' . $fractionWords;
                }

                // تصحیح برخی کلمات
                $replacements = [
                    'دویست' => 'دوصد',
                    'سیصد' => 'سه‌صد',
                    'پانصد' => 'پانصد',
                    'هشتصد' => 'هشتصد',
                    'نهصد' => 'نه‌صد',
                    'و ممیز' => ' ممیز', // حذف "و" قبل از ممیز
                    '  ' => ' ', // حذف فاصله‌های اضافی
                ];

                $words = str_replace(array_keys($replacements), array_values($replacements), $words);

                // اضافه کردن واحد بر اساس نوع فیلد
                switch ($property) {
                    case 'withdrawalAmountInWords':
                        $currency = $this->getCurrencyName($this->from_currency);
                        $words .= ' ' . $currency;
                        break;
                    case 'receivedAmountInWords':
                        $currency = $this->getCurrencyName($this->to_currency);
                        $words .= ' ' . $currency;
                        break;
                    case 'currencyRateInWords':
                        // برای نرخ ارز، واحد خاصی اضافه نمی‌شود
                        $words .= ' واحد';
                        break;
                }

                $this->$property = $words;

                // لاگ برای دیباگ
                Log::debug("مقدار {$value} به حروف تبدیل شد: {$words}", [
                    'property' => $property,
                    'numeric_value' => $numericValue,
                    'integer_part' => $integerPart,
                    'fraction_part' => $fractionPart
                ]);
            } catch (\Exception $e) {
                Log::error('خطا در تبدیل مقدار به حروف: ' . $e->getMessage(), [
                    'value' => $value,
                    'property' => $property
                ]);
                $this->$property = '';
            }
        } else {
            $this->$property = '';
        }
    }


    /**
     * بروزرسانی صندوق‌ها (نقدی و بانکی) برای معامله
     */
    private function updateSafes(
        $fromType,
        $fromCurrency,
        $withdrawAmount,
        $toType,
        $toCurrency,
        $receiveAmount,
        $dealId,
        $isEdit = false,
        $originalData = null
    ) {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $fromCurrency = $this->convertCurrencyNameToCode($fromCurrency);
        $toCurrency   = $this->convertCurrencyNameToCode($toCurrency);

        if ($isEdit && $originalData) {

            // حذف کامل revenue های قبلی
            SafeDealsRevenue::where('safe_deals_id', $dealId)->delete();

            // بازگرداندن صندوق‌ها
            $this->reverseSafeUpdate($originalData, $adminId);

            // ثبت جدید (مثل create)
            $this->updateSafeBalance(
                $fromType,
                $fromCurrency,
                $withdrawAmount,
                'decrease',
                $dealId,
                $user->id,
                $adminId
            );

            $this->updateSafeBalance(
                $toType,
                $toCurrency,
                $receiveAmount,
                'increase',
                $dealId,
                $user->id,
                $adminId
            );

            return;
        }

        // حالت ثبت جدید
        $this->updateSafeBalance(
            $fromType,
            $fromCurrency,
            $withdrawAmount,
            'decrease',
            $dealId,
            $user->id,
            $adminId
        );

        $this->updateSafeBalance(
            $toType,
            $toCurrency,
            $receiveAmount,
            'increase',
            $dealId,
            $user->id,
            $adminId
        );
    }

    /**
     * بازگرداندن تغییرات صندوق در حالت ویرایش
     */
    private function reverseSafeUpdate($originalData, $adminId)
    {
        $user = Auth::guard('sarafi')->user();

        // بازگرداندن صندوق مبدا (افزایش مبلغ)
        $this->updateSafeBalance(
            $originalData['from'],
            $originalData['from_currency'],
            $originalData['withdraw_amount'],
            'increase',
            $originalData['id'],
            $user->id,
            $adminId,
            true
        );

        // بازگرداندن صندوق مقصد (کاهش مبلغ)
        $this->updateSafeBalance(
            $originalData['to'],
            $originalData['to_currency'],
            $originalData['receive_amount'],
            'decrease',
            $originalData['id'],
            $user->id,
            $adminId,
            true
        );

        Log::info('موجودی صندوق‌ها برای معامله ویرایش شده بازگردانی شد', [
            'deal_id' => $originalData['id'],
            'from' => $originalData['from'],
            'from_currency' => $originalData['from_currency'],
            'to' => $originalData['to'],
            'to_currency' => $originalData['to_currency']
        ]);
    }

    /**
     * بروزرسانی موجودی یک صندوق
     */
    private function updateSafeBalance(
        $accountType,   // 'نقدی' یا 'بانکی'
        $currency,      // کد انگلیسی ارز
        $amount,
        $operation,     // 'increase' یا 'decrease'
        $dealId,
        $userId,
        $adminId,
        $isReversal = false
    ) {
        // انتخاب مدل بر اساس نوع حساب
        if ($accountType === 'نقدی') {
            $safe = CurrencySafe::where('admin_id', $adminId)->first();
            $modelName = 'CurrencySafe';
        } else {
            $safe = BankAccount::where('admin_id', $adminId)->first();
            $modelName = 'BankAccount';
        }

        // ایجاد صندوق در صورت عدم وجود
        if (!$safe) {
            $safe = $accountType === 'نقدی'
                ? CurrencySafe::create(['admin_id' => $adminId, 'user_id' => $userId])
                : BankAccount::create(['admin_id' => $adminId, 'user_id' => $userId]);
        }

        // بررسی فیلد ارز
        if (!isset($safe->{$currency})) {
            throw new \Exception("فیلد ارز {$currency} در مدل {$modelName} وجود ندارد");
        }

        $oldBalance = $safe->{$currency} ?? 0;

        // بررسی موجودی کافی قبل از کاهش (فقط در عملیات عادی کاهش)
        if ($operation === 'decrease' && !$isReversal && $oldBalance < $amount) {
            throw new \Exception("موجودی کافی نیست. موجودی فعلی {$currency}: {$oldBalance}");
        }

        // بروزرسانی موجودی
        $safe->{$currency} = $operation === 'increase'
            ? $oldBalance + $amount
            : $oldBalance - $amount;

        $safe->save();

        // ثبت درآمد معامله در جدول safe_deals_revenue
        $type = $operation === 'increase' ? 'رسید' : 'برد';
        $this->recordSafeDealRevenue(
            $dealId,
            $currency,
            $amount,
            $type,
            $accountType,
            $userId,
            $adminId,
            $isReversal
        );

        Log::info('موجودی صندوق بروزرسانی شد', [
            'model' => $modelName,
            'currency' => $currency,
            'old_balance' => $oldBalance,
            'new_balance' => $safe->{$currency},
            'operation' => $operation,
            'is_reversal' => $isReversal
        ]);
    }


    /**
     * تبدیل نام فارسی ارز به کد انگلیسی برای دیتابیس
     */
    private function convertCurrencyNameToCode($currencyName)
    {
        $currencyMap = [
            'افغانی' => 'afn',
            'دالر' => 'usd',
            'تومان' => 'irr',
            'یورو' => 'eur',
            'کلدار' => 'pkr',
            'درهم' => 'aed',
            'لیره' => 'try',
            'یوان' => 'cny',
            'پوند' => 'gbp',
            'ین' => 'jpy',
            'ریال سعودی' => 'sar',
            'روپیه' => 'inr',
        ];

        return $currencyMap[$currencyName] ?? $currencyName;
    }

    /**
     * ثبت درآمد معاملات صندوق
     */
    private function recordSafeDealRevenue(
        $dealId,
        $currency,
        $amount,
        $type,
        $accountType,
        $userId,
        $adminId,
        $isReversal = false
    ) {
        // اگر در حال معکوس کردن هستیم، قبلی را حذف می‌کنیم
        if ($isReversal) {
            SafeDealsRevenue::where('safe_deals_id', $dealId)
                ->where('currency', $currency)
                ->where('type', $type)
                ->where('account_type', $accountType)
                ->delete();

            Log::info('رکورد درآمد معامله برای معکوس کردن حذف شد', [
                'deal_id' => $dealId,
                'currency' => $currency,
                'type' => $type
            ]);
            return;
        }

        // در حالت عادی، رکورد جدید ثبت می‌کنیم
        SafeDealsRevenue::create([
            'user_id' => $userId,
            'admin_id' => $adminId,
            'safe_deals_id' => $dealId,
            'currency' => $currency,
            'amount' => $amount,
            'type' => $type,
            'account_type' => $accountType,
            'date' => $this->date,
            'description' => $this->description . ' - ' . ($type === 'رسید' ? 'دریافت به ' : 'برداشت از ') . $accountType
        ]);

        Log::info('ثبت درآمد معامله صندوق', [
            'deal_id' => $dealId,
            'currency' => $currency,
            'amount' => $amount,
            'type' => $type,
            'account_type' => $accountType
        ]);
    }

    /**
     * تبدیل کد ارز انگلیسی به نام فارسی
     */
    private function getCurrencyFaName($currencyCode)
    {
        $currencyMap = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'irr' => 'تومان',
            'eur' => 'یورو',
            'pkr' => 'کلدار',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'gbp' => 'پوند',
            'jpy' => 'ین',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه',
        ];

        return $currencyMap[$currencyCode] ?? $currencyCode;
    }

    /**
     * ثبت تراکنش برای مشتری در معاملات صندوقی
     */
    private function recordCustomerTransaction(
        $customerId,
        $fromType,
        $fromCurrency,
        $withdrawAmount,
        $toCurrency,
        $receiveAmount,
        $currencyRate,
        $dealId,
        $userId,
        $adminId,
        $isEdit = false
    ) {
        if (!$customerId) return;

        // اگر در حالت ویرایش هستیم، ابتدا تراکنش قبلی را حذف می‌کنیم
        if ($isEdit) {
            Transaction::where('safe_deal_id', $dealId)
                ->where('customer_id', $customerId)
                ->delete();

            Log::info('تراکنش قبلی مشتری برای ویرایش حذف شد', [
                'deal_id' => $dealId,
                'customer_id' => $customerId
            ]);
        }

        // تعیین نوع تراکنش بر اساس from
        $type = ($fromType === 'بانکی') ? 'برد' : 'رسید';

        // تعیین ارز و مبلغ برای ثبت
        $transactionCurrency = ($fromType === 'بانکی') ? $fromCurrency : $toCurrency;
        $transactionAmount = ($fromType === 'بانکی') ? $withdrawAmount : $receiveAmount;

        // تعیین account_type برای مشتری
        $customerAccountType = 'بانکی'; // فرض می‌کنیم مشتری فقط حساب بانکی دارد

        // تبدیل کد ارز به نام فارسی برای توضیحات
        $fromCurrencyName = $this->getCurrencyFaName($fromCurrency);
        $toCurrencyName = $this->getCurrencyFaName($toCurrency);

        // ایجاد توضیحات
        $description = ' برداشت مبلغ ' . number_format($withdrawAmount, 2) . ' ' . $fromCurrencyName .
            ' و خرید مبلغ ' . number_format($receiveAmount, 2) . ' ' . $toCurrencyName .
            ' به نرخ ' . number_format($currencyRate, 4);

        // کاربر جاری
        $user = Auth::guard('sarafi')->user();

        Transaction::create([
            'customer_id' => $customerId,
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'currency' => $transactionCurrency, // کد انگلیسی
            'amount' => $transactionAmount,
            'type' => $type,
            'date' => $this->date,
            'zone' => 'صندوق',
            'description' => $description,
            'by' => $user->name ?? 'سیستم',
            'account_type' => $customerAccountType,
            'safe_deal_id' => $dealId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('تراکنش مشتری ثبت شد', [
            'customer_id' => $customerId,
            'type' => $type,
            'currency' => $transactionCurrency,
            'amount' => $transactionAmount,
            'deal_id' => $dealId,
            'is_edit' => $isEdit
        ]);
    }

    /**
     * ایجاد توضیحات برای تراکنش
     */
    private function createTransactionDescription(
        $fromType,
        $fromCurrency,
        $withdrawAmount,
        $toCurrency,
        $receiveAmount,
        $currencyRate
    ) {
        $fromCurrencyName = $this->getCurrencyFaName($fromCurrency);
        $toCurrencyName = $this->getCurrencyFaName($toCurrency);

        return ' برداشت مبلغ ' . number_format($withdrawAmount, 2) . ' ' . $fromCurrencyName .
            ' و خرید مبلغ ' . number_format($receiveAmount, 2) . ' ' . $toCurrencyName .
            ' به نرخ ' . number_format($currencyRate, 4);
    }

    // ثبت معامله
    public function submitDeal()
    {
        $this->validate();
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        try {
            DB::beginTransaction();

            // ثبت یا بروزرسانی معامله
            $isEdit = !empty($this->dealId);

            if ($isEdit) {
                $originalDeal = SafeDeal::find($this->dealId);
                $this->originalDealData = [
                    'id' => $originalDeal->id,
                    'from' => $originalDeal->from,
                    'to' => $originalDeal->to,
                    'from_currency' => $originalDeal->from_currency,
                    'to_currency' => $originalDeal->to_currency,
                    'withdraw_amount' => $originalDeal->withdraw_amount,
                    'receive_amount' => $originalDeal->receive_amount,
                ];
                $deal = $originalDeal;
                $deal->update([
                    'from' => $this->from,
                    'to' => $this->to,
                    'from_currency' => $this->from_currency,
                    'to_currency' => $this->to_currency,
                    'withdraw_amount' => $this->withdraw_amount,
                    'currency_rate' => $this->currency_rate,
                    'receive_amount' => $this->receive_amount,
                    'date' => $this->date,
                    'description' => $this->description,
                    'customer_id' => $this->customer_id,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                ]);
                $message = 'معامله با موفقیت بروزرسانی شد.';
            } else {
                $deal = SafeDeal::create([
                    'from' => $this->from,
                    'to' => $this->to,
                    'from_currency' => $this->from_currency,
                    'to_currency' => $this->to_currency,
                    'withdraw_amount' => $this->withdraw_amount,
                    'currency_rate' => $this->currency_rate,
                    'receive_amount' => $this->receive_amount,
                    'date' => $this->date,
                    'description' => $this->description,
                    'customer_id' => $this->customer_id,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                ]);
                $this->dealId = $deal->id;
                $message = 'معامله با موفقیت ثبت شد.';
            }

            // به‌روزرسانی صندوق‌ها و تراکنش مشتری
            $this->updateSafes(...);
            if ($this->customer_id) {
                $this->recordCustomerTransaction(...);
            }

            DB::commit();

            // پاک کردن کش و بروزرسانی بیلانس مشتری
            Cache::forget($this->cacheKeys['deals_list'] . $adminId);
            if ($this->customer_id) {
                Cache::forget($this->cacheKeys['deals_list'] . $adminId . '_' . $this->customer_id);
                $this->updateCustomerCurrencyBalance();
            }

            session()->flash('message', $message);
            $this->resetForm();

            // **اینجا PDF را بساز و چاپ را فراخوانی کن**
            $this->generateDealPDF($deal, 'نسخه بایگانی');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('خطا در ثبت معامله: ' . $e->getMessage());
            session()->flash('error', 'خطا در ثبت معامله: ' . $e->getMessage());
        }

        // ثبت ژورنال
        $this->createSafeJournal($deal);
    }

    private function generateDealPDF(SafeDeal $deal, $copyType = 'نسخه بایگانی')
    {
        $generator = new BarcodeGeneratorPNG();
        $barcodeImage = base64_encode(
            $generator->getBarcode($deal->id, $generator::TYPE_CODE_128)
        );

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [72.1, 297],
            'directionality' => 'rtl',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
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

        $mpdf->SetAutoPageBreak(false);

        $html = view('pdf.Sarafi.safe-deal', [
            'deal' => $deal,
            'isShort' => true,
            'barcodeImage' => $barcodeImage,
            'copyType' => $copyType,
        ])->render();

        $mpdf->WriteHTML($html);

        $fileName = 'safe_deal_' . $deal->id . '.pdf';
        $path = storage_path('app/public/' . $fileName);

        $mpdf->Output($path, 'F');

        $this->dispatch('print-pdf', url: asset('storage/' . $fileName));
    }


    private function createSafeJournal(SafeDeal $deal)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        /*
        |--------------------------------------------------------------------------
        | تعیین نوع ژورنال و مقدار (فقط نمایشی)
        |--------------------------------------------------------------------------
        */
        if ($deal->from === 'نقدی') {
            $type     = 'برد';
            $amount   = $deal->withdraw_amount;
            $currency = $deal->from_currency;
        } else {
            $type     = 'رسید';
            $amount   = $deal->receive_amount;
            $currency = $deal->to_currency;
        }

        /*
        |--------------------------------------------------------------------------
        | فقط خواندن موجودی فعلی صندوق (بدون محاسبه)
        |--------------------------------------------------------------------------
        */
        $safe = CurrencySafe::where('admin_id', $adminId)
            ->lockForUpdate()
            ->first();

        if (!$safe || !isset($safe->{$currency})) {
            throw new \Exception("ارز {$currency} در صندوق یافت نشد");
        }

        $currentBalance = $safe->{$currency}; // ✅ همین، بدون دست‌کاری

        /*
        |--------------------------------------------------------------------------
        | ثبت ژورنال بدون تغییر صندوق
        |--------------------------------------------------------------------------
        */
        Journals::create([
            'safe_deal_id' => $deal->id,
            'user_id'      => $user->id,
            'admin_id'     => $adminId,
            'currency'     => $currency,
            'type'         => $type,
            'account_type' => 'نقدی',
            'amount'       => $amount,
            'balance'      => 0,
            'safe_balance' => $currentBalance,
            'description'  => $deal->description,
            'date'         => $deal->date,
        ]);

        Log::info('Safe journal created (read-only safe)', [
            'deal_id'  => $deal->id,
            'type'     => $type,
            'currency' => $currency,
            'safe_balance' => $currentBalance,
        ]);
    }





    // ویرایش معامله
    public function edit($id)
    {
        $deal = SafeDeal::findOrFail($id);

        $this->dealId = $deal->id;
        $this->from = $deal->from;
        $this->to = $deal->to;
        $this->from_currency = $deal->from_currency;
        $this->to_currency = $deal->to_currency;
        $this->withdraw_amount = $deal->withdraw_amount;
        $this->currency_rate = $deal->currency_rate;
        $this->receive_amount = $deal->receive_amount;
        $this->date = $deal->date;
        $this->description = $deal->description;
        $this->customer_id = $deal->customer_id;
        $this->selectedCustomerId = $deal->customer_id;

        $this->convertAmountToWords($this->withdraw_amount, 'withdrawalAmountInWords');
        $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords', 4);
        $this->convertAmountToWords($this->receive_amount, 'receivedAmountInWords');

        if ($this->customer_id) {
            $this->search = $deal->customer->fullname ?? '';
        }
    }

    public function showReport()
    {
        if (!$this->selectedCustomerId) {
            session()->flash('error', 'لطفاً ابتدا یک مشتری را انتخاب کنید');
            return;
        }

        session(['selected_customer_id' => $this->selectedCustomerId]);

        return redirect()->route('sarafi.transaction-reports');
    }

    // حذف معامله
    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        if ($this->confirmDeleteId) {
            try {
                DB::beginTransaction();

                $deal = SafeDeal::find($this->confirmDeleteId);

                if (!$deal) {
                    session()->flash('error', 'معامله یافت نشد.');
                    return;
                }

                // 1. بازگرداندن موجودی صندوق‌ها
                $this->reverseDealSafes($deal);

                // 2. حذف رکوردهای مربوطه در SafeDealsRevenue
                SafeDealsRevenue::where('safe_deals_id', $this->confirmDeleteId)->delete();

                // 3. حذف تراکنش‌های مربوطه
                Transaction::where('safe_deal_id', $this->confirmDeleteId)->delete();

                // 4. حذف معامله
                $deal->delete();

                DB::commit();

                // پاک کردن کش
                $user = Auth::guard('sarafi')->user();
                $adminId = $user->admin_id ?? $user->id;
                Cache::forget($this->cacheKeys['deals_list'] . $adminId);
                if ($deal->customer_id) {
                    Cache::forget($this->cacheKeys['deals_list'] . $adminId . '_' . $deal->customer_id);
                }

                session()->flash('message', 'معامله با موفقیت حذف شد و موجودی صندوق‌ها بازگردانی شد.');
                $this->confirmDeleteId = null;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('خطا در حذف معامله: ' . $e->getMessage());
                session()->flash('error', 'خطا در حذف معامله: ' . $e->getMessage());
            }
        }
    }

    /**
     * بازگرداندن موجودی صندوق‌ها هنگام حذف معامله
     */
    private function reverseDealSafes($deal)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // بازگرداندن صندوق مبدا (افزایش مبلغ)
        $this->updateSafeBalance(
            $deal->from,
            $deal->from_currency,
            $deal->withdraw_amount,
            'increase',
            $deal->id,
            $user->id,
            $adminId,
            true
        );

        // بازگرداندن صندوق مقصد (کاهش مبلغ)
        $this->updateSafeBalance(
            $deal->to,
            $deal->to_currency,
            $deal->receive_amount,
            'decrease',
            $deal->id,
            $user->id,
            $adminId,
            true
        );

        Log::info('موجودی صندوق‌ها برای معامله حذف شده بازگردانی شد', [
            'deal_id' => $deal->id,
            'from' => $deal->from,
            'from_currency' => $deal->from_currency,
            'to' => $deal->to,
            'to_currency' => $deal->to_currency
        ]);
    }

    // چاپ معامله
    public function print($dealId)
    {
        $deal = SafeDeal::with(['customer', 'user', 'journal'])->findOrFail($dealId);

        $generator = new BarcodeGeneratorPNG();
        $barcodeImage = base64_encode(
            $generator->getBarcode($deal->id, $generator::TYPE_CODE_128)
        );

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [72.1, 297],
            'directionality' => 'rtl',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
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

        $mpdf->SetAutoPageBreak(false);

        $html = view('pdf.Sarafi.safe-deal', [
            'deal'         => $deal,
            'isShort'      => true,
            'barcodeImage' => $barcodeImage,
            'copyType'     => 'نسخه بایگانی',
        ])->render();

        $mpdf->WriteHTML($html);

        $fileName = 'safe_deal_' . $deal->id . '.pdf';
        $path = storage_path('app/public/' . $fileName);

        $mpdf->Output($path, 'F');

        $this->dispatch('print-pdf', url: asset('storage/' . $fileName));
    }


    // پاک کردن فرم
    public function resetForm()
    {
        $this->reset([
            'dealId',
            'from',
            'to',
            'from_currency',
            'to_currency',
            'withdraw_amount',
            'currency_rate',
            'receive_amount',
            'date',
            'description',
            'customer_id',
            'withdrawalAmountInWords',
            'receivedAmountInWords',
            'currencyRateInWords',
            'originalDealData'
        ]);
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->selectedCustomerId = null;
        $this->customer_id = null;
        $this->search = '';
        $this->filteredCustomers = [];
        $this->selectedCustomer = null;

        // بازنشانی متغیرهای حروفی
        $this->withdrawalAmountInWords = '';
        $this->receivedAmountInWords = '';
        $this->currencyRateInWords = '';
        $this->calculatingField = null;
    }

    // لغو ویرایش
    public function cancel()
    {
        $this->resetForm();
    }
}
