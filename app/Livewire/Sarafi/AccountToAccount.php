<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\User;
use App\Models\Sarafi\SendToAccount;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use NumberFormatter;

class AccountToAccount extends Component
{
    use WithPagination;

    // حساب برداشت
    public $withdrawalAccount = null;
    public $withdrawalCustomerId;
    public $withdrawalCustomer = null;
    public $transferable_amount_changed = false;
    public $commission_amount_changed = false;



    // حساب دریافت
    public $depositAccount;
    public $depositCustomerId;
    public $from_account = 'نقدی';
    public $to_account = 'نقدی';

    // حساب کمیشن
    public $commissionAccount;
    public $commissionCustomerId;


    public $commissionAmountInWords = '';
    // اطلاعات تبدیل ارز
    public $currency = '';
    public $withdrawal_amount = '';
    public $commission_amount = '';
    public $transferable_amount = '';
    public $received_amount = '';
    public $date;


    public $description_sender = '';
    public $description_receiver = '';
    public $zone_sender = '';
    public $zone_receiver = '';
    public $by_sender = '';
    public $by_receiver = '';

    // برای نمایش حروفی
    public $withdrawalAmountInWords = '';
    public $receivedAmountInWords = '';

    public $transactionType = 'باتفاوت';
    public $currencies = [];
    public $customers = [];
    public $search = '';

    public $filteredCustomers;
    public $accountSearch = '';
    public $confirmDeleteId = null;
    public $editingConversionId = null;

    public $documentNumber;
    public $zones = [];

