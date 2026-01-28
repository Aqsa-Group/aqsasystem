<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\WithdrawsBanks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;
use NumberFormatter;

class WithdrawBank extends Component
{
    use WithFileUploads;

    public $confirmDeleteId = null;
    public $remittanceId = null;
    public $amountInWords;
    public $selectedAccount;
    public $toAccount;
    public $source_account;
    public $distantion_account;

    public $currency;
    public $amount;
    public $date;
    public $clock;
    public $tracking_code;
    public $from_bank;
    public $to_bank;
    public $zone;
    public $giver_name;
    public $description;
    public $remittance_image;
    public $source_account_last_four;
    public $distantion_account_last_four;

    public $accountType = 'معاملات داخلی';

    // Data collections
    public $currencies = [];
    public $customers = [];
    public $remittances = [];

    public $customerCashBalances = [];
    public $customerBankBalances = [];
    public $customerTotalBalances = [];

    public $search = '';
    public $selectedCustomer = null;
    public $selectedCustomerId = null;
    public $filteredCustomers = [];

    public function updatedAccountSearch($value)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $relatedUserIds = \App\Models\Sarafi\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

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
            ->get();
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

    public function toggleAccountType()
    {
        $this->accountType = $this->accountType === 'معاملات داخلی' ? 'معاملات بیرونی' : 'معاملات داخلی';
    }

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->clock = now()->format('H:i:s');
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
        $this->updateRemittances();
        $this->filteredCustomers = [];
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
        $this->currenciesdefault = array_map(function ($currency) {
            return ['name' => $currency, 'value' => 0];
        }, [
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
        ]);

