<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\SafeDealsRevenue;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class SafeDealReports extends Component
{
    use WithPagination;

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
    public $transactionType = 'تبدیل';
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
        ['code' => 'inr', 'name_fa' => 'روپیه'],
    ];

    public $cashBalances = [];
    public $bankBalances = [];
    
    // متغیرهای جدید برای تبدیل ارز
    public $from_currency;
    public $to_currency;
    public $from_amount;
    public $to_amount;
    public $currency_rate;
    public $zone_sender;
    public $by_sender;
    public $zone_receiver;
    public $by_receiver;
    public $conversion_description;

    protected $rules = [
        'selectedAccount' => 'required',
        'from_currency' => 'required',
        'to_currency' => 'required',
        'from_amount' => 'required|numeric|min:0.01',
        'to_amount' => 'required|numeric|min:0.01',
        'currency_rate' => 'required|numeric|min:0.0001',
        'date' => 'required',
        'zone_sender' => 'required',
        'zone_receiver' => 'required',
        'description' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'selectedAccount.required' => 'انتخاب حساب مشتری الزامی است.',
        'from_currency.required' => 'انتخاب ارز مبدا الزامی است.',
        'to_currency.required' => 'انتخاب ارز مقصد الزامی است.',
        'from_amount.required' => 'مبلغ مبدا الزامی است.',
        'from_amount.numeric' => 'مبلغ مبدا باید عددی باشد.',
        'from_amount.min' => 'مبلغ مبدا باید بیشتر از صفر باشد.',
        'to_amount.required' => 'مبلغ مقصد الزامی است.',
        'to_amount.numeric' => 'مبلغ مقصد باید عددی باشد.',
        'to_amount.min' => 'مبلغ مقصد باید بیشتر از صفر باشد.',
        'currency_rate.required' => 'نرخ تبدیل الزامی است.',
        'currency_rate.numeric' => 'نرخ تبدیل باید عددی باشد.',
        'currency_rate.min' => 'نرخ تبدیل باید بیشتر از صفر باشد.',
        'date.required' => 'تاریخ الزامی است.',
        'zone_sender.required' => 'زون ارسال کننده الزامی است.',
        'zone_receiver.required' => 'زون دریافت کننده الزامی است.',
    ];

    protected $cacheKeys = [
        'customer_list' => 'customers_list_',
        'transactions_list' => 'transactions_list_',
    ];

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->zone = Auth::guard('sarafi')->user()->zone;
        $this->zone_sender = Auth::guard('sarafi')->user()->zone;
        $this->zone_receiver = Auth::guard('sarafi')->user()->zone;
        $this->by_sender = Auth::guard('sarafi')->user()->name;
        $this->by_receiver = Auth::guard('sarafi')->user()->name;

        $this->loadCustomers();
        $this->calculateBalances();
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

        // محاسبه موجودی‌های مشتری
        $cashBalances = [];
        $bankBalances = [];
        
        foreach ($this->currencies as $currency) {
            $cashBalances[$currency['code']] = Transaction::where('customer_id', $this->selectedCustomerId)
                ->where('currency', $currency['code'])
                ->where('account_type', 'نقدی')
                ->selectRaw("SUM(CASE WHEN type = 'رسید' THEN amount ELSE -amount END) as balance")
                ->first()
                ->balance ?? 0;

            $bankBalances[$currency['code']] = Transaction::where('customer_id', $this->selectedCustomerId)
                ->where('currency', $currency['code'])
                ->where('account_type', 'بانکی')
                ->selectRaw("SUM(CASE WHEN type = 'رسید' THEN amount ELSE -amount END) as balance")
                ->first()
                ->balance ?? 0;
        }

        $this->customerCashBalances = $cashBalances;
        $this->customerBankBalances = $bankBalances;
    }

    public function calculateBalances()
    {
        // محاسبه طلب نقدی و بانکی برای هر ارز از جدول safe_deals_revenue
        foreach ($this->currencies as $currency) {
            // نقدی
            $cashBalance = SafeDealsRevenue::query()
                ->where('currency', $currency['code'])
                ->where('account_type', 'نقدی')
                ->selectRaw("SUM(CASE WHEN type = 'رسید' THEN amount ELSE -amount END) as balance")
                ->first();
            
            $this->cashBalances[$currency['code']] = $cashBalance ? $cashBalance->balance : 0;

            // بانکی
            $bankBalance = SafeDealsRevenue::query()
                ->where('currency', $currency['code'])
                ->where('account_type', 'بانکی')
                ->selectRaw("SUM(CASE WHEN type = 'رسید' THEN amount ELSE -amount END) as balance")
                ->first();
            
            $this->bankBalances[$currency['code']] = $bankBalance ? $bankBalance->balance : 0;
        }
    }

    public function formatAmount()
    {
        if ($this->amount) {
            $this->amount = number_format(floatval(str_replace(',', '', $this->amount)), 2);
        }
    }

    public function getCurrencyFaName($code)
    {
        foreach ($this->currencies as $currency) {
            if ($currency['code'] === $code) {
                return $currency['name_fa'];
            }
        }
        return $code;
    }

    public function submitTransaction()
    {
        $this->validate();

        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            DB::beginTransaction();

            // ایجاد رکورد در safe_deals_revenue
            $safeDeal = \App\Models\Sarafi\SafeDeal::create([
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'customer_id' => $this->selectedAccount,
                'from_currency' => $this->from_currency,
                'to_currency' => $this->to_currency,
                'from_amount' => $this->from_amount,
                'to_amount' => $this->to_amount,
                'rate' => $this->currency_rate,
                'date' => $this->date,
                'description' => $this->description,
                'zone_sender' => $this->zone_sender,
                'zone_receiver' => $this->zone_receiver,
                'by_sender' => $this->by_sender,
                'by_receiver' => $this->by_receiver,
            ]);

            // ایجاد دو تراکنش در safe_deals_revenue
            // تراکنش اول: برداشت از حساب مبدا
            SafeDealsRevenue::create([
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'safe_deals_id' => $safeDeal->id,
                'currency' => $this->from_currency,
                'amount' => $this->from_amount,
                'type' => 'برد',
                'account_type' => $this->accountType, // نقدی یا بانکی
                'date' => $this->date,
                'description' => $this->description . ' - برداشت از ' . ($this->accountType === 'نقدی' ? 'نقدی' : 'بانکی'),
            ]);

            // تراکنش دوم: واریز به حساب مقصد
            $targetAccountType = $this->accountType === 'نقدی' ? 'بانکی' : 'نقدی';
            SafeDealsRevenue::create([
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'safe_deals_id' => $safeDeal->id,
                'currency' => $this->to_currency,
                'amount' => $this->to_amount,
                'type' => 'رسید',
                'account_type' => $targetAccountType,
                'date' => $this->date,
                'description' => $this->description . ' - دریافت به ' . $targetAccountType,
            ]);

            // اگر مشتری انتخاب شده بود، تراکنش‌های مشتری هم ثبت شوند
            if ($this->selectedAccount) {
                // تراکنش برداشت برای مشتری
                Transaction::create([
                    'customer_id' => $this->selectedAccount,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'currency' => $this->from_currency,
                    'amount' => $this->from_amount,
                    'type' => 'برد',
                    'date' => $this->date,
                    'zone' => $this->zone_sender,
                    'description' => 
                        ' برداشت مبلغ ' . number_format($this->from_amount, 2) . ' ' .
                        $this->getCurrencyFaName($this->from_currency) .
                        ' و خرید مبلغ ' . number_format($this->to_amount, 2) . ' ' .
                        $this->getCurrencyFaName($this->to_currency) .
                        ' به نرخ ' . $this->currency_rate,
                    'by' => $this->by_sender,
                    'account_type' => $this->accountType,
                    'safe_deal_id' => $safeDeal->id,
                ]);

                // تراکنش واریز برای مشتری
                Transaction::create([
                    'customer_id' => $this->selectedAccount,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'currency' => $this->to_currency,
                    'amount' => $this->to_amount,
                    'type' => 'رسید',
                    'date' => $this->date,
                    'zone' => $this->zone_receiver,
                    'description' => 
                        ' دریافت مبلغ ' . number_format($this->to_amount, 2) . ' ' .
                        $this->getCurrencyFaName($this->to_currency) .
                        ' در مقابل پرداخت ' . number_format($this->from_amount, 2) . ' ' .
                        $this->getCurrencyFaName($this->from_currency) .
                        ' به نرخ ' . $this->currency_rate,
                    'by' => $this->by_receiver,
                    'account_type' => $targetAccountType,
                    'safe_deal_id' => $safeDeal->id,
                ]);
            }

            DB::commit();

            // به‌روزرسانی موجودی‌ها
            $this->calculateBalances();
            if ($this->selectedCustomerId) {
                $this->updateCustomerCurrencyBalance();
            }

            // پاک کردن کش
            $cacheKey = $this->cacheKeys['transactions_list'] . $adminId;
            Cache::forget($cacheKey);

            session()->flash('message', 'تراکنش تبادله با موفقیت ثبت شد.');
            
            // بازنشانی فرم
            $this->resetForm();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in submitTransaction: ' . $e->getMessage());
            session()->flash('error', 'خطا در ثبت تراکنش: ' . $e->getMessage());
        }
    }

    public function submitAndPrint()
    {
        $this->submitTransaction();
        // اگر موفقیت‌آمیز بود، پرینت انجام شود
        if (session()->has('message')) {
            // کد پرینت
        }
    }

    public function cancel()
    {
        $this->resetForm();
        $this->transactionId = null;
    }

    private function resetForm()
    {
        $this->reset([
            'selectedAccount',
            'from_currency',
            'to_currency',
            'from_amount',
            'to_amount',
            'currency_rate',
            'description',
            'zone_sender',
            'zone_receiver',
            'by_sender',
            'by_receiver',
        ]);
        
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->zone_sender = Auth::guard('sarafi')->user()->zone;
        $this->zone_receiver = Auth::guard('sarafi')->user()->zone;
        $this->by_sender = Auth::guard('sarafi')->user()->name;
        $this->by_receiver = Auth::guard('sarafi')->user()->name;
    }

    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->search = '';
        $this->filteredCustomers = [];
    }

    public function clearSearchAndFilter()
    {
        $this->clearFilter();
    }

    public function edit($id)
    {
        // کد ویرایش تراکنش
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
                $transaction->delete();
                session()->flash('message', 'تراکنش با موفقیت حذف شد.');
                $this->calculateBalances();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در حذف تراکنش: ' . $e->getMessage());
        }
        
        $this->confirmDeleteId = null;
    }

    public function print($id)
    {
        // کد پرینت
    }

    public function getTransactionsProperty()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        
        $cacheKey = $this->cacheKeys['transactions_list'] . $adminId . '_' . $this->selectedCustomerId . '_' . $this->search;

        return Cache::remember($cacheKey, 60, function () use ($adminId) {
            $query = \App\Models\Sarafi\SafeDeal::with('customer')
                ->where('admin_id', $adminId)
                ->orderBy('created_at', 'desc');

            if ($this->selectedCustomerId) {
                $query->where('customer_id', $this->selectedCustomerId);
            }

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('description', 'like', "%{$this->search}%")
                      ->orWhereHas('customer', function ($q2) {
                          $q2->where('fullname', 'like', "%{$this->search}%")
                             ->orWhere('account_number', 'like', "%{$this->search}%");
                      });
                });
            }

            return $query->paginate(10);
        });
    }

    public function render()
    {
        return view('livewire.sarafi.safe-deal-reports', [
            'transactions' => $this->transactions,
        ]);
    }
}