<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use NumberFormatter;

class Transactions extends Component
{
    use WithFileUploads;

    public $searchedCustomer = null;
    public $showCustomerModal = false;
    public $confirmDeleteId = null;

    public $customer_id;
    public $selectedAccount;
    public $byUser;
    public $currency;
    public $currencies = [];
    public $amount;
    public $amountInWords;
    public $customers;
    public $transactionType = 'رسید';
    public $accountType = 'نقدی';
    public $date;
    public $description;
    public $file;

    public $zone;
    public $by;
    public $transactionId;

    public $search = '';
    public $selectedCustomer = null;
    public $selectedCustomerId = null;
    public $filteredCustomers = [];

    public $transactions = [];

    public $additionalCustomers = [];
    public $accountSearch = '';

    public $customerCashBalances = [];
    public $customerBankBalances = [];
    public $customerTotalBalances = [];

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

    // متغیرهای کش
    protected $cacheKeys = [
        'customer_list' => 'customers_list_',
        'transactions_list' => 'transactions_list_',
    ];

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->zone = Auth::guard('sarafi')->user()->zone;

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

        $this->loadCustomers();
        $this->updateTransactions();
        
        if ($this->selectedCustomerId) {
            $this->updateCustomerCurrencyBalance();
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
            $this->updateTransactions();
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
            $this->updateTransactions();
        }
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

