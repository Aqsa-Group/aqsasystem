<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\SafeDealsRevenue;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;

class SafeDealReports extends Component
{
    use WithFileUploads;

    public $searchedCustomer = null;
    public $showCustomerModal = false;
    public $confirmDeleteId = null;

    public $customer_id;
    public $selectedAccount;
    public $byUser;
    public $currency;
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

    public $currencies = [
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

    public $cashBalances = [];
    public $bankBalances = [];

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
        $this->calculateSafeBalances();
    }

    public function setDefaultZone()
    {
        if (empty($this->zone)) {
            $this->zone = Auth::guard('sarafi')->user()->zone;
        }
    }


    public function toggleTransactionType()
    {
        $this->accountType = $this->accountType === 'نقدی' ? 'بانکی' : 'نقدی';
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

    protected $cacheKeys = [
        'customer_list' => 'customers_list_',
        'transactions_list' => 'transactions_list_',
    ];

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

        list($cashBalances, $bankBalances) = $this->calculateCustomerBalances($adminId);
        $totalBalances = $this->calculateTotalBalances($cashBalances, $bankBalances);
        $totalInUsd = $this->convertToUsd($totalBalances);

        $this->setCurrencyDefaults($totalBalances, $totalInUsd);
        $this->setCustomerBalances($cashBalances, $bankBalances, $totalBalances);
    }

    public function calculateSafeBalances()
    {
        foreach ($this->currencies as $currency) {
            $this->cashBalances[$currency['code']] = SafeDealsRevenue::query()
                ->where('currency', $currency['code'])
                ->where('account_type', 'نقدی')
                ->selectRaw("SUM(CASE WHEN type = 'رسید' THEN amount ELSE -amount END) as balance")
                ->first()
                ->balance ?? 0;

            $this->bankBalances[$currency['code']] = SafeDealsRevenue::query()
                ->where('currency', $currency['code'])
                ->where('account_type', 'بانکی')
                ->selectRaw("SUM(CASE WHEN type = 'رسید' THEN amount ELSE -amount END) as balance")
                ->first()
                ->balance ?? 0;
        }
    }