    // اضافه کردن متغیرهای جدید برای نمایش موجودی‌ها
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
        ['name' => 'روپیه', 'value' => 0],
        ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
    ];

    public function render()
    {
        $user = Auth::guard('sarafi')->user();

        if (!$user) {
            return view('livewire.sarafi.account-to-account', [
                'customers' => collect(),
                'SendToAccount' => collect(),
            ]);
        }

        $adminId = $user->admin_id ?? $user->id;

        // بارگذاری مشتریان اگر خالی است
        if (empty($this->customers)) {
            $this->loadCustomers($adminId);
        }

        // ایجاد کوئری پایه با join
        $query = SendToAccount::select(
            'account_to_account.*',
            'from_customer.fullname as from_customer_name',
            'from_customer.account_number as from_customer_account',
            'to_customer.fullname as to_customer_name',
            'to_customer.account_number as to_customer_account'
        )
            ->leftJoin('customers as from_customer', 'account_to_account.form_customer', '=', 'from_customer.id')
            ->leftJoin('customers as to_customer', 'account_to_account.to_customer', '=', 'to_customer.id')
            ->where('account_to_account.admin_id', $adminId);

        // اعمال جستجو اگر مقدار وجود دارد
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('from_customer.fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('from_customer.account_number', 'like', '%' . $this->search . '%')
                    ->orWhere('to_customer.fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('to_customer.account_number', 'like', '%' . $this->search . '%')
                    ->orWhere('account_to_account.currency', 'like', '%' . $this->search . '%')
                    ->orWhere('account_to_account.withdrawal_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('account_to_account.received_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('account_to_account.description_sender', 'like', '%' . $this->search . '%')
                    ->orWhere('account_to_account.description_receiver', 'like', '%' . $this->search . '%')
                    ->orWhere('account_to_account.type', 'like', '%' . $this->search . '%')
                    ->orWhere('account_to_account.zone_sender', 'like', '%' . $this->search . '%')
                    ->orWhere('account_to_account.zone_receiver', 'like', '%' . $this->search . '%');
            });
        }

        $SendToAccount = $query->latest('account_to_account.created_at')->paginate(10);

        return view('livewire.sarafi.account-to-account', [
            'customers' => collect($this->customers),
            'SendToAccount' => $SendToAccount,
        ]);
    }

    public function showReport()
    {
        if (!$this->withdrawalCustomerId) {
            session()->flash('error', 'لطفاً ابتدا یک مشتری را برای حساب برداشت انتخاب کنید');
            return redirect()->back();
        }

        $customer = Customer::find($this->withdrawalCustomerId);
        if (!$customer) {
            session()->flash('error', 'مشتری انتخاب شده یافت نشد');
            return redirect()->back();
        }

        session([
            'selected_customer_id' => $this->withdrawalCustomerId,
            'selected_customer_name' => $customer->fullname,
            'selected_customer_account' => $customer->account_number
        ]);

        return redirect()->route('sarafi.transaction-reports');
    }

    public function search()
    {
        $this->resetPage();
    }

    private function loadCustomers($adminId)
    {
        // دریافت IDهای کاربران مرتبط (اختیاری - برای backward compatibility)
        $relatedUserIds = \App\Models\Sarafi\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        // روش 1: استفاده از orWhereHas برای مشتریان لینک شده
        $this->customers = Customer::select('id', 'account_number', 'fullname', 'admin_id')
            ->with(['admins' => function ($query) use ($adminId) {
                $query->where('customer_admin.admin_id', $adminId);
            }])
            ->where(function ($query) use ($adminId, $relatedUserIds) {
                // مشتریانی که مال این ادمین هستند
                $query->where('admin_id', $adminId)
                    // یا مشتریانی که به این ادمین لینک شده‌اند
                    ->orWhereHas('admins', function ($q) use ($adminId) {
                        $q->where('customer_admin.admin_id', $adminId);
                    })
                    // یا مشتریانی که تراکنش با این ادمین دارند (برای backward compatibility)
                    ->orWhereHas('transactions', function ($t) use ($relatedUserIds) {
                        $t->whereIn('user_id', $relatedUserIds)
                            ->orWhereIn('admin_id', $relatedUserIds);
                    });
            })
            ->orderBy('fullname')
            ->get()
            ->map(function ($customer) use ($adminId) {
                return [
                    'id' => $customer->id,
                    'account_number' => $customer->account_number,
                    'fullname' => $customer->fullname,
                    'admin_id' => $customer->admin_id,
                    'is_mine' => $customer->admin_id == $adminId,
                    'is_linked' => $customer->admins->isNotEmpty() && $customer->admin_id != $adminId
                ];
            })
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
        $this->transactionType = $this->transactionType === 'باتفاوت'
            ? 'بدون تفاوت'
            : 'باتفاوت';

        if ($this->transactionType === 'بدون تفاوت') {
            // وقتی بدون تفاوت است
            $this->commission_amount = 0; // کمیشن صفر
            $this->transferable_amount = $this->withdrawal_amount; // مبلغ رسید برابر برداشت
            $this->commissionAccount = '';

            // تبدیل اعداد به متن فارسی
            $this->withdrawalAmountInWords = $this->convertNumberToWords($this->withdrawal_amount);
            $this->receivedAmountInWords = $this->convertNumberToWords($this->transferable_amount);
        } else {
            // اگر دوباره باتفاوت شد، محاسبات را انجام بده
            $this->calculateAmounts();
        }
    }


    public function calculateAmounts()
    {
        $withdrawal = floatval($this->withdrawal_amount ?: 0);

        if ($this->transactionType === 'باتفاوت') {
            // فقط وقتی کاربر فیلد قابل انتقال یا کمیشن رو تغییر داد
            if ($this->transferable_amount_changed ?? false) {
                $transferable = floatval($this->transferable_amount ?: 0);
                $this->commission_amount = number_format($withdrawal - $transferable, 2, '.', '');
                $this->received_amount = $transferable;
            } elseif ($this->commission_amount_changed ?? false) {
                $commission = floatval($this->commission_amount ?: 0);
                $this->transferable_amount = number_format($withdrawal - $commission, 2, '.', '');
                $this->received_amount = $this->transferable_amount;
            }
        } else {
            // حالت بدون تفاوت
            $this->received_amount = $withdrawal;
        }

        $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
        $this->convertAmountToWords($this->received_amount, 'receivedAmountInWords');
    }

    public function updated($property, $value)
    {
        // تبدیل رشته به عدد
        $withdrawal_amount = (float) $this->withdrawal_amount;
        $transferable_amount = (float) $this->transferable_amount;
        $commission_amount = (float) $this->commission_amount;

        // محاسبه اتوماتیک مبالغ
        if ($property === 'withdrawal_amount') {
            if (!$this->transferable_amount_changed) {
                $this->transferable_amount = $withdrawal_amount - $commission_amount;
            }
            if (!$this->commission_amount_changed) {
                $this->commission_amount = $withdrawal_amount - $transferable_amount;
            }
        }

        if ($property === 'transferable_amount') {
            $this->transferable_amount_changed = true;
            $this->commission_amount = $withdrawal_amount - $transferable_amount;
        }

        if ($property === 'commission_amount') {
            $this->commission_amount_changed = true;
            $this->transferable_amount = $withdrawal_amount - $commission_amount;
        }

        if ($property === 'withdrawal_amount' && $this->transactionType === 'بدون تفاوت') {
            $this->transferable_amount = (float) $this->withdrawal_amount;
            $this->receivedAmountInWords = $this->convertNumberToWords($this->transferable_amount);
            $this->withdrawalAmountInWords = $this->convertNumberToWords($this->withdrawal_amount);
        }


        // به‌روز رسانی متن فارسی
        $this->withdrawalAmountInWords = $this->convertNumberToWords($withdrawal_amount);
        $this->receivedAmountInWords = $this->convertNumberToWords($this->transferable_amount);
        $this->commissionAmountInWords = $this->convertNumberToWords($this->commission_amount);
    }


    private function convertNumberToWords($value)
    {
        if (!is_numeric($value)) {
            $value = 0;
        }

        $number = (float) $value;

        try {
            $formatter = new \NumberFormatter('fa_AF', \NumberFormatter::SPELLOUT);
            $words = $formatter->format($number);

            // اصلاحات مخصوص افغانستان
            $replacements = [
                'دویست' => 'دو صد',
                'سیصد' => 'سه صد',
                'چهارصد' => 'چهار صد',
                'پانصد' => 'پنج صد',
                'ششصد' => 'شش صد',
                'هفتصد' => 'هفت صد',
                'هشتصد' => 'هشت صد',
                'نهصد' => 'نه صد',
            ];

            $words = str_replace(array_keys($replacements), array_values($replacements), $words);

            return $words;
        } catch (\Exception $e) {
            Log::error('Error converting amount to words: ' . $e->getMessage());
            return '';
        }
    }


    // اگر خواستید بعداً تغییر دستی کاربر رو ریست کنید
    public function resetChanges()
    {
        $this->transferable_amount_changed = false;
        $this->commission_amount_changed = false;
    }


    public function updatedTransferableAmount()
    {
        $this->transferable_amount_changed = true;
        $this->commission_amount_changed = false;
        $this->calculateAmounts();
    }

    public function updatedCommissionAmount()
    {
        $this->commission_amount_changed = true;
        $this->transferable_amount_changed = false;
        $this->calculateAmounts();
    }






    public function filterCustomers()
    {
        if (empty($this->accountSearch)) {
            $this->filteredCustomers = collect($this->customers);
            return;
        }

        $search = $this->accountSearch;
        $this->filteredCustomers = collect($this->customers)->filter(function ($customer) use ($search) {
            return str_contains($customer['account_number'], $search) ||
                str_contains($customer['fullname'], $search);
        });
    }

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
            'روپیه' => 0.012,
        ];

        $totalInUsd = 0;
        foreach ($balances as $currency => $balance) {
            if (isset($exchangeRates[$currency])) {
                $totalInUsd += $balance * $exchangeRates[$currency];
            }
        }

        return round($totalInUsd, 2);
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

    public function selectWithdrawalAccount($customerId)
    {
        Log::info('selectWithdrawalAccount called', ['customer_id' => $customerId]);

        $this->withdrawalAccount = $customerId;
        $this->withdrawalCustomerId = $customerId;
        $this->withdrawalCustomer = Customer::find($customerId);

        $this->accountSearch = '';
        $this->filteredCustomers = null;

        // فراخوانی متد محاسبه بیلانس
        $this->updateCustomerCurrencyBalance();

        Log::debug('After selecting withdrawal account', [
            'customer_id' => $customerId,
            'withdrawalCustomerId' => $this->withdrawalCustomerId,
            'customer_name' => $this->withdrawalCustomer ? $this->withdrawalCustomer->fullname : 'Not found'
        ]);
    }

    public function selectDepositAccount($customerId)
    {
        $this->depositCustomerId = $customerId;
        $this->depositAccount = $customerId;
        $this->accountSearch = '';
        $this->filteredCustomers = null;
    }

    public function selectCommissionAccount($customerId)
    {
        $this->commissionCustomerId = $customerId;
        $this->commissionAccount = $customerId;
        $this->accountSearch = '';
        $this->filteredCustomers = null;
    }

    /**
     * برگرداندن تغییرات صندوق
     */
    private function reverseSafeChanges(SendToAccount $conversion)
    {
        $adminId = $conversion->admin_id;
        $currencyColumn = strtolower($conversion->currency);

        // اگر از نقدی به بانکی بوده، برگرداندن: به نقدی اضافه، از بانکی کم
        if ($conversion->from_account === 'نقدی' && $conversion->to_account === 'بانکی') {
            $amount = $conversion->received_amount;

            // اضافه کردن به صندوق نقدی
            $safe = CurrencySafe::where('admin_id', $adminId)->first();
            if ($safe) {
                $safe->increment($currencyColumn, $amount);
                Log::info("Reverse نقدی→بانکی: اضافه کردن {$amount} {$currencyColumn} به صندوق نقدی");
            }

            // کم کردن از صندوق بانکی
            $bank = BankAccount::where('admin_id', $adminId)->first();
            if ($bank) {
                $bank->decrement($currencyColumn, $amount);
                Log::info("Reverse نقدی→بانکی: کم کردن {$amount} {$currencyColumn} از صندوق بانکی");
            }
        }

        // اگر از بانکی به نقدی بوده، برگرداندن: به بانکی اضافه، از نقدی کم
        if ($conversion->from_account === 'بانکی' && $conversion->to_account === 'نقدی') {
            $amount = $conversion->withdrawal_amount;

            // اضافه کردن به صندوق بانکی
            $bank = BankAccount::where('admin_id', $adminId)->first();
            if ($bank) {
                $bank->increment($currencyColumn, $amount);
                Log::info("Reverse بانکی→نقدی: اضافه کردن {$amount} {$currencyColumn} به صندوق بانکی");
            }

            // کم کردن از صندوق نقدی
            $safe = CurrencySafe::where('admin_id', $adminId)->first();
            if ($safe) {
                $safe->decrement($currencyColumn, $amount);
                Log::info("Reverse بانکی→نقدی: کم کردن {$amount} {$currencyColumn} از صندوق نقدی");
            }
        }

        Log::info("تغییرات صندوق برای انتقال ID {$conversion->id} برگردانده شد");
    }

    /**
     * اعمال تغییرات صندوق
     */
    private function applySafeChanges()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $currencyColumn = strtolower($this->currency);

        // ===== نقدی → بانکی =====
        if ($this->from_account === 'نقدی' && $this->to_account === 'بانکی') {
            $amount = $this->transactionType === 'باتفاوت'
                ? floatval($this->withdrawal_amount) - floatval($this->commission_amount)
                : floatval($this->withdrawal_amount);

            // کم کردن از صندوق نقدی
            $safe = CurrencySafe::firstOrCreate([
                'admin_id' => $adminId,
            ], [
                'user_id' => $user->id,
            ]);
            $safe->decrement($currencyColumn, $amount);
            Log::info("نقدی→بانکی: کم کردن {$amount} {$currencyColumn} از صندوق نقدی");

            // اضافه کردن به صندوق بانکی
            $bank = BankAccount::firstOrCreate([
                'admin_id' => $adminId,
            ], [
                'user_id' => $user->id,
            ]);
            $bank->increment($currencyColumn, $amount);
            Log::info("نقدی→بانکی: اضافه کردن {$amount} {$currencyColumn} به صندوق بانکی");
        }

        // ===== بانکی → نقدی =====
        if ($this->from_account === 'بانکی' && $this->to_account === 'نقدی') {
            $amount = floatval($this->withdrawal_amount);

            // کم کردن از صندوق بانکی
            $bank = BankAccount::where('admin_id', $adminId)->first();
            if ($bank) {
                $bank->decrement($currencyColumn, $amount);
                Log::info("بانکی→نقدی: کم کردن {$amount} {$currencyColumn} از صندوق بانکی");
            }

            // اضافه کردن به صندوق نقدی
            $safe = CurrencySafe::firstOrCreate([
                'admin_id' => $adminId,
            ], [
                'user_id' => $user->id,
            ]);
            $safe->increment($currencyColumn, $amount);
            Log::info("بانکی→نقدی: اضافه کردن {$amount} {$currencyColumn} به صندوق نقدی");
        }

        // ===== نقدی → نقدی یا بانکی → بانکی =====
        // در این حالت‌ها نیازی به تغییر صندوق نیست
        if (($this->from_account === 'نقدی' && $this->to_account === 'نقدی') ||
            ($this->from_account === 'بانکی' && $this->to_account === 'بانکی')
        ) {
            Log::info("{$this->from_account}→{$this->to_account}: نیازی به تغییر صندوق نیست");
        }
    }

    public function submitConversion()
    { 

        $validationRules = [
            'withdrawalAccount' => 'required|integer|exists:sarafi.customers,id',
            'depositAccount' => 'required|integer|exists:sarafi.customers,id',
            'currency' => 'required|string',
            'documentNumber' => 'required|integer|min:1',
            'withdrawal_amount' => 'required|numeric',
            'from_account' => 'required|string',
            'to_account' => 'required|string',
            'date' => 'required|date',
            'description_sender' => 'nullable|string|max:500',
            'description_receiver' => 'nullable|string|max:500',
            'zone_sender' => 'required|string',
            'zone_receiver' => 'required|string',
            'by_sender' => 'nullable|string|max:255',
            'by_receiver' => 'nullable|string|max:255',
        ];

        if ($this->transactionType === 'باتفاوت') {
            $validationRules['commission_amount'] = 'required|numeric';
            $validationRules['commissionAccount'] = 'required|integer|exists:sarafi.customers,id';
        }

        $this->validate($validationRules);

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        DB::connection('sarafi')->beginTransaction();

$senderCustomer = Customer::find($this->withdrawalAccount);
$receiverCustomer = Customer::find($this->depositAccount);

$senderName = $senderCustomer?->fullname ?? 'نامشخص';
$receiverName = $receiverCustomer?->fullname ?? 'نامشخص';
        try {
            // محاسبه مبلغ دریافتی
            $receivedAmount = $this->transactionType === 'باتفاوت'
                ? floatval($this->withdrawal_amount) - floatval($this->commission_amount)
                : floatval($this->withdrawal_amount);

            // اگر در حال ویرایش هستیم، ابتدا تغییرات قبلی را برگردانیم
            if ($this->editingConversionId) {
                $oldConversion = SendToAccount::find($this->editingConversionId);
                if ($oldConversion) {
                    // برگرداندن تغییرات صندوق قبلی
                    $this->reverseSafeChanges($oldConversion);
                }
            }

            // داده‌های اصلی
            $conversionData = [
                'form_customer' => $this->withdrawalAccount,
                'currency' => $this->currency,
                'document_number' => $this->documentNumber,
                'withdrawal_amount' => $this->withdrawal_amount,
                'to_customer' => $this->depositAccount,
                'received_amount' => $receivedAmount,
                'transaction_date' => $this->date,
                'from_account' => $this->from_account,
                'to_account' => $this->to_account,
                'description_sender' => $this->description_sender,  
                'description_receiver' => $this->description_receiver,
                'zone_sender' => $this->zone_sender,
                'zone_receiver' => $this->zone_receiver,
                'by_sender' => $this->by_sender,
                'by_receiver' => $this->by_receiver,
                'type' => $this->transactionType,
                'user_id' => $user->id,
                'admin_id' => $adminId,
            ];

            // اضافه کردن فیلدهای مربوط به کمیشن در صورت وجود
            if ($this->transactionType === 'باتفاوت') {
                $conversionData['tax_amount'] = $this->commission_amount;
                $conversionData['tax_id'] = $this->commissionAccount;
            }

            if ($this->editingConversionId) {
                $conversion = SendToAccount::find($this->editingConversionId);

                if ($conversion) {
                    // حذف تراکنش‌های قبلی
                    Transaction::where('account_to_id', $conversion->id)->delete();

                    // آپدیت رکورد
                    $conversion->update($conversionData);
                    $conversionId = $conversion->id;

                    Log::info("انتقال ID {$conversionId} ویرایش شد");
                }
            } else {
                // ایجاد رکورد جدید
                $conversion = SendToAccount::create($conversionData);
                $conversionId = $conversion->id;

                Log::info("انتقال جدید با ID {$conversionId} ایجاد شد");
            }

            // ایجاد تراکنش‌ها
            if ($this->transactionType === 'بدون تفاوت') {
                // حالت بدون تفاوت
                Transaction::create([
                    'customer_id' => $this->withdrawalAccount,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'currency' => $this->currency,
                    'amount' => $this->withdrawal_amount,
                    'type' => 'برد',
                    'date' => $this->date,
                    'account_type' => $this->from_account,
                    'description' => 
                        '    به حساب    '     . $receiverName . 'انتقال یافت',
                    'zone' => $this->zone_sender,
                    'by' => $this->by_sender,
                    'account_to_id' => $conversionId,
                ]);

                Transaction::create([
                    'customer_id' => $this->depositAccount,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'currency' => $this->currency,
                    'amount' => $receivedAmount,
                    'type' => 'رسید',
                    'date' => $this->date,
                    'account_type' => $this->to_account,
                    'description' => '      از حساب    '   . $senderName . ' دریافت شد',
                    'zone' => $this->zone_receiver,
                    'by' => $this->by_receiver,
                    'account_to_id' => $conversionId,
                ]);
            } else {
                // حالت با تفاوت
                Transaction::create([
                    'customer_id' => $this->withdrawalAccount,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'currency' => $this->currency,
                    'amount' => $this->withdrawal_amount,
                    'type' => 'برد',
                    'date' => $this->date,
                    'account_type' => $this->from_account,
                       'description' => 
                        '   به حساب   '    . $receiverName . 'انتقال یافت',
                    'zone' => $this->zone_sender,
                    'by' => $this->by_sender,
                    'account_to_id' => $conversionId,
                ]);

                Transaction::create([
                    'customer_id' => $this->depositAccount,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'currency' => $this->currency,
                    'amount' => $receivedAmount,
                    'type' => 'رسید',
                    'date' => $this->date,
                    'account_type' => $this->to_account,
                                       'description' =>'     از حساب     ' . $senderName . ' دریافت شد',

                        '',
                    'zone' => $this->zone_receiver,
                    'by' => $this->by_receiver,
                    'account_to_id' => $conversionId,
                ]);

                Transaction::create([
                    'customer_id' => $this->commissionAccount,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'currency' => $this->currency,
                    'amount' => $this->commission_amount,
                    'type' => 'رسید',
                    'date' => $this->date,
                    'account_type' => $this->from_account,
                    'description' => 
                        ' بابت کمیشن انتقال از حساب ' . $senderName .
                        '   به حساب  ' . $receiverName,
                    'zone' => $this->zone_sender,
                    'by' => $this->by_sender,
                    'account_to_id' => $conversionId,
                ]);
            }

            // اعمال تغییرات صندوق (نقدی/بانکی)

            DB::connection('sarafi')->commit();

            $message = $this->editingConversionId ? 'انتقال با موفقیت ویرایش شد.' : 'انتقال با موفقیت ثبت شد.';
            session()->flash('message', $message);

            $this->resetForm();
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();
            session()->flash('error', 'خطا در ثبت انتقال: ' . $e->getMessage());

            Log::error('Transfer error: ' . $e->getMessage(), [
                'withdrawalAccount' => $this->withdrawalAccount,
                'depositAccount' => $this->depositAccount,
                'transactionType' => $this->transactionType,
                'editing' => $this->editingConversionId ? 'yes' : 'no',
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function resetForm()
    {
        $this->reset([
            'withdrawalAccount',
            'depositAccount',
            'commissionAccount',
            'withdrawalCustomerId',
            'depositCustomerId',
            'commissionCustomerId',
            'currency',
            'withdrawal_amount',
            'commission_amount',
            'transferable_amount',
            'received_amount',
            'description_sender',
            'description_receiver',
            'zone_sender',
            'zone_receiver',
            'by_sender',
            'by_receiver',
            'editingConversionId',
            'withdrawalAmountInWords',
            'receivedAmountInWords',
        ]);

        $this->transactionType = 'بدون تفاوت';
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->generateDocumentNumber();
        $this->accountSearch = '';
        $this->filteredCustomers = null;
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
            $conversion = SendToAccount::find($this->confirmDeleteId);

            if ($conversion) {

                // حذف تراکنش‌های مرتبط
                Transaction::where('account_to_id', $conversion->id)->delete();

                // حذف رکورد انتقال
                $conversion->delete();

                DB::connection('sarafi')->commit();
                session()->flash('message', 'انتقال با موفقیت حذف شد.');
                $this->confirmDeleteId = null;

                Log::info("انتقال ID {$conversion->id} حذف شد و تغییرات صندوق برگردانده شد");
            }
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();
            session()->flash('error', 'خطا در حذف انتقال: ' . $e->getMessage());
            Log::error('Delete transfer error: ' . $e->getMessage(), [
                'conversion_id' => $this->confirmDeleteId,
                'trace' => $e->getTraceAsString()
            ]);
            $this->confirmDeleteId = null;
        }
    }

    public function editConversion($conversionId)
    {
        $conversion = SendToAccount::with(['fromCustomer', 'toCustomer', 'tax'])->find($conversionId);

        if ($conversion) {
            $this->editingConversionId = $conversionId;
            $this->documentNumber = $conversion->document_number;
            $this->withdrawalAccount = $conversion->form_customer;
            $this->depositAccount = $conversion->to_customer;
            $this->currency = $conversion->currency;
            $this->withdrawal_amount = $conversion->withdrawal_amount;
            $this->received_amount = $conversion->received_amount;
            $this->from_account = $conversion->from_account;
            $this->to_account = $conversion->to_account;
            $this->date = $conversion->transaction_date;
            $this->transferable_amount = $conversion->received_amount;
            $this->documentNumber = $conversion->document_number;


            $this->description_sender = $conversion->description_sender;
            $this->description_receiver = $conversion->description_receiver;
            $this->zone_sender = $conversion->zone_sender;
            $this->zone_receiver = $conversion->zone_receiver;
            $this->by_sender = $conversion->by_sender;
            $this->by_receiver = $conversion->by_receiver;
            $this->transactionType = $conversion->type;

            $this->withdrawalCustomerId = $conversion->form_customer;
            $this->depositCustomerId = $conversion->to_customer;

            if ($this->transactionType === 'باتفاوت') {
                $this->commission_amount = $conversion->tax_amount;
                $this->commissionAccount = $conversion->tax_id;
                $this->commissionCustomerId = $conversion->tax_id;
            }

            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($this->received_amount, 'receivedAmountInWords');

            $this->updateCustomerCurrencyBalance();

            $this->dispatch('edit-mode-activated', [
                'withdrawalAccount' => $this->withdrawalAccount,
                'depositAccount' => $this->depositAccount,
                'commissionAccount' => $this->commissionAccount,
                'withdrawalCustomer' => $conversion->fromCustomer ? $conversion->fromCustomer->account_number . ' - ' . $conversion->fromCustomer->fullname : '',
                'depositCustomer' => $conversion->toCustomer ? $conversion->toCustomer->account_number . ' - ' . $conversion->toCustomer->fullname : '',
                'commissionCustomer' => $conversion->tax_id ? ($conversion->tax ? $conversion->tax->account_number . ' - ' . $conversion->tax->fullname : '') : ''
            ]);

            Log::info("ویرایش انتقال ID {$conversionId} آغاز شد");
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
        $this->editingConversionId = null;
        session()->flash('message', 'ویرایش لغو شد.');
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
        session()->flash('message', 'حذف لغو شد.');
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
            $conversion = SendToAccount::with(['fromCustomer', 'toCustomer', 'user'])->findOrFail($conversionId);

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

            $html = view('pdf.Sarafi.account-to-account', compact('conversion'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'تبدیل بین حسابات' . $conversion->id . '_' . $conversion->type . '.pdf';

            $path = storage_path('app/public/' . $fileName);

            // ذخیره PDF
            $mpdf->Output($path, 'F');

            // ارسال event به JS (Livewire v3)
            $this->dispatch('print-pdf', url: asset('storage/' . $fileName));
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
            $conversion = SendToAccount::findOrFail($conversionId);

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
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->transactionType = 'باتفاوت';

        $this->generateDocumentNumber();

        $this->currencies = [
            ['code' => 'usd', 'name_fa' => 'دالر'],
            ['code' => 'afn', 'name_fa' => 'افغانی'],
            ['code' => 'eur', 'name_fa' => 'یورو'],
            ['code' => 'irr', 'name_fa' => 'تومان'],
            ['code' => 'aed', 'name_fa' => 'درهم'],
            ['code' => 'try', 'name_fa' => 'لیره'],
            ['code' => 'cny', 'name_fa' => 'یوان'],
            ['code' => 'pkr', 'name_fa' => 'کلدار'],
            ['code' => 'inr', 'name_fa' => 'روپیه'],
        ];

        $user = Auth::guard('sarafi')->user();

        if ($user) {
            $adminId = $user->admin_id ?? $user->id;

            $this->loadCustomers($adminId);
            $this->loadZones($adminId);
        }
    }


    private function generateDocumentNumber()
    {
        $latestDocument = SendToAccount::latest('id')->first();
        $this->documentNumber = $latestDocument ? $latestDocument->id + 1 : 1;
    }

    private function loadZones($adminId)
    {
        $zones = \App\Models\Sarafi\User::where(function ($query) use ($adminId) {
            $query->where('id', $adminId)
                ->orWhere('admin_id', $adminId);
        })
            ->whereNotNull('zone')
            ->where('zone', '!=', '')
            ->pluck('zone')
            ->unique()
            ->values()
            ->toArray();

        if (empty($zones)) {
            $zones = ['غرب', 'مرکز', 'شمال', 'جنوب', 'شرق'];
        }

        $this->zones = $zones;

        if (!$this->zone_sender) {
            $this->zone_sender = $zones[0];
        }

        if (!$this->zone_receiver) {
            $this->zone_receiver = $zones[0];
        }
    }
}