            $this->updateTransactions();
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

    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->search = '';
        $this->selectedCustomer = null;
        $this->filteredCustomers = [];
        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->filteredCustomers = [];
        $this->updateCustomerCurrencyBalance();
    }

    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->filteredCustomers = [];
        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance();
    }

    public function updateTransactions()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->transactions = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        $cacheKey = $this->cacheKeys['transactions_list'] . $adminId . ($this->selectedCustomerId ? '_' . $this->selectedCustomerId : '');

        $query = Transaction::with(['customer' => function ($query) {
                $query->select('id', 'fullname', 'account_number', 'phone');
            }])
            ->where('admin_id', $adminId)
            ->whereIn('type', ['برد', 'رسید'])
            ->whereNull('conversion_transfer_id')
            ->whereNull('conversion_in_account_id')
            ->whereNull('account_to_id')
            ->whereNull('remittance_id')
            ->whereNull('withdrawbank_id')
            ->whereNull('changerdeal_id');

        if ($this->selectedCustomerId) {
            $query->where('customer_id', $this->selectedCustomerId);
        }

        // برای نمایش اولیه، محدودیت 50 رکورد
        $this->transactions = Cache::remember($cacheKey, 60, function () use ($query) {
            return $query->latest()->limit(50)->get();
        });
    }

    public function render()
    {
        return view('livewire.sarafi.transactions', [
            'customers' => $this->customers,
            'transactions' => $this->transactions,
            'customerCashBalances' => $this->customerCashBalances,
            'customerBankBalances' => $this->customerBankBalances,
            'customerTotalBalances' => $this->customerTotalBalances,
        ]);
    }

    public function updatedAmount($value)
    {
        $number = preg_replace('/[^\d]/', '', $value);
        $this->amount = $number;

        if ($number > 0) {
            $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
            $words = $formatter->format($number);
            $words = str_replace(['دویست', 'سیصد', 'پانصد'], ['دوصد', 'سه صد', 'پنجصد'], $words);
            $this->amountInWords = $words;
        } else {
            $this->amountInWords = null;
        }
    }

    public function toggleTransactionType()
    {
        $this->transactionType = $this->transactionType === 'رسید' ? 'برد' : 'رسید';
    }

    public function toggleAccountType()
    {
        $this->accountType = $this->accountType === 'نقدی' ? 'بانکی' : 'نقدی';
    }

    public function setDefaultZone()
    {
        if (empty($this->zone)) {
            $this->zone = Auth::guard('sarafi')->user()->zone;
        }
    }

    public function formatAmount()
    {
        if ($this->amount) {
            $this->amount = number_format((int)$this->amount);
        }
    }

    public function edit($id)
    {
        $transaction = Transaction::with('customer')->findOrFail($id);
        
        $this->transactionId = $id;
        $this->selectedAccount = $transaction->customer_id;
        $this->amount = $transaction->amount;
        $this->currency = $transaction->currency;
        $this->byUser = $transaction->by;
        $this->zone = $transaction->zone;
        $this->date = $transaction->date;
        $this->description = $transaction->description;
        $this->transactionType = $transaction->type;
        $this->accountType = $transaction->account_type;
        
        $this->selectCustomer($transaction->customer_id);
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        $transaction = Transaction::findOrFail($this->confirmDeleteId);

        $user = Auth::guard('sarafi')->user();
        $this->applyCurrencyChange($user, $transaction->currency, $transaction->amount, $transaction->type, $transaction->account_type, true);

        $transaction->delete();

        // پاک کردن کش
        $adminId = $user->admin_id ?? $user->id;
        Cache::forget($this->cacheKeys['transactions_list'] . $adminId);
        Cache::forget($this->cacheKeys['transactions_list'] . $adminId . '_' . $transaction->customer_id);

        session()->flash('message', 'ترانزکشن موفقیتانه حذف گردید.');

        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance();

        $this->confirmDeleteId = null;
    }

    public function submitTransaction()
    {
        $this->selectedAccount = (int) $this->selectedAccount;
        $this->amount = str_replace(',', '', $this->amount);
        $user = Auth::guard('sarafi')->user();

        $this->validate([
            'selectedAccount' => 'required|integer|exists:sarafi.customers,id',
            'byUser'         => 'nullable|string|max:255',
            'currency'       => 'required|string',
            'amount'         => 'required|numeric|min:1',
            'transactionType' => 'required|string',
            'accountType' => 'required|string',
            'date'           => 'required|date',
            'description'    => 'nullable|string|max:500',
            'zone'           => 'required|string',
            'file'           => 'nullable|file|max:10240',
        ]);

        $filePath = $this->file ? $this->file->store('transactions', 'public') : null;
        $adminId = $user->admin_id ?? $user->id;

        $data = [
            'customer_id'      => $this->selectedAccount,
            'user_id'          => $user->id,
            'admin_id'         => $adminId,
            'currency'         => $this->currency,
            'amount'           => $this->amount,
            'type'             => $this->transactionType,
            'account_type'     => $this->accountType,
            'date'             => $this->date,
            'description'      => $this->description,
            'zone'             => $this->zone,
            'transaction_file' => $filePath,
            'by'               => $this->byUser,
        ];

        if ($this->transactionId) {
            $old = Transaction::findOrFail($this->transactionId);

            $this->applyCurrencyChange($user, $old->currency, $old->amount, $old->type, $old->account_type, true);
            $old->update($data);
            $this->applyCurrencyChange($user, $this->currency, $this->amount, $this->transactionType, $this->accountType);

            $transaction = $old;
            $message = 'تراکنش با موفقیت بروزرسانی شد.';
        } else {
            $transaction = Transaction::create($data);
            $this->applyCurrencyChange($user, $this->currency, $this->amount, $this->transactionType, $this->accountType);
            $message = 'تراکنش با موفقیت ثبت شد.';
        }

        // پاک کردن کش
        Cache::forget($this->cacheKeys['transactions_list'] . $adminId);
        Cache::forget($this->cacheKeys['transactions_list'] . $adminId . '_' . $this->selectedAccount);

        session()->flash('message', $message);
        
        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance();
        $this->resetForm();
        
        return $transaction;
    }

    public function submitAndPrint()
    {
        $transaction = $this->submitTransaction();
        
        if (!$transaction) {
            return;
        }

        $html = view('pdf.Sarafi.transaction', ['transaction' => $transaction])->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 250],
            'directionality' => 'rtl',
            'margin_top' => 2,
            'margin_bottom' => 2,
            'margin_left' => 2,
            'margin_right' => 2,
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'Shabnam' => [
                    'R' => 'Shabnam-FD.ttf',
                ],
            ],
            'default_font' => 'Shabnam',
        ]);

        $mpdf->SetAutoPageBreak(false);
        $mpdf->WriteHTML($html);

        $fileName = 'ترانزکشن_شماره_' . $transaction->id . '_به_اسم_' . $transaction->customer->fullname . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    public function print($transactionId)
    {
        $transaction = Transaction::with(['customer', 'user'])->findOrFail($transactionId);

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 250],
            'directionality' => 'rtl',
            'margin_top' => 2,
            'margin_bottom' => 2,
            'margin_left' => 2,
            'margin_right' => 2,
            'fontDir' => array_merge(
                (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                [public_path('fonts')]
            ),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'Shabnam' => ['R' => 'Shabnam-FD.ttf'],
            ],
            'default_font' => 'Shabnam',
        ]);

        $mpdf->SetAutoPageBreak(false);

        $html = view('pdf.Sarafi.transaction', compact('transaction'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'transaction_' . $transaction->id . '.pdf';
        $path = storage_path('app/public/' . $fileName);

        $mpdf->Output($path, 'F');

        $this->dispatch('print-pdf', url: asset('storage/' . $fileName));
    }

    private function applyCurrencyChange($user, $currency, $amount, $transactionType, $accountType, $reverse = false)
    {
        $adminId = $user->admin_id ?? $user->id;

        $factor = $reverse ? -1 : 1;
        $change = ($transactionType === 'رسید' ? 1 : -1) * $amount * $factor;

        if ($accountType === 'نقدی') {
            $safe = CurrencySafe::firstOrCreate(
                ['admin_id' => $adminId],
                [
                    'user_id' => $user->id,
                    'usd' => 0,
                    'afn' => 0,
                    'eur' => 0,
                    'irr' => 0,
                    'aed' => 0,
                    'try' => 0,
                    'cny' => 0,
                    'pkr' => 0,
                    'gbp' => 0,
                    'jpy' => 0,
                    'sar' => 0,
                    'inr' => 0
                ]
            );

            $safe->increment($currency, $change);
            if ($safe->$currency < 0) {
                $safe->$currency = 0;
            }
            $safe->save();
        } else {
            $bankAccount = BankAccount::firstOrCreate(
                ['admin_id' => $adminId],
                [
                    'user_id' => $user->id,
                    'usd' => 0,
                    'afn' => 0,
                    'eur' => 0,
                    'irr' => 0,
                    'aed' => 0,
                    'try' => 0,
                    'cny' => 0,
                    'pkr' => 0,
                    'gbp' => 0,
                    'jpy' => 0,
                    'sar' => 0,
                    'inr' => 0
                ]
            );

            $bankAccount->increment($currency, $change);
            if ($bankAccount->$currency < 0) {
                $bankAccount->$currency = 0;
            }
            $bankAccount->save();
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

    private function resetForm()
    {
        $this->reset([
            'selectedAccount',
            'byUser',
            'currency',
            'amount',
            'amountInWords',
            'description',
            'file',
            'transactionId',
        ]);

        $this->date = Jalalian::now()->format('Y/m/d');
        $this->transactionType = 'برد';
        $this->accountType = 'نقدی';
        $this->zone = Auth::guard('sarafi')->user()->zone;
    }

    public function cancel()
    {
        $this->resetForm();
        $this->dispatch('close-modal');
    }
}