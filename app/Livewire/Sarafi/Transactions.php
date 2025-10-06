<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;
use NumberFormatter;

class Transactions extends Component
{
    use WithFileUploads;

    public $customer_id;
    public $selectedAccount;
    public $byUser;

    public $currency;
    public $currencies = [];
    public $amount;
    public $amountInWords;

    public $transactionType = 'برد';
    public $date;
    public $description;
    public $file;

    public $zone;
    public $by;
    public $transactionId;

    public $search = '';

    public $selectedCustomerId = null;
    public $transactions = [];

    public $filteredCustomers;
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

        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance();
        $this->updateCustomerCurrencyBalance();
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

    $this->filteredCustomers = Customer::where('admin_id', $adminId)
        ->where(function ($query) use ($value) {
            $query->where('fullname', 'like', "%{$value}%")
                  ->orWhere('account_number', 'like', "%{$value}%");
        })
        ->get();

    // اگر فقط یک مشتری پیدا شد، به طور خودکار انتخابش کن
    if ($this->filteredCustomers->count() === 1) {
        $this->selectCustomer($this->filteredCustomers->first()->id);
    } else {
        $this->selectedCustomerId = null;
        $this->updateTransactions();
    }
}


    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->search = '';
        $this->filteredCustomers = [];
        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance(); // این خط رو اضافه کنید
    }

    public function updateCustomerCurrencyBalance()
    {
        if (!$this->selectedCustomerId) {
            // اگر مشتری انتخاب نشده، همه مقادیر صفر شود
            $this->currenciesdefault = [
                ['name' => 'افغانی', 'value' => 0],
                ['name' => 'دالر', 'value' => 0],
                ['name' => 'تومان', 'value' => 0],
                ['name' => 'یورو', 'value' => 0],
                ['name' => 'کلدار', 'value' => 0],
                ['name' => 'درهم', 'value' => 0],
                ['name' => 'لیره', 'value' => 0],
                ['name' => 'یوان', 'value' => 0],
                ['name' => 'روپیه', 'value' => 0],

                ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
            ];
            return;
        }

        // محاسبه موجودی هر ارز برای مشتری انتخاب شده
        $transactions = Transaction::where('customer_id', $this->selectedCustomerId)->get();

        $balances = [
            'افغانی' => 0,
            'دالر' => 0,
            'تومان' => 0,
            'یورو' => 0,
            'کلدار' => 0,
            'درهم' => 0,
            'لیره' => 0,
            'یوان' => 0,
            'روپیه' => 0,

        ];

        foreach ($transactions as $transaction) {
            $currencyName = $this->getCurrencyName($transaction->currency);
            $amount = $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;

            if (array_key_exists($currencyName, $balances)) {
                $balances[$currencyName] += $amount;
            }
        }

        // important for feauture billance
        $totalInUsd = 0;
        $exchangeRates = [
            'افغانی' => 0.011,
            'دالر' => 1,
            'تومان' => 0.000024,
            'یورو' => 1.07,
            'کلدار' => 0.0036,
            'درهم' => 0.27,
            'لیره' => 0.031,
            'یوان' => 0.14,
            'روپیه' => 0.14,

        ];

        foreach ($balances as $currency => $balance) {
            if ($currency !== 'خلاصه بیلانس به دالر' && isset($exchangeRates[$currency])) {
                $totalInUsd += $balance * $exchangeRates[$currency];
            }
        }

        // بروزرسانی currenciesdefault
        $this->currenciesdefault = [
            ['name' => 'افغانی', 'value' => $balances['افغانی']],
            ['name' => 'دالر', 'value' => $balances['دالر']],
            ['name' => 'تومان', 'value' => $balances['تومان']],
            ['name' => 'یورو', 'value' => $balances['یورو']],
            ['name' => 'کلدار', 'value' => $balances['کلدار']],
            ['name' => 'درهم', 'value' => $balances['درهم']],
            ['name' => 'لیره', 'value' => $balances['لیره']],
            ['name' => 'یوان', 'value' => $balances['یوان']],
            ['name' => 'روپیه', 'value' => $balances['روپیه']],

            ['name' => 'خلاصه بیلانس به دالر', 'value' => $totalInUsd],
        ];
    }

    // متد کمکی برای تبدیل کد ارز به نام فارسی
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
        $this->search = '';
        $this->filteredCustomers = [];
        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->filteredCustomers = [];
    }

    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedCustomerId = null;
        $this->filteredCustomers = [];
        $this->updateTransactions();
    }

    public function updateTransactions()
    {
        $user = Auth::guard('sarafi')->user();

        if (!$user) {
            $this->transactions = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        $query = Transaction::with('customer')
            ->where(function ($q) use ($adminId) {
                $q->where('admin_id', $adminId)
                    ->orWhere('user_id', $adminId);
            });

        if ($this->selectedCustomerId) {
            $query->where('customer_id', $this->selectedCustomerId);
        }

        $this->transactions = $query->latest()->get();
    }

 public function render()
{
    $user = Auth::guard('sarafi')->user();

    if (!$user) {
        return view('livewire.sarafi.transactions', [
            'customers' => collect(),
            'transactions' => collect(),
        ]);
    }

    $adminId = $user->admin_id ?? $user->id;

    $customers = Customer::select('id', 'account_number', 'fullname')
        ->where('admin_id', $adminId)
        ->get();

    return view('livewire.sarafi.transactions', [
        'customers' => $customers,
        'transactions' => $this->transactions,
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

    public function formatAmount()
    {
        if ($this->amount) {
            $this->amount = number_format((int)$this->amount);
        }
    }

    public function toggleTransactionType()
    {
        $this->transactionType = $this->transactionType === 'برد' ? 'رسید' : 'برد';
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $this->transactionId = $id;
        $this->selectedAccount = $transaction->customer_id;
        $this->amount = $transaction->amount;
        $this->currency = $transaction->currency;
        $this->byUser = $transaction->by;
        $this->zone = $transaction->zone;
        $this->date = $transaction->date;
        $this->description = $transaction->description;
        $this->transactionType = $transaction->type;
    }

    public function delete($id)
    {
        $transaction = Transaction::findOrFail($id);

        $user = Auth::guard('sarafi')->user();
        $this->applyCurrencyChange($user, $transaction->currency, $transaction->amount, $transaction->type, true);

        $transaction->delete();
        session()->flash('message', 'تراکنش با موفقیت حذف شد.');
        $this->updateTransactions();
    }

    public function submitTransaction()
    {
        $this->selectedAccount = (int) $this->selectedAccount;
        $this->amount = str_replace(',', '', $this->amount);
        $user = Auth::guard('sarafi')->user();

        $this->validate([
            'selectedAccount' => 'required|exists:sarafi.customers,id',
            'byUser'         => 'nullable|string|max:255',
            'currency'       => 'required|string',
            'amount'         => 'required|numeric|min:1',
            'transactionType' => 'required|string',
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
            'date'             => $this->date,
            'description'      => $this->description,
            'zone'             => $this->zone,
            'transaction_file' => $filePath,
            'by'               => $this->byUser,
        ];

        if ($this->transactionId) {
            $old = Transaction::findOrFail($this->transactionId);

            $this->applyCurrencyChange($user, $old->currency, $old->amount, $old->type, true);

            $old->update($data);

            $this->applyCurrencyChange($user, $this->currency, $this->amount, $this->transactionType);

            session()->flash('message', 'تراکنش با موفقیت بروزرسانی شد.');
        } else {
            Transaction::create($data);

            $this->applyCurrencyChange($user, $this->currency, $this->amount, $this->transactionType);

            session()->flash('message', 'تراکنش با موفقیت ثبت شد.');
        }
        $this->updateTransactions();
        $this->resetForm();
    }

    private function applyCurrencyChange($user, $currency, $amount, $transactionType, $reverse = false)
    {
        $adminId = $user->admin_id ?? $user->id;

        $factor = $reverse ? -1 : 1;

        $change = ($transactionType === 'رسید' ? 1 : -1) * $amount * $factor;

        $safe = CurrencySafe::firstOrCreate(
            ['user_id' => $adminId, 'admin_id' => null],
            [
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

        $safe->$currency += $change;

        if ($safe->$currency < 0) {
            $safe->$currency = 0;
        }

        $safe->save();
    }

    private function updateCurrencySafe($userId, $currency, $amount)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $safe = CurrencySafe::firstOrCreate(
            [
                'user_id'          => $user->id,
                'admin_id'         => $adminId,
            ],
            [
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
                'inr' => 0,
            ]
        );

        $safe->$currency += $amount;

        if ($safe->$currency < 0) {
            $safe->$currency = 0;
        }

        $safe->save();
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
            'zone',
            'transactionId',
        ]);

        $this->date = Jalalian::now()->format('Y/m/d');
        $this->transactionType = 'برد';
    }
}
