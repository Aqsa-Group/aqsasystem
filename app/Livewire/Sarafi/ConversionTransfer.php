<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\ConversionTransfers;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use NumberFormatter;

class ConversionTransfer extends Component
{
    use WithPagination;

    // حساب برداشت
    public $withdrawalAccount;
    public $withdrawalCustomerId;

    // حساب دریافت
    public $depositAccount;
    public $depositCustomerId;

    // اطلاعات تبدیل ارز
    public $from_currency = '';
    public $withdrawal_amount = '';
    public $to_currency = '';
    public $received_amount = '';
    public $currency_rate = '';
    public $transaction_date;
    public $description = '';
    public $zone_sender = '';
    public $zone_receiver = '';
    public $by_sender = '';
    public $by_receiver = '';

    // برای نمایش حروفی
    public $withdrawalAmountInWords = '';
    public $receivedAmountInWords = '';
    public $currencyRateInWords = '';

    public $transactionType = 'خرید';
    public $from_account = 'نقدی';
    public $to_account = 'نقدی';
    public $currencies = [];
    public $customers = [];
    public $search = '';

    public $filteredCustomers;
    public $accountSearch = '';
    public $confirmDeleteId = null;
    public $editingConversionId = null;

    // اضافه کردن متغیرهای جدید برای نمایش موجودی‌ها
    public $customerCashBalances = [];
    public $customerBankBalances = [];
    public $customerTotalBalances = [];
    public $zones = [];