    public function updateTransactions()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $query = SafeDealsRevenue::with('customer')
            ->where('admin_id', $adminId)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($this->selectedCustomerId) {
            $query->where('customer_id', $this->selectedCustomerId);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', "%{$this->search}%")
                    ->orWhere('currency', 'like', "%{$this->search}%")
                    ->orWhere('amount', 'like', "%{$this->search}%")
                    ->orWhereHas('customer', function ($q2) {
                        $q2->where('fullname', 'like', "%{$this->search}%")
                            ->orWhere('account_number', 'like', "%{$this->search}%");
                    });
            });
        }

        $this->transactions = $query->get();
    }

    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->selectedCustomer = null;
        $this->search = '';
        $this->updateTransactions();
    }

    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedCustomerId = null;
        $this->selectedCustomer = null;
        $this->updateTransactions();
    }

    public function formatAmount()
    {
        if ($this->amount) {
            $this->amount = number_format((int)$this->amount);
        }
    }
    private function updateCurrencySafe($adminId, $currency, $amount)
    {
        $safe = CurrencySafe::where('admin_id', $adminId)
            ->lockForUpdate()
            ->first();

        if (!$safe) {
            throw new \Exception('صندوق نقدی یافت نشد');
        }

        $column = strtolower(trim($currency));

        // ✅ اصلاح شده: بررسی عدم وجود ستون
        if (!Schema::connection($safe->getConnectionName())
            ->hasColumn($safe->getTable(), $column)) {
            throw new \Exception('ارز نامعتبر: ' . $currency);
        }

        $currentBalance = (float) ($safe->$column ?? 0);
        $newBalance = $currentBalance + $amount;

        if ($amount < 0 && $newBalance < 0) {
            throw new \Exception(
                'موجودی صندوق نقدی کافی نیست. موجودی فعلی: '
                    . number_format($currentBalance) . ' ' . strtoupper($currency)
            );
        }

        $safe->$column = $newBalance;
        $safe->save();
    }

    private function updateBankSafe($adminId, $currency, $amount)
    {
        $bank = BankAccount::where('admin_id', $adminId)
            ->lockForUpdate()
            ->first();

        if (!$bank) {
            throw new \Exception('صندوق بانکی یافت نشد');
        }

        $column = strtolower(trim($currency));

        // ✅ اصلاح شده: بررسی عدم وجود ستون
        if (!Schema::connection($bank->getConnectionName())
            ->hasColumn($bank->getTable(), $column)) {
            throw new \Exception('ارز نامعتبر: ' . $currency);
        }

        $currentBalance = (float) ($bank->$column ?? 0);
        $newBalance = $currentBalance + $amount;

        if ($amount < 0 && $newBalance < 0) {
            throw new \Exception(
                'موجودی صندوق بانکی کافی نیست. موجودی فعلی: '
                    . number_format($currentBalance) . ' ' . strtoupper($currency)
            );
        }

        $bank->$column = $newBalance;
        $bank->save();
    }

    public function submitTransaction()
    {
        $this->amount = str_replace(',', '', $this->amount);

        $this->validate([
            'currency' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required',
            'zone' => 'required',
            'description' => 'required',
        ]);

        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            DB::beginTransaction();

            // 1. ایجاد تراکنش اصلی
            $safeDeal = SafeDealsRevenue::create([
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'type' => 'برد',
                'account_type' => $this->accountType,
                'date' => $this->date,
                'description' => $this->description . ($this->selectedAccount ? ' - انتقال به حساب مشتری' : ''),
                'customer_id' => $this->selectedAccount,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. به‌روزرسانی موجودی صندوق
            if ($this->accountType === 'نقدی') {
                // از صندوق کم می‌شود
                $this->updateCurrencySafe($adminId, $this->currency, -$this->amount);

                // اگر به مشتری انتقال داده شد، به صندوق اضافه می‌شود
                if ($this->selectedAccount) {
                    $this->updateCurrencySafe($adminId, $this->currency, +$this->amount);
                }
            } else { // بانکی
                $this->updateBankSafe($adminId, $this->currency, -$this->amount);

                if ($this->selectedAccount) {
                    $this->updateBankSafe($adminId, $this->currency, +$this->amount);
                }
            }

            // 3. ایجاد تراکنش مشتری (اگر مشتری انتخاب شده)
            if ($this->selectedAccount) {
                Transaction::create([
                    'customer_id' => $this->selectedAccount,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'currency' => $this->currency,
                    'amount' => $this->amount,
                    'type' => 'رسید',
                    'account_type' => $this->accountType,
                    'date' => $this->date,
                    'description' => $this->description,
                    'zone' => 'هرات',
                    'by' => $this->by ?? $user->name,
                    'safe_deals_revenue_id' => $safeDeal->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            Cache::forget($this->cacheKeys['transactions_list'] . $adminId);

            $this->resetForm();
            $this->calculateSafeBalances();
            $this->updateTransactions();

            session()->flash('message', 'تراکنش با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting transaction: ' . $e->getMessage());
            session()->flash('error', 'خطا در ثبت تراکنش: ' . $e->getMessage());
        }
    }

    public function submitAndPrint()
    {
        $this->submitTransaction();
        if (!session()->has('error')) {
            $this->dispatch('print-pdf', ['url' => route('sarafi.transaction.print', ['id' => $this->transactionId])]);
        }
    }

    public function cancel()
    {
        $this->resetForm();
    }

    public function edit($id)
    {
        $transaction = SafeDealsRevenue::find($id);
        if ($transaction) {
            $this->transactionId = $transaction->id;
            $this->selectedAccount = $transaction->customer_id;
            $this->currency = $transaction->currency;
            $this->amount = $transaction->amount;
            $this->accountType = $transaction->account_type;
            $this->date = $transaction->date;
            $this->description = $transaction->description;

            if ($transaction->customer_id) {
                $this->selectCustomer($transaction->customer_id);
            }
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        try {
            $transaction = SafeDealsRevenue::find($this->confirmDeleteId);
            if ($transaction) {
                Transaction::where('safe_deals_revenue_id', $transaction->id)->delete();

                $transaction->delete();

                $user = Auth::guard('sarafi')->user();
                $adminId = $user->admin_id ?? $user->id;
                Cache::forget($this->cacheKeys['transactions_list'] . $adminId);

                session()->flash('message', 'تراکنش با موفقیت حذف شد.');

                $this->calculateSafeBalances();
                $this->updateTransactions();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در حذف تراکنش: ' . $e->getMessage());
        }

        $this->confirmDeleteId = null;
    }

    public function print($id)
    {
        $this->dispatch('print-pdf', ['url' => route('sarafi.transaction.print', ['id' => $id])]);
    }

    private function resetForm()
    {
        $this->transactionId = null;
        $this->selectedAccount = null;
        $this->currency = null;
        $this->amount = null;
        $this->amountInWords = null;
        $this->transactionType = 'رسید';
        $this->accountType = 'نقدی';
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->description = null;
        $this->zone = Auth::guard('sarafi')->user()->zone;
        $this->by = null;
        $this->file = null;

        $this->selectedCustomerId = null;
        $this->selectedCustomer = null;
        $this->search = '';
    }

    private function resetBalances()
    {
        foreach ($this->currencies as $currency) {
            $this->customerCashBalances[$currency['code']] = 0;
            $this->customerBankBalances[$currency['code']] = 0;
            $this->customerTotalBalances[$currency['code']] = 0;
        }
    }

    private function calculateCustomerBalances($adminId)
    {
        $cashBalances = [];
        $bankBalances = [];

        foreach ($this->currencies as $currency) {
            $cashBalances[$currency['code']] = Transaction::query()
                ->where('customer_id', $this->selectedCustomerId)
                ->where('currency', $currency['code'])
                ->where('account_type', 'نقدی')
                ->selectRaw("SUM(CASE WHEN type = 'رسید' THEN amount ELSE -amount END) as balance")
                ->first()
                ->balance ?? 0;

            $bankBalances[$currency['code']] = Transaction::query()
                ->where('customer_id', $this->selectedCustomerId)
                ->where('currency', $currency['code'])
                ->where('account_type', 'بانکی')
                ->selectRaw("SUM(CASE WHEN type = 'رسید' THEN amount ELSE -amount END) as balance")
                ->first()
                ->balance ?? 0;
        }

        return [$cashBalances, $bankBalances];
    }

    private function calculateTotalBalances($cashBalances, $bankBalances)
    {
        $totalBalances = [];
        foreach ($this->currencies as $currency) {
            $totalBalances[$currency['code']] =
                ($cashBalances[$currency['code']] ?? 0) +
                ($bankBalances[$currency['code']] ?? 0);
        }
        return $totalBalances;
    }

    private function convertToUsd($balances)
    {
        $total = 0;
        foreach ($balances as $currency => $amount) {
            $total += $amount;
        }
        return $total;
    }

    private function setCurrencyDefaults($totalBalances, $totalInUsd)
    {
        if (empty($this->currency)) {
            arsort($totalBalances);
            $this->currency = key($totalBalances) ?? 'usd';
        }
    }

    private function setCustomerBalances($cashBalances, $bankBalances, $totalBalances)
    {
        $this->customerCashBalances = $cashBalances;
        $this->customerBankBalances = $bankBalances;
        $this->customerTotalBalances = $totalBalances;
    }

    public function render()
    {
        return view('livewire.sarafi.safe-deal-reports');
    }
}
