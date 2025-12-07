<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\ConversionTransfers;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\ProfitRate;
use App\Models\Sarafi\Revenue;
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

    // اضافه کردن متغیرهای سود/ضرر
    public $profit_display = '';
    public $loss_display = '';
    public $profit_loss_display = [];
    public $deleting = false;


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
        $this->transactionType = $this->transactionType === 'خرید' ? 'فروش' : 'خرید';

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

        $this->calculateReceivedAmount();
        $this->dispatch('transactionTypeToggled');
    }


    /**
     * محاسبه خودکار مبلغ دریافت بر اساس نرخ ارز
     */
    public function calculateReceivedAmount()
    {
        if ($this->withdrawal_amount && $this->currency_rate && $this->from_currency && $this->to_currency) {

            $fromCurrency = $this->from_currency;
            $toCurrency = $this->to_currency;

            // تبدیل مقادیر به عدد
            $amount = floatval(str_replace(',', '', $this->withdrawal_amount));
            $rate = floatval(str_replace(',', '', $this->currency_rate));

            // بررسی اینکه نرخ ارز صفر نباشد
            if ($rate == 0) {
                $this->received_amount = '';
                $this->receivedAmountInWords = '';
                return;
            }

            $calculatedAmount = 0;

            // -----------------------------
            // *** حالت‌های خاص AFN ↔ IRR ***
            // -----------------------------
            if ($fromCurrency === 'afn' && $toCurrency === 'irr') {
                // افغانی → تومان: (مبلغ × 1000) ÷ نرخ معکوس
                $calculatedAmount = ($amount * 1000) / $rate;
            } elseif ($fromCurrency === 'irr' && $toCurrency === 'afn') {
                // تومان → افغانی: (مبلغ × نرخ معکوس) ÷ 1000
                $calculatedAmount = ($amount * $rate) / 1000;
            }
            // -----------------------------
            //     حالت‌های USD ↔ AFN
            // -----------------------------
            elseif ($fromCurrency === 'afn' && $toCurrency === 'usd') {
                // افغانی → دالر: مبلغ ÷ نرخ مستقیم
                $calculatedAmount = $amount / $rate;
            } elseif ($fromCurrency === 'usd' && $toCurrency === 'afn') {
                // دالر → افغانی: مبلغ × نرخ مستقیم
                $calculatedAmount = $amount * $rate;
            }
            // -----------------------------
            //   سایر تبدیل‌های عمومی
            // -----------------------------
            else {
                $calculatedAmount = $amount * $rate;
            }

            // محدود کردن به 2 رقم اعشار
            $calculatedAmount = round($calculatedAmount, 2);

            // ذخیره
            $this->received_amount = $calculatedAmount;

            // تبدیل به حروف
            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($calculatedAmount, 'receivedAmountInWords');
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');

            // محاسبه سود/ضرر
            $this->calculateRealTimeProfitLoss();
        } else {
            $this->received_amount = '';
            $this->withdrawalAmountInWords = '';
            $this->receivedAmountInWords = '';
            $this->currencyRateInWords = '';
            $this->resetProfitLossDisplay();
        }
    }

    // Event Listeners برای محاسبه خودکار
    public function updated($property)
    {
        $calculationProperties = [
            'withdrawal_amount',
            'currency_rate',
            'from_currency',
            'to_currency',
            'transactionType',
            'from_account',
            'to_account'
        ];

        if (in_array($property, $calculationProperties)) {
            $this->calculateReceivedAmount();
        }

        // تبدیل مستقیم برای فیلدهای خاص
        if ($property === 'withdrawal_amount') {
            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
        }

        if ($property === 'currency_rate') {
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');
        }

        if ($property === 'received_amount') {
            $this->convertAmountToWords($this->received_amount, 'receivedAmountInWords');
        }
    }

    /**
     * محاسبه و نمایش سود/ضرر در زمان واقعی
     */
    public function calculateRealTimeProfitLoss()
    {
        try {
            if (!$this->withdrawal_amount || !$this->currency_rate || !$this->from_currency || !$this->to_currency) {
                $this->resetProfitLossDisplay();
                return;
            }

            $profitLoss = $this->calculateProfitOrLoss();

            $this->profit_loss_display = [
                'profit' => $profitLoss['profit'],
                'loss' => $profitLoss['loss'],
                'predefined_rate' => $profitLoss['predefined_rate'],
                'amount_with_predefined_rate' => $profitLoss['amount_with_predefined_rate'],
                'amount_with_entered_rate' => $profitLoss['amount_with_entered_rate'],
                'difference' => $profitLoss['difference']
            ];

            // به‌روزرسانی نمایش در فرم
            $this->updateProfitLossDisplay();
        } catch (\Exception $e) {
            Log::error('خطا در محاسبه سود/ضرر زمان واقعی: ' . $e->getMessage());
            $this->resetProfitLossDisplay();
        }
    }

    /**
     * به‌روزرسانی نمایش سود/ضرر در فرم
     */
    private function updateProfitLossDisplay()
    {
        $profit = $this->profit_loss_display['profit'] ?? 0;
        $loss = $this->profit_loss_display['loss'] ?? 0;

        if ($profit > 0) {
            $this->profit_display = 'سود: ' . number_format($profit, 4) . ' دالر';
            $this->loss_display = '';
        } elseif ($loss > 0) {
            $this->profit_display = '';
            $this->loss_display = 'ضرر: ' . number_format($loss, 4) . ' دالر';
        } else {
            $this->profit_display = 'بدون سود/ضرر';
            $this->loss_display = '';
        }
    }

    /**
     * ریست نمایش سود/ضرر
     */
    private function resetProfitLossDisplay()
    {
        $this->profit_loss_display = [
            'profit' => 0,
            'loss' => 0,
            'predefined_rate' => 0,
            'amount_with_predefined_rate' => 0,
            'amount_with_entered_rate' => 0,
            'difference' => 0
        ];

        $this->profit_display = '';
        $this->loss_display = '';
    }

    /** 
     * محاسبه سود/ضرر تبدیل ارز - برای انتقال بین حساب‌ها
     */
    private function calculateProfitOrLoss()
    {
        try {
            Log::info('=== شروع محاسبه سود/ضرر ===', [
                'from_currency' => $this->from_currency,
                'to_currency' => $this->to_currency,
                'withdrawal_amount' => $this->withdrawal_amount,
                'received_amount' => $this->received_amount,
                'currency_rate' => $this->currency_rate,
                'transaction_type' => $this->transactionType
            ]);

            // دریافت نرخ از پیش تعیین شده
            $predefinedRate = $this->getTransferPredefinedRate();

            if ($predefinedRate === null || $predefinedRate == 0) {
                Log::warning("نرخ از پیش تعیین شده یافت نشد");
                return $this->getDefaultProfitLossResult();
            }

            // محاسبه مبلغ با نرخ از پیش تعیین شده
            $amountWithPredefinedRate = $this->calculateTransferWithPredefinedRate($predefinedRate);
            $amountWithEnteredRate = floatval($this->received_amount);

            Log::info('مقادیر محاسبه شده', [
                'predefined_rate' => $predefinedRate,
                'amount_with_predefined_rate' => $amountWithPredefinedRate,
                'amount_with_entered_rate' => $amountWithEnteredRate
            ]);

            // محاسبه تفاوت بر اساس نوع تراکنش
            $difference = $amountWithPredefinedRate - $amountWithEnteredRate;

            Log::info('محاسبه تفاوت', [
                'difference' => $difference,
                'formula' => "{$amountWithPredefinedRate} - {$amountWithEnteredRate} = {$difference}",
                'note' => 'مثبت = سود صراف (کمتر پرداخت کرده)'
            ]);

            // سود/ضرر از دیدگاه صراف
            $profit = $difference > 0 ? $difference : 0;
            $loss = $difference < 0 ? abs($difference) : 0;

            Log::info('سود/ضرر در ارز مقصد', [
                'profit' => $profit,
                'loss' => $loss
            ]);

            // تبدیل سود/ضرر به USD
            if ($profit > 0) {
                $profitUsd = $this->convertTransferToUsd($profit, $this->to_currency);
                Log::info('سود تبدیل به USD', ['profit_usd' => $profitUsd]);
                $profit = $profitUsd;
            }

            if ($loss > 0) {
                $lossUsd = $this->convertTransferToUsd($loss, $this->to_currency);
                Log::info('ضرر تبدیل به USD', ['loss_usd' => $lossUsd]);
                $loss = $lossUsd;
            }

            Log::info('نتیجه نهایی در USD', [
                'profit' => $profit,
                'loss' => $loss
            ]);

            return [
                'profit' => round($profit, 4),
                'loss' => round($loss, 4),
                'predefined_rate' => round($predefinedRate, 4),
                'amount_with_predefined_rate' => round($amountWithPredefinedRate, 4),
                'amount_with_entered_rate' => round($amountWithEnteredRate, 4),
                'difference' => round($difference, 4)
            ];
        } catch (\Exception $e) {
            Log::error('❌ خطا در محاسبه سود/ضرر: ' . $e->getMessage());
            return $this->getDefaultProfitLossResult();
        }
    }

    /**
     * دریافت نرخ از پیش تعیین شده برای انتقال
     */
    private function getTransferPredefinedRate()
    {
        $rateType = $this->getTransferRateType(); // 'sell_cash' یا 'sell_bank'

        Log::info("جستجوی نرخ برای انتقال: {$this->from_currency} → {$this->to_currency} با نوع: {$rateType}");

        // حالت خاص: تبدیل AFN ↔ IRR
        if (($this->from_currency === 'afn' && $this->to_currency === 'irr') ||
            ($this->from_currency === 'irr' && $this->to_currency === 'afn')
        ) {

            Log::info("🔍 حالت خاص: تبدیل {$this->from_currency} ↔ {$this->to_currency}");

            $afnProfitRate = ProfitRate::where('source_currency', 'afn')->first();

            if ($afnProfitRate) {
                // تعیین فیلد بر اساس نوع نرخ
                $field = $rateType === 'sell_cash' ? 'irr_sell_cash' : 'irr_sell_bank';
                $inverseRate = $afnProfitRate->{$field} ?? 0;

                Log::info("نرخ معکوس یافت شد از رکورد AFN", [
                    'inverse_rate' => $inverseRate,
                    'rate_field' => $field,
                    'note' => 'هر 1000 تومان = ' . $inverseRate . ' افغانی'
                ]);

                if ($inverseRate > 0) {
                    return $inverseRate;
                }
            }

            Log::warning("❌ نرخ معکوس برای تبدیل {$this->from_currency} ↔ {$this->to_currency} یافت نشد");
            return null;
        }

        // حالت خاص: تبدیل USD ↔ AFN
        if (($this->from_currency === 'usd' && $this->to_currency === 'afn') ||
            ($this->from_currency === 'afn' && $this->to_currency === 'usd')
        ) {

            Log::info("🔍 حالت خاص: تبدیل {$this->from_currency} ↔ {$this->to_currency}");

            $usdProfitRate = ProfitRate::where('source_currency', 'usd')->first();

            if ($usdProfitRate) {
                // برای فروش: همیشه از نرخ فروش استفاده کن
                if ($this->from_currency === 'usd') {
                    // دلار به افغانی (فروش دلار): از نرخ فروش دلار استفاده کن
                    $field = 'afn_' . $rateType; // 'afn_sell_cash' یا 'afn_sell_bank'
                    $rate = $usdProfitRate->{$field} ?? 0;

                    Log::info("نرخ فروش دلار به افغانی", [
                        'rate' => $rate,
                        'rate_field' => $field,
                        'note' => 'هر 1 دلار = ' . $rate . ' افغانی (فروش)'
                    ]);
                } else {
                    // افغانی به دلار (فروش افغانی): معکوس نرخ خرید دلار
                    $field = 'afn_' . str_replace('sell', 'buy', $rateType); // 'afn_buy_cash' یا 'afn_buy_bank'
                    $buyRate = $usdProfitRate->{$field} ?? 0;

                    if ($buyRate > 0) {
                        $rate = 1 / $buyRate; // معکوس نرخ خرید
                        Log::info("معکوس نرخ خرید دلار برای فروش افغانی", [
                            'buy_rate' => $buyRate,
                            'rate' => $rate,
                            'rate_field' => $field,
                            'note' => 'هر 1 افغانی = ' . $rate . ' دلار (بر اساس معکوس نرخ خرید)'
                        ]);
                    } else {
                        $rate = 0;
                    }
                }

                if ($rate > 0) {
                    return $rate;
                }
            }

            Log::warning("❌ نرخ برای تبدیل {$this->from_currency} ↔ {$this->to_currency} یافت نشد");
            return null;
        }

        // استراتژی اصلی: از رکورد USD استفاده کن
        $usdProfitRate = ProfitRate::where('source_currency', 'usd')->first();

        if ($usdProfitRate) {
            // اگر مبدا USD باشد، نرخ مستقیم فروش
            if ($this->from_currency === 'usd') {
                $field = $this->to_currency . '_' . $rateType;
                $rate = $usdProfitRate->{$field} ?? null;

                if ($rate && $rate > 0) {
                    Log::info("✅ نرخ فروش مستقیم از USD: {$this->from_currency} → {$this->to_currency} = {$rate} (نوع: {$rateType})");
                    return $rate;
                }
            }
            // اگر مقصد USD باشد، معکوس نرخ خرید (چون ما داریم ارز دیگر را می‌فروشیم)
            else if ($this->to_currency === 'usd') {
                $buyField = $this->from_currency . '_' . str_replace('sell', 'buy', $rateType);
                $buyRate = $usdProfitRate->{$buyField} ?? null;

                if ($buyRate && $buyRate > 0) {
                    $sellRate = 1 / $buyRate; // معکوس نرخ خرید
                    Log::info("✅ معکوس نرخ خرید به فروش: {$this->from_currency} → {$this->to_currency} = 1/{$buyRate} = {$sellRate} (نوع: {$rateType})");
                    return $sellRate;
                }
            }
            // اگر هیچکدام USD نباشد، محاسبه متقاطع بر اساس نرخ فروش
            else {
                $fromField = $this->from_currency . '_' . $rateType;
                $toField = $this->to_currency . '_' . $rateType;

                $fromRate = $usdProfitRate->{$fromField} ?? null;
                $toRate = $usdProfitRate->{$toField} ?? null;

                if ($fromRate && $toRate && $fromRate > 0 && $toRate > 0) {
                    $crossRate = $toRate / $fromRate;
                    Log::info("✅ نرخ متقاطع فروش: {$this->from_currency} → {$this->to_currency} = {$toRate} ÷ {$fromRate} = {$crossRate} (نوع: {$rateType})");
                    return $crossRate;
                }
            }
        }

        Log::warning("❌ هیچ نرخ مناسبی برای {$this->from_currency} به {$this->to_currency} با نوع {$rateType} یافت نشد");
        return null;
    }

    /**
     * تعیین نوع نرخ مورد نیاز برای انتقال
     */
    private function getTransferRateType()
    {
        // همیشه از نرخ فروش نقدی استفاده می‌کنیم
        return $this->from_account === 'نقدی' ? 'sell_cash' : 'sell_bank';
    }

    /**
     * محاسبه با نرخ از پیش تعیین شده برای انتقال
     */
    private function calculateTransferWithPredefinedRate($predefinedRate)
    {
        $amount = floatval(str_replace(',', '', $this->withdrawal_amount));

        Log::info("محاسبه انتقال با نرخ پیش‌فرض", [
            'amount' => $amount,
            'from_currency' => $this->from_currency,
            'to_currency' => $this->to_currency,
            'predefined_rate' => $predefinedRate,
            'transaction_type' => $this->transactionType
        ]);

        $result = 0;

        // برای تبدیل IRR → AFN
        if ($this->from_currency === 'irr' && $this->to_currency === 'afn') {
            // با فرمول: (مبلغ × نرخ معکوس) ÷ 1000
            $result = ($amount * $predefinedRate) / 1000;
            Log::info("فرمول IRR → AFN: ({$amount} × {$predefinedRate}) ÷ 1000 = {$result}");
        }
        // برای تبدیل AFN → IRR
        elseif ($this->from_currency === 'afn' && $this->to_currency === 'irr') {
            // با فرمول: (مبلغ × 1000) ÷ نرخ معکوس
            $result = ($amount * 1000) / $predefinedRate;
            Log::info("فرمول AFN → IRR: ({$amount} × 1000) ÷ {$predefinedRate} = {$result}");
        }
        // برای تبدیل AFN → USD (فروش افغانی به دلار)
        elseif ($this->from_currency === 'afn' && $this->to_currency === 'usd') {
            // نرخ پیش‌فرض: هر 1 افغانی = X دلار
            // پس: مبلغ افغانی × نرخ = مبلغ دلاری
            $result = $amount * $predefinedRate;
            Log::info("فرمول AFN → USD: {$amount} × {$predefinedRate} = {$result}");
        }
        // برای تبدیل USD → AFN (فروش دلار به افغانی)
        elseif ($this->from_currency === 'usd' && $this->to_currency === 'afn') {
            // نرخ پیش‌فرض: هر 1 دلار = X افغانی
            // پس: مبلغ دلاری × نرخ = مبلغ افغانی
            $result = $amount * $predefinedRate;
            Log::info("فرمول USD → AFN: {$amount} × {$predefinedRate} = {$result}");
        }
        // برای سایر تبدیل‌ها
        else {
            $result = $amount * $predefinedRate;
            Log::info("فرمول عمومی: {$amount} × {$predefinedRate} = {$result}");
        }

        Log::info("نتیجه محاسبه", ['result' => $result]);

        return $result;
    }

    /**
     * تبدیل به دالر - همیشه از نرخ خرید نقدی استفاده کن
     */
    private function convertTransferToUsd($amount, $currency)
    {
        Log::info("تبدیل {$currency} به USD", ['amount' => $amount]);

        if ($currency === 'usd') {
            return $amount;
        }

        // همیشه از رکورد USD استفاده کن
        $usdProfitRate = ProfitRate::where('source_currency', 'usd')->first();

        if (!$usdProfitRate) {
            Log::warning("رکورد USD یافت نشد");
            return 0;
        }

        // برای تبدیل IRR به USD - از نرخ خرید نقدی تومان استفاده کن
        if ($currency === 'irr') {
            $rate = $usdProfitRate->irr_buy_cash ?? 0;
            Log::info("نرخ خرید نقدی IRR → USD یافت شد", ['rate' => $rate]);

            if ($rate > 0) {
                $result = $amount / $rate;
                Log::info("نتیجه تبدیل", [
                    'result' => $result,
                    'formula' => "{$amount} ÷ {$rate} = {$result}"
                ]);
                return $result;
            }
        }

        // برای تبدیل AFN به USD - از نرخ خرید نقدی افغانی استفاده کن
        if ($currency === 'afn') {
            // نرخ خرید دلار: هر دلار = چند افغانی؟
            $rate = $usdProfitRate->afn_buy_cash ?? 0;
            Log::info("نرخ خرید نقدی AFN → USD یافت شد", [
                'rate' => $rate,
                'rate_field' => 'afn_buy_cash',
                'note' => 'هر دلار = ' . $rate . ' افغانی'
            ]);

            if ($rate > 0) {
                $result = $amount / $rate;
                Log::info("نتیجه تبدیل", [
                    'result' => $result,
                    'formula' => "{$amount} ÷ {$rate} = {$result}"
                ]);
                return $result;
            }
        }

        // برای سایر ارزها - از نرخ خرید نقدی استفاده کن
        $rateField = $currency . '_buy_cash';
        $rate = $usdProfitRate->{$rateField} ?? 0;

        if ($rate <= 0) {
            $rateField = $currency . '_buy_bank';
            $rate = $usdProfitRate->{$rateField} ?? 0;
        }

        Log::info("نرخ یافت شده", [
            'rate_field' => $rateField,
            'rate' => $rate,
            'formula' => "{$amount} ÷ {$rate}"
        ]);

        if ($rate > 0) {
            $result = $amount / $rate;
            Log::info("نتیجه تبدیل", ['result' => $result]);
            return $result;
        }

        Log::warning("نرخ تبدیل برای {$currency} یافت نشد");
        return 0;
    }

    /**
     * نتیجه پیش‌فرض برای سود/ضرر
     */
    private function getDefaultProfitLossResult()
    {
        return [
            'profit' => 0,
            'loss' => 0,
            'predefined_rate' => 0,
            'amount_with_predefined_rate' => 0,
            'amount_with_entered_rate' => 0,
            'difference' => 0
        ];
    }

    /**
     * ثبت سود/ضرر در جدول revenues برای انتقال
     */
    private function recordTransferProfitLoss($conversionId, $profitLoss)
    {
        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            if ($profitLoss['profit'] > 0 || $profitLoss['loss'] > 0) {
                Log::info('📊 در حال ثبت سود/ضرر انتقال در جدول revenue...', [
                    'profit' => $profitLoss['profit'],
                    'loss' => $profitLoss['loss'],
                    'conversion_id' => $conversionId
                ]);

                $revenueData = [
                    'currency' => 'usd',
                    'profit' => $profitLoss['profit'],
                    'lost' => $profitLoss['loss'],
                    'from' => 'انتقال بین حساب‌ها',
                    'description' => $this->generateTransferProfitLossDescription($profitLoss),
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'conversion_transfer_in_account_id' => $conversionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $revenue = Revenue::create($revenueData);

                Log::info("✅ سود/ضرر انتقال در جدول revenue ثبت شد - ID: {$revenue->id}");

                return $revenue;
            }

            Log::info('ℹ️ هیچ سود یا ضرری برای ثبت در revenue وجود ندارد');
            return null;
        } catch (\Exception $e) {
            Log::error('❌ خطا در ثبت سود/ضرر انتقال: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * تولید توضیحات برای سود/ضرر انتقال
     */
    private function generateTransferProfitLossDescription($profitLoss)
    {
        $fromCurrency = $this->getCurrencyName($this->from_currency);
        $toCurrency = $this->getCurrencyName($this->to_currency);

        $description = "سود/ضرر انتقال از {$fromCurrency} به {$toCurrency} - ";
        $description .= "مبلغ: " . number_format($this->withdrawal_amount) . " {$this->from_currency} - ";
        $description .= "حساب مبدا: {$this->from_account} - ";
        $description .= "حساب مقصد: {$this->to_account} - ";
        $description .= "نرخ وارد شده: " . number_format($this->currency_rate, 4) . " - ";
        $description .= "نرخ پیش‌فرض: " . number_format($profitLoss['predefined_rate'], 4);

        if ($profitLoss['profit'] > 0) {
            $description .= " - سود: " . number_format($profitLoss['profit'], 4) . " دالر";
        } else {
            $description .= " - ضرر: " . number_format($profitLoss['loss'], 4) . " دالر";
        }

        return $description;
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
            // محاسبه سود/ضرر
            Log::info('در حال محاسبه سود/ضرر برای انتقال...');
            $profitLoss = $this->calculateProfitOrLoss();

            if ($this->editingConversionId) {
                // حالت ویرایش
                $conversion = ConversionTransfers::find($this->editingConversionId);

                if ($conversion) {
                    // حذف تراکنش‌های قبلی با استفاده از رابطه
                    Transaction::where('conversion_transfer_id', $conversion->id)->delete();
                    // حذف سود/ضرر قبلی
                    Revenue::where('conversion_transfer_in_account_id', $conversion->id)->delete();

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
                'account_type' => $this->from_account,
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
                'account_type' => $this->to_account,
                'type' => 'رسید',
                'date' => $this->transaction_date,
                'description' => $this->description . ' - تبدیل از ' . $this->getCurrencyName($this->from_currency) . ' (' . $this->transactionType . ')',
                'zone' => $this->zone_receiver,
                'by' => $this->by_receiver,
                'conversion_transfer_id' => $conversionId,
            ]);

            // ===== نقدی → بانکی =====
            if ($this->from_account === 'نقدی' && $this->to_account === 'بانکی') {

                // کم کردن از صندوق نقدی (ارز مبدا)
                $safe = CurrencySafe::firstOrCreate([
                    'admin_id' => $adminId,
                ], [
                    'user_id' => $user->id,
                ]);
                $safe->decrement(strtolower($this->from_currency), $this->withdrawal_amount);

                // اضافه کردن به حساب بانکی (ارز مقصد)
                $bank = BankAccount::firstOrCreate([
                    'admin_id' => $adminId,
                ], [
                    'user_id' => $user->id,
                ]);
                $bank->increment(strtolower($this->to_currency), $this->received_amount);
            }

            // ===== بانکی → نقدی =====
            if ($this->from_account === 'بانکی' && $this->to_account === 'نقدی') {

                // کم کردن از حساب بانکی (ارز مبدا)
                $bank = BankAccount::where('admin_id', $adminId)->first();
                if ($bank) {
                    $bank->decrement(strtolower($this->from_currency), $this->withdrawal_amount);
                }

                // اضافه کردن به صندوق نقدی (ارز مقصد)
                $safe = CurrencySafe::firstOrCreate([
                    'admin_id' => $adminId,
                ], [
                    'user_id' => $user->id,
                ]);
                $safe->increment(strtolower($this->to_currency), $this->received_amount);
            }

            // ثبت سود/ضرر
            $this->recordTransferProfitLoss($conversionId, $profitLoss);

            DB::connection('sarafi')->commit();

            $message = $this->editingConversionId ? 'تبدیل ارز با موفقیت ویرایش شد.' : 'تبدیل ارز با موفقیت ثبت شد.';

            if ($profitLoss['profit'] > 0) {
                $message .= ' سود: ' . number_format($profitLoss['profit'], 4) . ' دالر';
            } elseif ($profitLoss['loss'] > 0) {
                $message .= ' ضرر: ' . number_format($profitLoss['loss'], 4) . ' دالر';
            }

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
            'profit_display',
            'loss_display',
        ]);

        $this->transactionType = 'خرید';
        $this->transaction_date = Jalalian::now()->format('Y/m/d');
        $this->resetProfitLossDisplay();
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

            // محاسبه سود/ضرر
            $this->calculateRealTimeProfitLoss();

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
        Log::info("تأیید حذف تبدیل ارز با ID: {$conversionId}");
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
        $this->deleting = false;
    }

    /**
     * حذف تبدیل ارز
     */
    public function deleteConversion()
    {
        if (!$this->confirmDeleteId) {
            session()->flash('error', 'شناسه تبدیل ارز برای حذف مشخص نیست.');
            return;
        }

        $this->deleting = true;

        DB::connection('sarafi')->beginTransaction();

        try {
            Log::info("در حال حذف تبدیل ارز با ID: {$this->confirmDeleteId}");

            // پیدا کردن رکورد تبدیل ارز
            $conversion = ConversionTransfers::find($this->confirmDeleteId);

            if (!$conversion) {
                throw new \Exception('رکورد تبدیل ارز یافت نشد.');
            }

            Log::info("رکورد تبدیل ارز پیدا شد: {$conversion->id}");

            // حذف تراکنش‌های مرتبط
            $transactionsDeleted = Transaction::where('conversion_transfer_id', $conversion->id)->delete();
            Log::info("{$transactionsDeleted} تراکنش مرتبط حذف شد");

            // حذف سود/ضرر مرتبط
            $revenuesDeleted = Revenue::where('conversion_transfer_in_account_id', $conversion->id)->delete();
            Log::info("{$revenuesDeleted} رکورد سود/ضرر مرتبط حذف شد");

            // حذف تبدیل ارز
            $conversion->delete();
            Log::info("رکورد تبدیل ارز با موفقیت حذف شد");

            DB::connection('sarafi')->commit();

            session()->flash('message', 'تبدیل ارز با موفقیت حذف شد.');

            // رفرش داده‌ها
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();

            $errorMessage = 'خطا در حذف تبدیل ارز: ' . $e->getMessage();
            session()->flash('error', $errorMessage);

            Log::error('Delete conversion error: ' . $e->getMessage(), [
                'conversion_id' => $this->confirmDeleteId,
                'user_id' => Auth::guard('sarafi')->user()->id ?? 'unknown'
            ]);
        } finally {
            $this->confirmDeleteId = null;
            $this->deleting = false;
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
