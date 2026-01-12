<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ProfitRate;
use App\Models\Sarafi\Salaries;
use App\Models\Sarafi\Staffs;
use App\Models\Sarafi\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Salary extends Component
{
    public $staffs = [];
    public $selectedStaffId = null;
    public $staffDetails = null;
    public $dueDays = 0;
    public $dueAmount = 0;
    public $paymentMethod = 'نقدی';
    public $customers = [];
    public $selectedCustomerId = null;
    public $selectedCustomer = null;
    public $description = '';
    public $salaryHistory = [];
    public $search = '';
    public $selectedAccount = null;
    public $currencies = [];
    public $currency = 'afn';
    public $amount = '';
    public $date;
    
    // برای نمایش اطلاعات مشتری
    public $customerCashBalances = [];
    public $customerBankBalances = [];
    public $customerTotalBalances = [];
    
    protected $rules = [
        'selectedStaffId' => 'required|exists:staffs,id',
        'dueAmount' => 'required|numeric|min:1',
        'paymentMethod' => 'required|in:نقدی,کارتی',
        'selectedCustomerId' => 'required_if:paymentMethod,کارتی',
        'description' => 'nullable|string|max:500',
        'currency' => 'required|in:AFN,USD,EUR,IRR,AED,TRY,CNY,PKR,GBP,JPY,SAR,INR',
        'date' => 'required|date',
    ];
    
    protected $messages = [
        'selectedStaffId.required' => 'لطفاً کارمند را انتخاب کنید.',
        'dueAmount.required' => 'مبلغ قابل پرداخت محاسبه نشده است.',
        'selectedCustomerId.required_if' => 'برای پرداخت کارتی، مشتری را انتخاب کنید.',
    ];
    
    public function mount()
    {
        $this->staffs = Staffs::all();
        $this->customers = Customer::all();
        $this->date = Jalalian::now()->format('Y/m/d');
        
        // تعریف ارزها
        $this->currencies = [
            ['code' => 'afn', 'name_fa' => 'افغانی', 'name_en' => 'Afghani'],
            ['code' => 'USD', 'name_fa' => 'دالر', 'name_en' => 'US Dollar'],
            ['code' => 'EUR', 'name_fa' => 'یورو', 'name_en' => 'Euro'],
            ['code' => 'IRR', 'name_fa' => 'تومان', 'name_en' => 'Iranian Rial'],
            ['code' => 'AED', 'name_fa' => 'درهم', 'name_en' => 'UAE Dirham'],
            ['code' => 'TRY', 'name_fa' => 'لیره', 'name_en' => 'Turkish Lira'],
            ['code' => 'CNY', 'name_fa' => 'یوان', 'name_en' => 'Chinese Yuan'],
            ['code' => 'PKR', 'name_fa' => 'کلدار', 'name_en' => 'Pakistani Rupee'],
            ['code' => 'GBP', 'name_fa' => 'پوند', 'name_en' => 'British Pound'],
            ['code' => 'JPY', 'name_fa' => 'ین', 'name_en' => 'Japanese Yen'],
            ['code' => 'SAR', 'name_fa' => 'ریال', 'name_en' => 'Saudi Riyal'],
            ['code' => 'INR', 'name_fa' => 'روپیه', 'name_en' => 'Indian Rupee'],
        ];
    }
    
    public function updatedSelectedStaffId($value)
    {
        if ($value) {
            $this->staffDetails = Staffs::find($value);
            $this->calculateDueSalary();
            $this->loadSalaryHistory();
            
            // تنظیم ارز پیش فرض بر اساس واحد حقوق کارمند (فرض بر افغانی)
            $this->currency = 'afn';
        } else {
            $this->staffDetails = null;
            $this->dueDays = 0;
            $this->dueAmount = 0;
            $this->salaryHistory = [];
        }
    }
    
  public function calculateDueSalary()
{
    if (!$this->staffDetails) {
        return;
    }
    
    // پیدا کردن آخرین پرداخت
    $lastSalary = Salaries::where('staff_id', $this->selectedStaffId)
        ->latest('paid_date')
        ->first();
    
    // تاریخ شروع برای محاسبه
    if ($lastSalary) {
        // از روز بعد از آخرین پرداخت شروع می‌کنیم
        $startDate = Carbon::parse($lastSalary->paid_date)->addDay()->startOfDay();
    } else {
        $startDate = Carbon::parse($this->staffDetails->contract_start)->startOfDay();
    }
    
    $endDate = Carbon::now()->startOfDay();
    
    // اگر تاریخ شروع بعد از امروز باشد
    if ($startDate->greaterThan($endDate)) {
        $this->dueDays = 0;
        $this->dueAmount = 0;
        return;
    }
    
    // اگر قرارداد تمام شده باشد، تا تاریخ قرارداد محاسبه می‌کنیم
    if ($this->staffDetails->contract_end) {
        $contractEnd = Carbon::parse($this->staffDetails->contract_end)->startOfDay();
        if ($contractEnd->lessThan($endDate)) {
            $endDate = $contractEnd;
        }
    }
    
    // تعداد روزهای معوقه (به عدد صحیح)
    $this->dueDays = (int) $startDate->diffInDays($endDate);
    
    // مبلغ روزانه (فرض بر 30 روز در ماه)
    $dailySalary = $this->staffDetails->salary_amount / 30;
    
    // مبلغ معوقه
    $this->dueAmount = round($this->dueDays * $dailySalary);
    $this->amount = number_format($this->dueAmount);
}
    
    public function loadSalaryHistory()
    {
        $this->salaryHistory = Salaries::where('staff_id', $this->selectedStaffId)
            ->with(['customer', 'admin', 'user'])
            ->orderBy('paid_date', 'desc')
            ->get();
    }
    
    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
        if ($method === 'نقدی') {
            $this->selectedCustomerId = null;
            $this->selectedCustomer = null;
        }
    }
    
    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $customer = Customer::find($customerId);
        
        if ($customer) {
            $this->selectedCustomer = $customer;
            $this->selectedAccount = $customerId;
            $this->calculateCustomerBalances($customerId);
        }
    }
    
    public function calculateCustomerBalances($customerId)
    {
        // محاسبه موجودی‌های مشتری
        $transactions = Transaction::where('customer_id', $customerId)->get();
        
        $cashBalances = [];
        $bankBalances = [];
        $totalBalances = [];
        
        foreach ($this->currencies as $currency) {
            $code = $currency['code'];
            $name_fa = $currency['name_fa'];
            
            // موجودی نقدی
            $cashBalance = $transactions->where('currency', $code)
                ->where('account_type', 'نقدی')
                ->sum(function($transaction) {
                    return $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;
                });
            
            // موجودی بانکی
            $bankBalance = $transactions->where('currency', $code)
                ->where('account_type', 'بانکی')
                ->sum(function($transaction) {
                    return $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;
                });
            
            $cashBalances[$name_fa] = max($cashBalance, 0);
            $bankBalances[$name_fa] = max($bankBalance, 0);
            $totalBalances[$name_fa] = max($cashBalance + $bankBalance, 0);
        }
        
        $this->customerCashBalances = $cashBalances;
        $this->customerBankBalances = $bankBalances;
        $this->customerTotalBalances = $totalBalances;
    }
    
 public function paySalary()
    {
        $this->validate([
            'selectedStaffId' => 'required|exists:sarafi.staffs,id',
            'dueAmount' => 'required|numeric|min:1',
            'paymentMethod' => 'required|in:نقدی,کارتی',
            'selectedCustomerId' => 'required_if:paymentMethod,کارتی',
            'description' => 'nullable|string|max:500',
            'currency' => 'required|in:afn,USD,EUR,IRR,AED,TRY,CNY,PKR,GBP,JPY,SAR,INR',
            'date' => 'required|date',
        ]);

        if (!$this->selectedStaffId || $this->dueAmount <= 0) {
            session()->flash('error', 'مبلغ قابل پرداخت معتبر نیست.');
            return;
        }

        if ($this->paymentMethod === 'کارتی' && !$this->selectedCustomerId) {
            session()->flash('error', 'برای پرداخت کارتی، مشتری را انتخاب کنید.');
            return;
        }

        DB::beginTransaction();

        try {
            $adminId = Auth::guard('sarafi')->user()->admin_id ?? Auth::guard('sarafi')->id();
            $userId = Auth::guard('sarafi')->id();
            $paidDate = Jalalian::fromFormat('Y/m/d', $this->date)->toCarbon();

            // پرداخت نقدی
            if ($this->paymentMethod === 'نقدی') {
                // کسر از صندوق
                $safe = CurrencySafe::where('user_id', $userId)->first();

                if (!$safe) {
                    // اگر صندوق وجود نداشت، ایجاد می‌کنیم
                    $safe = CurrencySafe::create([
                        'user_id' => $userId,
                        'admin_id' => $adminId,
                        'afn' => 0,
                        'usd' => 0,
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
                    ]);
                }

                // بررسی موجودی صندوق
                $currencyField = strtolower($this->currency);
                if ($safe->{$currencyField} < $this->dueAmount) {
                    throw new \Exception('موجودی صندوق کافی نیست.');
                }

                // کسر از صندوق
                $safe->{$currencyField} -= $this->dueAmount;
                $safe->save();

                // ثبت پرداخت حقوق با استفاده از query builder برای دور زدن validation
                DB::connection('sarafi')->table('salary')->insert([
                    'user_id' => $userId,
                    'admin_id' => $adminId,
                    'staff_id' => $this->selectedStaffId,
                    'customer_id' => null,
                    'amount' => $this->dueAmount,
                    'currency' => $this->currency,
                    'payment_method' => 'نقدی',
                    'paid_date' => $paidDate,
                    'description' => $this->description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                session()->flash('message', 'پرداخت نقدی با موفقیت انجام شد و از صندوق کسر شد.');
            } 
            // پرداخت کارتی
            else {
                // ثبت تراکنش از مشتری
                $transaction = Transaction::create([
                    'user_id' => $userId,
                    'admin_id' => $adminId,
                    'customer_id' => $this->selectedCustomerId,
                    'amount' => $this->dueAmount,
                    'currency' => $this->currency,
                    'type' => 'رسید',
                    'account_type' => 'نقدی',
                    'description' => $this->description . ' (پرداخت حقوق کارمند)',
                    'zone' => Auth::guard('sarafi')->user()->zone ?? 'کابل',
                    'date' => $paidDate,
                ]);

                // ثبت پرداخت حقوق با استفاده از query builder
                DB::connection('sarafi')->table('salary')->insert([
                    'user_id' => $userId,
                    'admin_id' => $adminId,
                    'staff_id' => $this->selectedStaffId,
                    'customer_id' => $this->selectedCustomerId,
                    'amount' => $this->dueAmount,
                    'currency' => $this->currency,
                    'payment_method' => 'کارتی',
                    'paid_date' => $paidDate,
                    'description' => $this->description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                session()->flash('message', 'پرداخت کارتی با موفقیت انجام شد و از حساب مشتری کسر شد.');
            }

            DB::commit();

            // بازخوانی اطلاعات
            $this->staffDetails = Staffs::find($this->selectedStaffId);
            $this->calculateDueSalary();
            $this->loadSalaryHistory();
            $this->description = '';

            // اگر مشتری انتخاب شده بود، موجودی‌ها را به روز کنیم
            if ($this->selectedCustomerId) {
                $this->calculateCustomerBalances($this->selectedCustomerId);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'خطا در پرداخت: ' . $e->getMessage());
        }
    }
    
    public function formatAmount()
    {
        if ($this->amount) {
            $this->amount = number_format(str_replace(',', '', $this->amount));
        }
    }
    
    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->selectedCustomer = null;
        $this->selectedAccount = null;
        $this->customerCashBalances = [];
        $this->customerBankBalances = [];
        $this->customerTotalBalances = [];
    }
    
    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedCustomerId = null;
        $this->selectedCustomer = null;
        $this->selectedAccount = null;
    }
    
    public function render()
    {
        $filteredCustomers = $this->search 
            ? Customer::where('fullname', 'like', '%' . $this->search . '%')
                ->orWhere('account_number', 'like', '%' . $this->search . '%')
                ->limit(10)
                ->get()
            : collect([]);
        
        // اگر مشتری انتخاب شده، موجودی‌ها را محاسبه کن
        if ($this->selectedCustomerId && empty($this->customerCashBalances)) {
            $this->calculateCustomerBalances($this->selectedCustomerId);
        }
        
        return view('livewire.sarafi.salary', [
            'filteredCustomers' => $filteredCustomers,
            'staffs' => $this->staffs,
        ]);
    }
}