        $this->customerCashBalances = [];
        $this->customerBankBalances = [];
        $this->customerTotalBalances = [];
    }

    private function calculateBalances($adminId)
    {
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
        
        $transactions = Transaction::where('customer_id', $this->selectedCustomerId)
            ->where('admin_id', $adminId)
            ->whereIn('type', ['برد', 'رسید'])
            ->get();

        foreach ($transactions as $transaction) {
            $currencyName = $this->getCurrencyName($transaction->currency);
            $amount = $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;

            if (isset($cashBalances[$currencyName]) || isset($bankBalances[$currencyName])) {
                if ($transaction->account_type === 'نقدی') {
                    $cashBalances[$currencyName] += $amount;
                } else {
                    $bankBalances[$currencyName] += $amount;
                }
            }
        }

        return [$cashBalances, $bankBalances];
    }

    private function calculateTotalBalances($cashBalances, $bankBalances)
    {
        $totalBalances = [];
        foreach ($cashBalances as $currency => $balance) {
            $totalBalances[$currency] = $balance + $bankBalances[$currency];
        }
        return $totalBalances;
    }

    private function convertToUsd($totalBalances)
    {
        $latestExchangeRate = ExchangeRates::latest()->first();
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

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->selectedAccount = $customerId;
        $this->selectedCustomer = Customer::find($customerId);
        $this->filteredCustomers = [];

        $customer = Customer::find($customerId);
        if ($customer) {
            $this->search = $customer->fullname;

            if (!$this->customers->contains('id', $customer->id)) {
                $this->customers->push($customer);
            }

            $this->dispatch('account-selected', [
                'id' => $customer->id,
                'text' => $customer->account_number . ' - ' . $customer->fullname,
            ]);

            $this->updateCustomerCurrencyBalance();

            Log::debug("Customer selected", [
                'customer_id' => $customerId,
                'customer_name' => $customer->fullname,
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

    public function showReport()
    {
        if (!$this->selectedCustomerId) {
            session()->flash('error', 'لطفاً ابتدا یک مشتری را انتخاب کنید');
            return;
        }

        session(['selected_customer_id' => $this->selectedCustomerId]);
        return redirect()->route('sarafi.transaction-reports');
    }

    private function loadCustomers()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->customers = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        $this->customers = Customer::select('customers.id', 'customers.account_number', 'customers.fullname', 'customers.admin_id')
            ->leftJoin('customer_admin', function ($join) use ($adminId) {
                $join->on('customers.id', '=', 'customer_admin.customer_id')
                    ->where('customer_admin.admin_id', '=', $adminId);
            })
            ->where(function ($query) use ($adminId) {
                $query->where('customers.admin_id', '=', $adminId)
                    ->orWhereNotNull('customer_admin.id');
            })
            ->orderBy('customers.fullname')
            ->distinct()
            ->get()
            ->map(function ($customer) use ($adminId) {
                return [
                    'id' => $customer->id,
                    'account_number' => $customer->account_number,
                    'fullname' => $customer->fullname,
                    'admin_id' => $customer->admin_id,
                    'is_mine' => $customer->admin_id == $adminId,
                    'is_linked' => $customer->admin_id != $adminId
                ];
            });
    }

    public function updatedSearch($value)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        if (empty($value)) {
            $this->selectedCustomerId = null;
            $this->filteredCustomers = [];
            $this->updateRemittances();
            return;
        }

        $this->filteredCustomers = Customer::where('admin_id', $adminId)
            ->where(function ($query) use ($value) {
                $query->where('fullname', 'like', "%{$value}%")
                    ->orWhere('account_number', 'like', "%{$value}%");
            })
            ->limit(15)
            ->get();

        if (count($this->filteredCustomers) === 1) {
            $this->selectCustomer($this->filteredCustomers[0]['id']);
        } else {
            $this->selectedCustomerId = null;
            $this->updateRemittances();
        }
    }

    public function selectToAccount($customerId)
    {
        $this->toAccount = $customerId;
        $this->autoFillGiverName($customerId);
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

    private function autoFillGiverName($customerId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $this->giver_name = $customer->fullname;
        }
    }

    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->toAccount = null;
        $this->search = '';
        $this->filteredCustomers = [];
        $this->updateRemittances();
    }

    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->toAccount = null;
        $this->filteredCustomers = [];
        $this->updateRemittances();
    }

    private function createTransactions($remittance)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $senderCustomer = Customer::find($this->selectedAccount);
        $receiverCustomer = Customer::find($this->toAccount);

        // تاریخ شمسی
        $jalaliDate = $this->date;

        // تراکنش برداشت از فرستنده
        Transaction::create([
            'withdrawbank_id' => $remittance->id,
            'customer_id' => $this->selectedAccount,
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'type' => 'برد',
            'date' => $jalaliDate,
            'description' => 'برداشت حواله بانکی | کد: ' . $this->tracking_code .
                ' | به: ' . ($receiverCustomer->fullname ?? ''),
            'zone' => $this->zone,
            'by' => 'خودش',
            'account_type' => 'بانکی',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // فقط در معاملات داخلی، تراکنش برای گیرنده ایجاد می‌شود
        if ($this->accountType === 'معاملات داخلی') {
            Transaction::create([
                'withdrawbank_id' => $remittance->id,
                'customer_id' => $this->toAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'type' => 'برد',
                'date' => $jalaliDate,
                'description' => 'برد حواله بانکی | کد: ' . $this->tracking_code,
                'zone' => $this->zone,
                'by' => 'خودش',
                'account_type' => 'بانکی',
            ]);
        }
    }

    private function deleteTransactions($remittanceId)
    {
        Transaction::where('withdrawbank_id', $remittanceId)->delete();
    }

    public function updateRemittances()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->remittances = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        $query = WithdrawsBanks::with(['customer', 'recipient'])
            ->where('admin_id', $adminId);

        if ($this->selectedCustomerId) {
            $query->where('customer_id', $this->selectedCustomerId);
        }

        $this->remittances = $query->latest()->get();
    }

    private function applyBankWithdrawal()
    {
        // در معاملات بیرونی هیچ تغییری در صندوق ایجاد نمی‌شود
        if ($this->accountType === 'معاملات بیرونی') {
            Log::info('External transaction: no bank balance change', [
                'account_type' => $this->accountType,
                'remittance_id' => $this->remittanceId ?? 'new'
            ]);
            return;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $currencyColumn = strtolower($this->currency);

        $bank = BankAccount::where('admin_id', $adminId)->first();

        if (!$bank) {
            Log::error('Bank account not found', ['admin_id' => $adminId]);
            throw new \Exception('صندوق بانکی یافت نشد');
        }

        $currentBalance = $bank->$currencyColumn ?? 0;

        if ($currentBalance < $this->amount) {
            Log::error('Insufficient bank balance', [
                'admin_id' => $adminId,
                'currency' => $currencyColumn,
                'amount' => $this->amount,
                'balance' => $currentBalance
            ]);

            throw new \Exception(
                'موجودی صندوق بانکی کافی نیست. موجودی فعلی: ' . number_format($currentBalance)
            );
        }

        $bank->decrement($currencyColumn, $this->amount);

        Log::info('Bank withdrawal applied', [
            'admin_id' => $adminId,
            'currency' => $currencyColumn,
            'amount' => $this->amount,
            'new_balance' => $bank->$currencyColumn,
            'remittance_id' => $this->remittanceId ?? 'new'
        ]);
    }

    private function reverseBankWithdrawal($amount, $currency, $accountType = null)
    {
        // در معاملات بیرونی هیچ تغییری در صندوق ایجاد نمی‌شود
        if ($accountType === 'معاملات بیرونی') {
            Log::info('External transaction reversal: no bank balance change', [
                'account_type' => $accountType,
                'amount' => $amount,
                'currency' => $currency
            ]);
            return;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $currencyColumn = strtolower($currency);

        $bank = BankAccount::where('admin_id', $adminId)->first();

        if ($bank) {
            $bank->increment($currencyColumn, $amount);
            Log::info('Bank withdrawal reversed', [
                'admin_id' => $adminId,
                'currency' => $currencyColumn,
                'amount' => $amount,
                'new_balance' => $bank->$currencyColumn
            ]);
        }
    }

    public function submitRemittance()
    {
        Log::info('Starting submitRemittance', ['user' => Auth::guard('sarafi')->user()->id ?? 'none']);

        $this->amount = str_replace(',', '', $this->amount);
        $this->source_account = $this->source_account_last_four . ' - xxxx - xxxx - xxxx';

        // مقداردهی فیلد distantion_account در معاملات بیرونی
        if ($this->accountType === 'معاملات بیرونی') {
            $this->distantion_account = $this->distantion_account_last_four . ' - xxxx - xxxx - xxxx';
            $this->toAccount = null;
        }

        // اعتبارسنجی شرطی
        $validationRules = [
            'selectedAccount' => 'required|exists:sarafi.customers,id',
            'currency' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'clock' => 'required',
            'tracking_code' => 'required|string|max:255',
            'from_bank' => 'required|string|max:255',
            'to_bank' => 'required|string|max:255',
            'zone' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'remittance_image' => 'nullable|image|max:10240',
            'source_account_last_four' => 'required|digits:4',
        ];

        if ($this->accountType === 'معاملات داخلی') {
            $validationRules['toAccount'] = 'required|exists:sarafi.customers,id|different:selectedAccount';
            $validationRules['giver_name'] = 'required|string|max:255';
        } else {
            $validationRules['distantion_account_last_four'] = 'required|digits:4';
            $validationRules['giver_name'] = 'nullable|string|max:255';
        }

        $this->validate($validationRules);

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        Log::info('Validation passed', [
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'account_type' => $this->accountType
        ]);

        $imagePath = $this->remittance_image
            ? $this->remittance_image->store('remittances', 'public')
            : null;

        $data = [
            'customer_id' => $this->selectedAccount,
            'to_account' => $this->toAccount,
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'source_account' => $this->source_account,
            'distanition_account' => $this->distantion_account,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'date' => $this->date,
            'clock' => $this->clock,
            'tracking_code' => $this->tracking_code,
            'from_bank' => $this->from_bank,
            'to_bank' => $this->to_bank,
            'zone' => $this->zone,
            'giver_name' => $this->giver_name,
            'description' => $this->description,
            'remittance_image' => $imagePath,
            'state' => 1,
            'account_type' => $this->accountType,
        ];

        DB::beginTransaction();

        try {
            $oldAmount = 0;
            $oldCurrency = null;
            $oldAccountType = null;

            if ($this->remittanceId) {
                $remittance = WithdrawsBanks::findOrFail($this->remittanceId);

                $oldAmount = $remittance->amount;
                $oldCurrency = $remittance->currency;
                $oldAccountType = $remittance->account_type;

                // فقط اگر حواله قدیمی از نوع داخلی بوده، برگشت انجام شود
                if ($oldAmount > 0 && $oldCurrency && $oldAccountType === 'معاملات داخلی') {
                    $this->reverseBankWithdrawal($oldAmount, $oldCurrency, $oldAccountType);
                }

                if ($imagePath && $remittance->remittance_image) {
                    Storage::disk('public')->delete($remittance->remittance_image);
                }

                $remittance->update($data);
                $this->deleteTransactions($remittance->id);

                Log::info('Updated existing remittance', [
                    'id' => $remittance->id,
                    'old_account_type' => $oldAccountType,
                    'new_account_type' => $this->accountType
                ]);
            } else {
                $remittance = WithdrawsBanks::create($data);
                Log::info('Created new remittance', ['id' => $remittance->id]);
            }

            // اعمال کسر از صندوق بانکی (فقط برای معاملات داخلی)
            $this->applyBankWithdrawal();

            // ایجاد تراکنش‌ها
            $this->createTransactions($remittance);

            DB::commit();

            session()->flash('message', 'حواله با موفقیت ثبت شد.');
            $this->updateRemittances();
            $this->resetForm();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Remittance Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'خطا در ثبت حواله: ' . $e->getMessage());
        }
        $this->updateCustomerCurrencyBalance();
    }

    public function edit($id)
    {
        $remittance = WithdrawsBanks::with(['customer', 'recipient'])->findOrFail($id);

        $this->remittanceId = $id;
        $this->selectedAccount = $remittance->customer_id;
        $this->toAccount = $remittance->to_account;
        $this->source_account = $remittance->source_account;
        $this->accountType = $remittance->account_type; // مهم: نوع حساب را نیز تنظیم کن

        $this->source_account_last_four = substr($remittance->source_account, 0, 4);
        $this->distantion_account_last_four = substr($remittance->distanition_account, 0, 4);

        $this->currency = $remittance->currency;
        $this->amount = $remittance->amount;
        $this->date = $remittance->date;
        $this->clock = $remittance->clock;
        $this->tracking_code = $remittance->tracking_code;
        $this->from_bank = $remittance->from_bank;
        $this->to_bank = $remittance->to_bank;
        $this->zone = $remittance->zone;
        $this->giver_name = $remittance->giver_name;
        $this->description = $remittance->description;

        $this->search = $remittance->customer->fullname ?? '';
        $this->filteredCustomers = [];
        $this->updateCustomerCurrencyBalance();
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        DB::transaction(function () {
            $remittance = WithdrawsBanks::findOrFail($this->confirmDeleteId);

            // فقط اگر حواله از نوع معاملات داخلی باشد، برگشت به صندوق انجام شود
            if ($remittance->account_type === 'معاملات داخلی') {
                $this->reverseBankWithdrawal(
                    $remittance->amount,
                    $remittance->currency,
                    $remittance->account_type
                );
            }

            // حذف تراکنش‌های مرتبط
            $this->deleteTransactions($remittance->id);

            // حذف تصویر
            if ($remittance->remittance_image) {
                Storage::disk('public')->delete($remittance->remittance_image);
            }

            // حذف حواله
            $remittance->delete();

            session()->flash('message', 'حواله با موفقیت حذف شد.');
        });

        $this->updateRemittances();
        $this->confirmDeleteId = null;
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'remittanceId',
            'selectedAccount',
            'toAccount',
            'source_account',
            'source_account_last_four',
            'distantion_account',
            'distantion_account_last_four',
            'currency',
            'amount',
            'clock',
            'tracking_code',
            'from_bank',
            'to_bank',
            'giver_name',
            'description',
            'remittance_image',
            'accountType'
        ]);

        $this->date = Jalalian::now()->format('Y/m/d');
        $this->clock = now()->format('H:i:s');
        $this->zone = Auth::guard('sarafi')->user()->zone;
        $this->search = '';
        $this->filteredCustomers = [];
        $this->amountInWords = null;
        $this->accountType = 'معاملات داخلی'; // بازنشانی به حالت پیش‌فرض
    }

    public function formatAmount()
    {
        if ($this->amount) {
            $this->amount = number_format((float) $this->amount);
        }
    }

    public function setDefaultZone()
    {
        $this->zone = Auth::guard('sarafi')->user()->zone;
    }

    public function submitAndPrint()
    {
        $this->submitRemittance();
        // Add print logic here
    }

    public function print($id)
    {
        $this->dispatch('report-alert', message: 'Print functionality for remittance ID: ' . $id);
    }

    public function render()
    {
        $user = Auth::guard('sarafi')->user();

        if (!$user) {
            return view('livewire.sarafi.withdraw-bank', [
                'customers' => collect(),
                'remittances' => collect(),
                'filteredCustomers' => [],
            ]);
        }

        if (collect($this->customers)->isEmpty()) {
            $this->loadCustomers();
        }

        return view('livewire.sarafi.withdraw-bank', [
            'customers' => $this->customers,
            'remittances' => $this->remittances,
            'filteredCustomers' => $this->filteredCustomers ?? [],
        ]);
    }
}