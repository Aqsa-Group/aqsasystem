<?php

namespace App\Livewire\Market;

use Livewire\Component;
use App\Models\Market\Customer as CustomerModel;
use App\Models\Market\CustomerConversion as ConversionModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use NumberFormatter;
use Morilog\Jalali\Jalalian;

class CustomerConversion extends Component
{
    // Properties for search and selection
    public $search = '';
    public $selectedAccount;
    public $selectedCustomerId = null;
    public $selectedCustomer = null;
    public $customers = [];
    public $filteredCustomers = [];

    // Form fields for conversion
    public $from_currency = 'AFN';
    public $to_currency = 'USD';
    public $withdraw_amount;
    public $receive_amount;
    public $rate;
    public $description;
    public $date; // تاریخ شمسی به فرمت Y/m/d

    // Data for cards (balances)
    public $customerTotalBalances = [];
    public $customerCashBalances = [];
    public $customerBankBalances = [];

    // Conversion history
    public $conversions = [];
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 75, 'all'];

    // Currencies
    public $currencies = ['AFN', 'USD', 'EUR', 'IRR'];
    public $currencyNames = [
        'AFN' => 'افغانی',
        'USD' => 'دالر',
        'EUR' => 'یورو',
        'IRR' => 'تومان',
    ];

    // For amount in words
    public $withdrawAmountInWords = '';
    public $receiveAmountInWords = '';
    public $rateInWords = '';

    // Editing & Deletion
    public $editingConversionId = null;
    public $confirmDeleteId = null;

    protected function rules()
    {
        return [
            'selectedAccount' => [
                'required',
                Rule::exists('market.customers', 'id'),
            ],
            'from_currency'    => ['required', Rule::in($this->currencies)],
            'to_currency'      => ['required', Rule::in($this->currencies), 'different:from_currency'],
            'withdraw_amount'  => 'required|numeric|min:0.01',
            'rate'             => 'required|numeric|min:0.0001',
            'receive_amount'   => 'required|numeric|min:0',
            'description'      => 'nullable|string|max:500',
            'date'             => 'required|date_format:Y/m/d',
        ];
    }

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->loadCustomers();
        $this->loadConversions();
        $this->resetForm();
        $this->customerTotalBalances = [];
        $this->customerCashBalances = [];
        $this->customerBankBalances = [];
    }

    // ----- Customer Search & Loading -----
    public function updatedSearch($value)
    {
        if (empty($value)) {
            $this->selectedCustomerId = null;
            $this->filteredCustomers = [];
            $this->selectedCustomer = null;
            $this->loadConversions();
            $this->updateCustomerBalances();
            return;
        }
        $this->searchCustomers($value);
    }

    private function loadCustomers()
    {
        $this->customers = CustomerModel::orderBy('fullname')
            ->get(['id', 'fullname', 'phone']);
    }

    private function searchCustomers($value)
    {
        $this->filteredCustomers = CustomerModel::where(function ($query) use ($value) {
            $query->where('fullname', 'like', "%{$value}%")
                ->orWhere('phone', 'like', "%{$value}%");
        })
            ->limit(15)
            ->get(['id', 'fullname', 'phone']);
    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->selectedAccount = $customerId;
        $this->selectedCustomer = CustomerModel::find($customerId);
        $this->filteredCustomers = [];

        if ($this->selectedCustomer) {
            $this->search = $this->selectedCustomer->fullname;
            $this->updateCustomerBalances();
            $this->loadConversions();
        }
    }

    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->search = '';
        $this->selectedCustomer = null;
        $this->filteredCustomers = [];
        $this->loadConversions();
        $this->updateCustomerBalances();
    }

    public function updatedPerPage()
    {
        $this->loadConversions();
    }

    // ----- Update Customer Balance Cards -----
    public function updateCustomerBalances()
    {
        if (!$this->selectedCustomer) {
            $this->customerTotalBalances = [];
            $this->customerCashBalances = [];
            $this->customerBankBalances = [];
            return;
        }

        $customer = $this->selectedCustomer;
        $this->customerTotalBalances = [];
        $this->customerCashBalances = [];
        $this->customerBankBalances = [];

        foreach ($this->currencies as $code) {
            $field = 'balance_' . strtolower($code);
            $balance = $customer->$field ?? 0;
            $name = $this->currencyNames[$code] ?? $code;
            $this->customerTotalBalances[$name] = $balance;
            $this->customerCashBalances[$name] = $balance;
            $this->customerBankBalances[$name] = 0;
        }
    }

    // ----- Load Conversion History -----
    public function loadConversions()
    {
        $query = ConversionModel::with(['customer', 'admin'])
            ->where('admin_id', Auth::id());

        if ($this->selectedCustomerId) {
            $query->where('customer_id', $this->selectedCustomerId);
        }

        if ($this->perPage === 'all') {
            $this->conversions = $query->latest()->get();
        } else {
            $this->conversions = $query->latest()
                ->limit((int) $this->perPage)
                ->get();
        }
    }

    public function resetForm()
    {
        $this->selectedAccount = null;
        $this->from_currency = 'AFN';
        $this->to_currency = 'USD';
        $this->withdraw_amount = null;
        $this->receive_amount = null;
        $this->rate = null;
        $this->description = null;
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->withdrawAmountInWords = '';
        $this->receiveAmountInWords = '';
        $this->rateInWords = '';
        $this->editingConversionId = null;
        $this->confirmDeleteId = null;
    }

    // ----- Auto-calculate receive amount -----
    public function updatedWithdrawAmount()
    {
        $this->calculateReceiveAmount();
        $this->convertAmountToWords($this->withdraw_amount, 'withdrawAmountInWords', 2);
    }

    public function updatedRate()
    {
        $this->calculateReceiveAmount();
        $this->convertAmountToWords($this->rate, 'rateInWords', 4);
    }

    public function updatedFromCurrency()
    {
        $this->calculateReceiveAmount();
    }

    public function updatedToCurrency()
    {
        $this->calculateReceiveAmount();
    }

    protected function calculateReceiveAmount()
    {
        if (empty($this->withdraw_amount) || empty($this->rate) || $this->rate <= 0 || empty($this->from_currency) || empty($this->to_currency)) {
            $this->receive_amount = null;
            $this->receiveAmountInWords = '';
            return;
        }

        $fromCurrency = strtolower($this->from_currency);
        $toCurrency = strtolower($this->to_currency);
        $amount = floatval(str_replace(',', '', $this->withdraw_amount));
        $rate = floatval(str_replace(',', '', $this->rate));

        if ($rate == 0) {
            $this->receive_amount = null;
            $this->receiveAmountInWords = '';
            return;
        }

        // محاسبه بر اساس منطق صرافی
        if ($fromCurrency === 'afn' && $toCurrency === 'irr') {
            $calculated = ($amount * 1000) / $rate;
        } elseif ($fromCurrency === 'irr' && $toCurrency === 'afn') {
            $calculated = ($amount * $rate) / 1000;
        } elseif ($fromCurrency === 'irr' && $toCurrency === 'usd') {
            $calculated = $amount / $rate;
        } elseif ($fromCurrency === 'irr' && $toCurrency === 'eur') {
            $usdAmount = $amount / $rate;
            $eurToUsdRate = 1.1;
            $calculated = $usdAmount / $eurToUsdRate;
        } elseif ($fromCurrency === 'usd' && $toCurrency === 'eur') {
            $eurToUsdRate = 1.1;
            $calculated = $amount / $eurToUsdRate;
        } elseif ($fromCurrency === 'eur' && $toCurrency === 'usd') {
            $eurToUsdRate = 1.1;
            $calculated = $amount * $eurToUsdRate;
        } else {
            $shouldDivide = $this->shouldUseDivision($fromCurrency, $toCurrency);
            $calculated = $shouldDivide ? $amount / $rate : $amount * $rate;
        }

        $this->receive_amount = round($calculated, 2);
        $this->convertAmountToWords($this->receive_amount, 'receiveAmountInWords', 2);
    }

    private function shouldUseDivision($fromCurrency, $toCurrency): bool
    {
        $baseCurrencies = ['usd', 'eur', 'gbp'];
        $localCurrencies = ['afn', 'irr', 'pkr', 'aed', 'try', 'cny', 'inr'];

        if (in_array($fromCurrency, $baseCurrencies) && in_array($toCurrency, $localCurrencies)) {
            return false;
        }
        if (in_array($fromCurrency, $localCurrencies) && in_array($toCurrency, $baseCurrencies)) {
            return true;
        }
        return true;
    }

    private function convertAmountToWords($value, $property, $decimals = 2)
    {
        if (!empty($value) && is_numeric(str_replace(',', '', $value))) {
            try {
                $numericValue = floatval(str_replace(',', '', $value));
                if ($numericValue == 0) {
                    $this->$property = 'صفر';
                    return;
                }
                $rounded = round($numericValue, $decimals);
                $parts = explode('.', (string)$rounded);
                $integerPart = intval($parts[0]);
                $fractionPart = isset($parts[1]) ? rtrim($parts[1], '0') : '';

                $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
                $integerWords = $formatter->format($integerPart);
                $fractionWords = '';
                if ($fractionPart !== '' && intval($fractionPart) > 0) {
                    $fractionPart = str_pad($fractionPart, $decimals, '0', STR_PAD_RIGHT);
                    $fractionWords = $formatter->format(intval($fractionPart));
                }

                $words = $integerWords;
                if ($fractionWords !== '') {
                    $words .= ' ممیز ' . $fractionWords;
                }

                $replacements = [
                    'دویست' => 'دوصد',
                    'سیصد' => 'سه‌صد',
                    'پانصد' => 'پانصد',
                    'هشتصد' => 'هشتصد',
                    'نهصد' => 'نه‌صد',
                    'و ممیز' => ' ممیز',
                    '  ' => ' ',
                ];
                $words = str_replace(array_keys($replacements), array_values($replacements), $words);

                if ($property === 'withdrawAmountInWords') {
                    $currencyName = $this->currencyNames[$this->from_currency] ?? $this->from_currency;
                    $words .= ' ' . $currencyName;
                } elseif ($property === 'receiveAmountInWords') {
                    $currencyName = $this->currencyNames[$this->to_currency] ?? $this->to_currency;
                    $words .= ' ' . $currencyName;
                }

                $this->$property = $words;
            } catch (\Exception $e) {
                $this->$property = '';
            }
        } else {
            $this->$property = '';
        }
    }

    // ----- Edit Conversion -----
    public function editConversion($id)
    {
        $conversion = ConversionModel::with(['customer'])->find($id);
        if (!$conversion) {
            session()->flash('error', 'رکورد تبدیل ارز یافت نشد.');
            return;
        }

        $this->editingConversionId = $id;
        $this->selectedAccount = $conversion->customer_id;
        $this->selectedCustomerId = $conversion->customer_id;
        $this->selectedCustomer = $conversion->customer;
        $this->from_currency = $conversion->from_currency;
        $this->to_currency = $conversion->to_currency;
        $this->withdraw_amount = $conversion->withdraw_amount;
        $this->receive_amount = $conversion->receive_amount;
        $this->rate = $conversion->rate;
        $this->description = $conversion->description;

        // ★ تنظیم تاریخ از transaction_date
        if (!empty($conversion->transaction_date)) {
            $this->date = $conversion->transaction_date; // قبلاً به صورت Y/m/d ذخیره شده
        } else {
            // اگر transaction_date خالی بود، از created_at استفاده کن
            $this->date = Jalalian::fromCarbon($conversion->created_at)->format('Y/m/d');
        }

        $this->updateCustomerBalances();
        $this->search = $this->selectedCustomer->fullname;

        $this->convertAmountToWords($this->withdraw_amount, 'withdrawAmountInWords', 2);
        $this->convertAmountToWords($this->receive_amount, 'receiveAmountInWords', 2);
        $this->convertAmountToWords($this->rate, 'rateInWords', 4);

        $this->dispatch('scroll-to-form');
    }

    // ----- Save or Update Conversion -----
    public function submitTransaction()
    {
        $validated = $this->validate();

        $balanceField = 'balance_' . strtolower($this->from_currency);
        $customer = CustomerModel::find($this->selectedAccount);

      

        try {
            DB::connection('market')->transaction(function () use ($customer) {
                if ($this->editingConversionId) {
                    // ویرایش
                    $conversion = ConversionModel::find($this->editingConversionId);
                    if (!$conversion) {
                        throw new \Exception('رکورد تبدیل ارز برای ویرایش یافت نشد.');
                    }

                    // برگرداندن موجودی قبلی
                    $oldFromField = 'balance_' . strtolower($conversion->from_currency);
                    $oldToField = 'balance_' . strtolower($conversion->to_currency);
                    $customer->$oldFromField += $conversion->withdraw_amount;
                    $customer->$oldToField -= $conversion->receive_amount;

                    // اعمال تغییرات جدید
                    $fromField = 'balance_' . strtolower($this->from_currency);
                    $toField = 'balance_' . strtolower($this->to_currency);

                    $customer->$fromField = $customer->$fromField - $this->withdraw_amount;
                    $customer->$toField = $customer->$toField + $this->receive_amount;
                    $customer->save();

                    // به‌روزرسانی رکورد تبدیل
                    $conversion->update([
                        'customer_id'      => $this->selectedAccount,
                        'from_currency'    => $this->from_currency,
                        'to_currency'      => $this->to_currency,
                        'withdraw_amount'  => $this->withdraw_amount,
                        'receive_amount'   => $this->receive_amount,
                        'rate'             => $this->rate,
                        'description'      => $this->description,
                        'transaction_date' => $this->date, // ★ ذخیره تاریخ شمسی
                    ]);

                    $this->selectedCustomer = $customer->fresh();
                    $this->updateCustomerBalances();
                    session()->flash('message', 'تبدیل با موفقیت ویرایش شد.');
                } else {
                    // ثبت جدید
                    $conversion = ConversionModel::create([
                        'customer_id'      => $this->selectedAccount,
                        'admin_id'         => Auth::id(),
                        'from_currency'    => $this->from_currency,
                        'to_currency'      => $this->to_currency,
                        'withdraw_amount'  => $this->withdraw_amount,
                        'receive_amount'   => $this->receive_amount,
                        'rate'             => $this->rate,
                        'description'      => $this->description,
                        'transaction_date' => $this->date, // ★ ذخیره تاریخ شمسی
                    ]);

                    $fromField = 'balance_' . strtolower($this->from_currency);
                    $toField   = 'balance_' . strtolower($this->to_currency);

                    $customer->$fromField = $customer->$fromField - $this->withdraw_amount;
                    $customer->$toField   = $customer->$toField + $this->receive_amount;
                    $customer->save();

                    $this->selectedCustomer = $customer->fresh();
                    $this->updateCustomerBalances();
                    session()->flash('message', 'تبدیل با موفقیت ثبت شد.');
                }
            });

            $this->loadConversions();
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در ذخیره‌سازی: ' . $e->getMessage());
        }
    }

    // ----- Delete Conversion -----
    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConversion()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        try {
            DB::connection('market')->transaction(function () {
                $conversion = ConversionModel::find($this->confirmDeleteId);
                if (!$conversion) {
                    throw new \Exception('رکورد تبدیل ارز یافت نشد.');
                }

                // برگرداندن موجودی
                $customer = CustomerModel::find($conversion->customer_id);
                $fromField = 'balance_' . strtolower($conversion->from_currency);
                $toField = 'balance_' . strtolower($conversion->to_currency);

                $customer->$fromField += $conversion->withdraw_amount;
                $customer->$toField -= $conversion->receive_amount;
                $customer->save();

                $conversion->delete();
                $this->selectedCustomer = $customer->fresh();
                $this->updateCustomerBalances();
            });

            $this->loadConversions();
            $this->confirmDeleteId = null;
            session()->flash('message', 'تبدیل با موفقیت حذف شد.');
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در حذف تبدیل: ' . $e->getMessage());
        }
    }

    // ----- Cancel (بازنشانی فرم و لغو ویرایش) -----
    public function cancel()
    {
        $this->resetForm();
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.market.customer-conversion', [
            'customers' => $this->customers,
            'conversions' => $this->conversions,
            'customerTotalBalances' => $this->customerTotalBalances,
            'customerCashBalances' => $this->customerCashBalances,
            'customerBankBalances' => $this->customerBankBalances,
        ]);
    }
}