    public function render()
    {
        $user = Auth::guard('sarafi')->user();

        if (!$user) {
            return view('livewire.sarafi.conversion-transfer', [
                'customers' => collect(),
                'conversionTransactions' => collect(),
            ]);
        }

        $adminId = $user->admin_id ?? $user->id;

        // بارگذاری مشتریان اگر خالی است
        if (empty($this->customers)) {
            $this->loadCustomers($adminId);
        }

        // ایجاد کوئری پایه با join
        $query = ConversionTransfers::select(
            'conversion_transfer.*',
            'from_customer.fullname as from_customer_name',
            'from_customer.account_number as from_customer_account',
            'to_customer.fullname as to_customer_name',
            'to_customer.account_number as to_customer_account'
        )
            ->leftJoin('customers as from_customer', 'conversion_transfer.form_customer', '=', 'from_customer.id')
            ->leftJoin('customers as to_customer', 'conversion_transfer.to_customer', '=', 'to_customer.id')
            ->where('conversion_transfer.admin_id', $adminId);

        // اعمال جستجو اگر مقدار وجود دارد
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('from_customer.fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('from_customer.account_number', 'like', '%' . $this->search . '%')
                    ->orWhere('to_customer.fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('to_customer.account_number', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.from_currency', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.to_currency', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.withdrawal_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.received_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.currency_rate', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.description', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.type', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.zone_sender', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.zone_receiver', 'like', '%' . $this->search . '%');
            });
        }

        $conversionTransactions = $query->latest('conversion_transfer.created_at')->paginate(10);

        return view('livewire.sarafi.conversion-transfer', [
            'customers' => collect($this->customers),
            'conversionTransactions' => $conversionTransactions,
        ]);
    }
    public function showReport()
    {
        if (!$this->withdrawalCustomerId) {
            session()->flash('error', 'لطفاً ابتدا یک مشتری را برای حساب برداشت انتخاب کنید');
            return redirect()->back();
        }

        // پیدا کردن مشتری برای اطمینان از وجود
        $customer = Customer::find($this->withdrawalCustomerId);
        if (!$customer) {
            session()->flash('error', 'مشتری انتخاب شده یافت نشد');
            return redirect()->back();
        }

        // ذخیره اطلاعات مشتری در سشن
        session([
            'selected_customer_id' => $this->withdrawalCustomerId,
            'selected_customer_name' => $customer->fullname,
            'selected_customer_account' => $customer->account_number
        ]);

        // انتقال به صفحه گزارشات
        return redirect()->route('sarafi.transaction-reports');
    }


    public function search()
    {
        $this->resetPage();
    }
    private function loadCustomers($adminId)
    {
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
            ->get()
            ->toArray();
    }

    // متد تبدیل عدد به حروف
    private function convertAmountToWords($value, $property)
    {
        if ($value && is_numeric($value)) {
            try {
                $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
                $words = $formatter->format(floatval($value));
                $words = str_replace(['دویست', 'سیصد', 'پانصد'], ['دوصد', 'سه صد', 'پنجصد'], $words);
                $this->$property = $words;
            } catch (\Exception $e) {
                $this->$property = '';
                Log::error('Error converting amount to words: ' . $e->getMessage());
            }
        } else {
            $this->$property = '';
        }
    }

    public function toggleTransactionType()
    {
        // تغییر نوع معامله
        $this->transactionType = $this->transactionType === 'خرید' ? 'فروش' : 'خرید';

        // جابجایی حساب‌های برداشت و دریافت
        $tempAccount = $this->withdrawalAccount;
        $tempCustomerId = $this->withdrawalCustomerId;

        $this->withdrawalAccount = $this->depositAccount;
        $this->withdrawalCustomerId = $this->depositCustomerId;

        $this->depositAccount = $tempAccount;
        $this->depositCustomerId = $tempCustomerId;

        // جابجایی ارزها
        $tempCurrency = $this->from_currency;
        $this->from_currency = $this->to_currency;
        $this->to_currency = $tempCurrency;

        // جابجایی زون‌ها
        $tempZone = $this->zone_sender;
        $this->zone_sender = $this->zone_receiver;
        $this->zone_receiver = $tempZone;

        // جابجایی نام افراد
        $tempBy = $this->by_sender;
        $this->by_sender = $this->by_receiver;
        $this->by_receiver = $tempBy;

        // به‌روزرسانی موجودی‌ها برای حساب جدید برداشت
        if ($this->withdrawalAccount) {
            $this->updateCustomerCurrencyBalance($this->withdrawalAccount);
        }

        // محاسبه مجدد مبلغ دریافت
        $this->calculateReceivedAmount();

        // dispatch events برای Alpine.js
        $this->dispatch('accountsSwapped');
    }

    // محاسبه خودکار مبلغ دریافت بر اساس نرخ ارز
    public function calculateReceivedAmount()
    {
        if ($this->withdrawal_amount && $this->currency_rate && $this->from_currency && $this->to_currency) {
            $fromCurrency = $this->from_currency;
            $toCurrency = $this->to_currency;
            $amount = floatval($this->withdrawal_amount);
            $rate = floatval($this->currency_rate);

            $shouldDivide = $this->shouldUseDivision($fromCurrency, $toCurrency);

            if ($shouldDivide) {
                $this->received_amount = number_format($amount / $rate, 2, '.', '');
            } else {
                $this->received_amount = number_format($amount * $rate, 2, '.', '');
            }

            // تبدیل به حروف
            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($this->received_amount, 'receivedAmountInWords');
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');
        } else {
            $this->received_amount = '';
            $this->withdrawalAmountInWords = '';
            $this->receivedAmountInWords = '';
            $this->currencyRateInWords = '';
        }
    }

    private function shouldUseDivision($fromCurrency, $toCurrency)
    {
        // منطق محاسبه بر اساس نوع ارز
        $baseCurrencies = ['usd', 'eur', 'gbp'];
        $localCurrencies = ['afn', 'irr', 'pkr'];

        if (in_array($fromCurrency, $baseCurrencies) && in_array($toCurrency, $localCurrencies)) {
            return false; // ضرب
        }

        if (in_array($fromCurrency, $localCurrencies) && in_array($toCurrency, $baseCurrencies)) {
            return true; // تقسیم
        }

        return true;
    }

    // Event Listeners برای محاسبه خودکار
    public function updated($property)
    {
        if (in_array($property, [
            'withdrawal_amount',
            'currency_rate',
            'from_currency',
            'to_currency',
            'transactionType'
        ])) {
            $this->calculateReceivedAmount();
        }

        // تبدیل مستقیم برای فیلدهای خاص
        if ($property === 'withdrawal_amount') {
            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
        }

        if ($property === 'currency_rate') {
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');
        }
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

    public function updateCustomerCurrencyBalance()
    {
        if (!$this->withdrawalCustomerId) {
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

            // ریست کردن موجودی‌های تفکیک شده
            $this->customerCashBalances = [];
            $this->customerBankBalances = [];
            $this->customerTotalBalances = [];
            return;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $transactions = Transaction::where('customer_id', $this->withdrawalCustomerId)
            ->where('admin_id', $adminId)
            ->get();

        // محاسبه موجودی‌های نقدی و بانکی جداگانه
        $cashBalances = [
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

        $bankBalances = [
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

            if ($transaction->account_type === 'نقدی') {
                if (array_key_exists($currencyName, $cashBalances)) {
                    $cashBalances[$currencyName] += $amount;
                }
            } else {
                if (array_key_exists($currencyName, $bankBalances)) {
                    $bankBalances[$currencyName] += $amount;
                }
            }
        }

        $latestExchangeRate = ExchangeRates::latest()->first();
        $exchangeRates = [
            'افغانی' => $latestExchangeRate->afn_buy ?? 0.011,
            'دالر' => 1,
            'تومان' => $latestExchangeRate->irr_buy ?? 0.000024,
            'یورو' => $latestExchangeRate->eur_buy ?? 1.07,
            'کلدار' => $latestExchangeRate->pkr_buy ?? 0.0036,
            'درهم' => $latestExchangeRate->aed_buy ?? 0.27,
            'لیره' => $latestExchangeRate->try_buy ?? 0.031,
            'یوان' => $latestExchangeRate->cny_buy ?? 0.14,
            'روپیه' => 0.14,
        ];

        // محاسبه مجموع برای نمایش در کارت‌های اصلی
        $totalBalances = [];
        foreach ($cashBalances as $currency => $balance) {
            $totalBalances[$currency] = $balance + $bankBalances[$currency];
        }

        $totalInUsd = 0;
        foreach ($totalBalances as $currency => $balance) {
            if ($currency !== 'خلاصه بیلانس به دالر' && isset($exchangeRates[$currency])) {
                $totalInUsd += $balance * $exchangeRates[$currency];
            }
        }

        $this->currenciesdefault = [
            ['name' => 'افغانی', 'value' => $totalBalances['افغانی']],
            ['name' => 'دالر', 'value' => $totalBalances['دالر']],
            ['name' => 'تومان', 'value' => $totalBalances['تومان']],
            ['name' => 'یورو', 'value' => $totalBalances['یورو']],
            ['name' => 'کلدار', 'value' => $totalBalances['کلدار']],
            ['name' => 'درهم', 'value' => $totalBalances['درهم']],
            ['name' => 'لیره', 'value' => $totalBalances['لیره']],
            ['name' => 'یوان', 'value' => $totalBalances['یوان']],
            ['name' => 'روپیه', 'value' => $totalBalances['روپیه']],
            ['name' => 'خلاصه بیلانس به دالر', 'value' => $totalInUsd],
        ];

        // ذخیره موجودی‌های تفکیک شده برای نمایش در کارت‌های جدید
        $this->customerCashBalances = $cashBalances;
        $this->customerBankBalances = $bankBalances;
        $this->customerTotalBalances = $totalBalances;
    }

    private function resetCurrenciesDefault()
    {
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
    }

    private function calculateBalances($transactions)
    {
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

        return $balances;
    }

    private function calculateTotalInUsd($balances)
    {
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

        $totalInUsd = 0;
        foreach ($balances as $currency => $balance) {
            if (isset($exchangeRates[$currency])) {
                $totalInUsd += $balance * $exchangeRates[$currency];
            }
        }

        return $totalInUsd;
    }

    private function updateCurrenciesDefault($balances, $totalInUsd)
    {
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

    // متد برای انتخاب حساب برداشت
    public function selectWithdrawalAccount($customerId)
    {
        $this->withdrawalCustomerId = $customerId;
        $this->withdrawalAccount = $customerId;
        $this->updateCustomerCurrencyBalance($customerId);
    }

    // متد برای انتخاب حساب دریافت
    public function selectDepositAccount($customerId)
    {
        $this->depositCustomerId = $customerId;
        $this->depositAccount = $customerId;
    }

    public function submitConversion()
    {
        $this->validate([
            'withdrawalAccount' => 'required|integer|exists:sarafi.customers,id',
            'depositAccount' => 'required|integer|exists:sarafi.customers,id',
            'from_currency' => 'required|string',
            'to_currency' => 'required|string',
            'from_account' => 'required|string',
            'to_account' => 'required|string',
            'withdrawal_amount' => 'required|numeric|min:0.01',
            'received_amount' => 'required|numeric|min:0.01',
            'currency_rate' => 'required|numeric|min:0.0001',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'zone_sender' => 'required|string',
            'zone_receiver' => 'required|string',
            'by_sender' => 'nullable|string|max:255',
            'by_receiver' => 'nullable|string|max:255',
        ]);

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // شروع تراکنش دیتابیس
        DB::connection('sarafi')->beginTransaction();

        try {
            if ($this->editingConversionId) {
                // حالت ویرایش
                $conversion = ConversionTransfers::find($this->editingConversionId);

                if ($conversion) {
                    // حذف تراکنش‌های قبلی با استفاده از رابطه
                    Transaction::where('conversion_transfer_id', $conversion->id)->delete();

                    // آپدیت رکورد تبدیل ارز
                    $conversion->update([
                        'form_customer' => $this->withdrawalAccount,
                        'from_currency' => $this->from_currency,
                        'withdrawal_amount' => $this->withdrawal_amount,
                        'to_customer' => $this->depositAccount,
                        'to_currency' => $this->to_currency,
                        'from_account' => $this->from_account,
                        'to_account' => $this->to_account,
                        'received_amount' => $this->received_amount,
                        'currency_rate' => $this->currency_rate,
                        'transaction_date' => $this->transaction_date,
                        'description' => $this->description,
                        'zone_sender' => $this->zone_sender,
                        'zone_receiver' => $this->zone_receiver,
                        'by_sender' => $this->by_sender,
                        'by_receiver' => $this->by_receiver,
                        'type' => $this->transactionType,
                    ]);

                    $conversionId = $conversion->id;
                }
            } else {
                // حالت ایجاد جدید
                $conversion = ConversionTransfers::create([
                    'form_customer' => $this->withdrawalAccount,
                    'from_currency' => $this->from_currency,
                    'withdrawal_amount' => $this->withdrawal_amount,
                    'to_customer' => $this->depositAccount,
                    'to_currency' => $this->to_currency,
                    'from_account' => $this->from_account,
                    'to_account' => $this->to_account,
                    'received_amount' => $this->received_amount,
                    'currency_rate' => $this->currency_rate,
                    'transaction_date' => $this->transaction_date,
                    'description' => $this->description,
                    'zone_sender' => $this->zone_sender,
                    'zone_receiver' => $this->zone_receiver,
                    'by_sender' => $this->by_sender,
                    'by_receiver' => $this->by_receiver,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'type' => $this->transactionType,
                ]);

                $conversionId = $conversion->id;
            }

            // ایجاد تراکنش برداشت برای حساب مبدا
            Transaction::create([
                'customer_id' => $this->withdrawalAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'account_type'=>$this->from_account,
                'currency' => $this->from_currency,
                'amount' => $this->withdrawal_amount,
                'type' => 'برداشت',
                'date' => $this->transaction_date,
                'description' => $this->description . ' - تبدیل به ' . $this->getCurrencyName($this->to_currency) . ' (' . $this->transactionType . ')',
                'zone' => $this->zone_sender,
                'by' => $this->by_sender,
                'conversion_transfer_id' => $conversionId,
            ]);

            // ایجاد تراکنش دریافت برای حساب مقصد
            Transaction::create([
                'customer_id' => $this->depositAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->to_currency,
                'amount' => $this->received_amount,
                'account_type'=>$this->to_account,
                'type' => 'رسید',
                'date' => $this->transaction_date,
                'description' => $this->description . ' - تبدیل از ' . $this->getCurrencyName($this->from_currency) . ' (' . $this->transactionType . ')',
                'zone' => $this->zone_receiver,
                'by' => $this->by_receiver,
                'conversion_transfer_id' => $conversionId,
            ]);

            DB::connection('sarafi')->commit();

            $message = $this->editingConversionId ? 'تبدیل ارز با موفقیت ویرایش شد.' : 'تبدیل ارز با موفقیت ثبت شد.';
            session()->flash('message', $message);

            $this->resetForm();
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();
            session()->flash('error', 'خطا در ثبت تبدیل ارز: ' . $e->getMessage());

            Log::error('Conversion transfer error: ' . $e->getMessage(), [
                'withdrawalAccount' => $this->withdrawalAccount,
                'depositAccount' => $this->depositAccount,
                'transactionType' => $this->transactionType,
                'editing' => $this->editingConversionId ? 'yes' : 'no',
            ]);
        }
    }

    public function resetForm()
    {
        $this->reset([
            'withdrawalAccount',
            'depositAccount',
            'withdrawalCustomerId',
            'depositCustomerId',
            'from_currency',
            'to_currency',
            'withdrawal_amount',
            'received_amount',
            'currency_rate',
            'description',
            'zone_sender',
            'zone_receiver',
            'by_sender',
            'by_receiver',
            'editingConversionId',
            'withdrawalAmountInWords',
            'receivedAmountInWords',
            'currencyRateInWords',
        ]);

        $this->transactionType = 'خرید';
        $this->transaction_date = Jalalian::now()->format('Y/m/d');
    }

    public function editConversion($conversionId)
    {
        $conversion = ConversionTransfers::with(['fromCustomer', 'toCustomer'])->find($conversionId);

        if ($conversion) {
            // تنظیم ID تبدیل برای ویرایش
            $this->editingConversionId = $conversionId;

            // تنظیم مقادیر برای ویرایش
            $this->withdrawalAccount = $conversion->form_customer;
            $this->depositAccount = $conversion->to_customer;
            $this->from_currency = $conversion->from_currency;
            $this->to_currency = $conversion->to_currency;
            $this->withdrawal_amount = $conversion->withdrawal_amount;
            $this->from_account = $conversion->from_account;
            $this->to_account = $conversion->to_account;
            $this->received_amount = $conversion->received_amount;
            $this->currency_rate = $conversion->currency_rate;
            $this->transaction_date = $conversion->transaction_date;
            $this->description = $conversion->description;
            $this->zone_sender = $conversion->zone_sender;
            $this->zone_receiver = $conversion->zone_receiver;
            $this->by_sender = $conversion->by_sender;
            $this->by_receiver = $conversion->by_receiver;
            $this->transactionType = $conversion->type;

            // تنظیم customer IDs برای Alpine.js
            $this->withdrawalCustomerId = $conversion->form_customer;
            $this->depositCustomerId = $conversion->to_customer;

            // تبدیل مقادیر به حروف
            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($this->received_amount, 'receivedAmountInWords');
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');

            // به‌روزرسانی موجودی‌ها
            $this->updateCustomerCurrencyBalance($conversion->form_customer);

            // dispatch events برای به‌روزرسانی Alpine.js
            $this->dispatch('edit-mode-activated', [
                'withdrawalAccount' => $this->withdrawalAccount,
                'depositAccount' => $this->depositAccount,
                'withdrawalCustomer' => $conversion->fromCustomer ? $conversion->fromCustomer->account_number . ' - ' . $conversion->fromCustomer->fullname : '',
                'depositCustomer' => $conversion->toCustomer ? $conversion->toCustomer->account_number . ' - ' . $conversion->toCustomer->fullname : ''
            ]);
        }
    }

    public function confirmDelete($conversionId)
    {
        $this->confirmDeleteId = $conversionId;
    }

    public function deleteConversion()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        DB::connection('sarafi')->beginTransaction();

        try {
            $conversion = ConversionTransfers::find($this->confirmDeleteId);

            if ($conversion) {
                // حذف تراکنش‌های مرتبط
                Transaction::where('conversion_transfer_id', $conversion->id)->delete();

                // حذف تبدیل ارز
                $conversion->delete();

                DB::connection('sarafi')->commit();
                session()->flash('message', 'تبدیل ارز با موفقیت حذف شد.');
                $this->confirmDeleteId = null;
            }
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();
            session()->flash('error', 'خطا در حذف تبدیل ارز: ' . $e->getMessage());
            Log::error('Delete conversion error: ' . $e->getMessage());
            $this->confirmDeleteId = null;
        }
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


    /**
     * Generate PDF for conversion transaction
     */
    private function generateConversionPdf($conversionId)
    {
        try {
            $conversion = ConversionTransfers::with(['fromCustomer', 'toCustomer', 'user'])->findOrFail($conversionId);

            // Check user access
            $user = Auth::guard('sarafi')->user();
            if ($conversion->user_id !== $user->id && $conversion->admin_id !== $user->id) {
                session()->flash('error', 'دسترسی به این تراکنش مجاز نیست.');
                return null;
            }

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [85, 220],
                'directionality' => 'rtl',
                'margin_top' => 5,
                'margin_bottom' => 5,
                'margin_left' => 5,
                'margin_right' => 5,
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

            $html = view('pdf.Sarafi.conversion-transaction', compact('conversion'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'تبدیل_ارز_' . $conversion->id . '_' . $conversion->type . '.pdf';

            return response()->streamDownload(function () use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $fileName);
        } catch (\Exception $e) {
            Log::error('PDF generation error: ' . $e->getMessage());
            session()->flash('error', 'خطا در ایجاد PDF: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Print conversion transaction as PDF
     */
    public function printTransaction($conversionId)
    {
        try {
            $conversion = ConversionTransfers::findOrFail($conversionId);

            // Check user access
            $user = Auth::guard('sarafi')->user();
            if ($conversion->user_id !== $user->id && $conversion->admin_id !== $user->id) {
                session()->flash('error', 'دسترسی به این تراکنش مجاز نیست.');
                return redirect()->back();
            }

            return $this->generateConversionPdf($conversion->id);
        } catch (\Exception $e) {
            Log::error('Print conversion error: ' . $e->getMessage());
            session()->flash('error', 'خطا در چاپ تراکنش: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function mount()
    {
        $this->transaction_date = Jalalian::now()->format('Y/m/d');
        $this->transactionType = 'خرید';

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

        $user = Auth::guard('sarafi')->user();
        if ($user) {
            $adminId = $user->admin_id ?? $user->id;
            $this->loadCustomers($adminId);
               $this->loadZones($adminId);
        }
    }
     private function loadZones($adminId)
    {
        $this->zones = \App\Models\Sarafi\User::where(function ($query) use ($adminId) {
            $query->where('admin_id', $adminId)
                ->orWhere('id', $adminId);
        })
            ->whereNotNull('zone')
            ->where('zone', '!=', '')
            ->pluck('zone')
            ->unique()
            ->values()
            ->toArray();


        if (empty($this->zones)) {
            $this->zones = ['غرب', 'مرکز', 'شمال', 'جنوب', 'شرق'];
        }
    }
}
