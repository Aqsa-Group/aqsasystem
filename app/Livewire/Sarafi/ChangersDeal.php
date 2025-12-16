<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\ChangerDeal;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;
use NumberFormatter;

class ChangersDeal extends Component
{
    use WithFileUploads;

    // متغیرهای جستجو و انتخاب
    public $searchedCustomer = null;
    public $showCustomerModal = false;

    // جستجو برای مشتری فرستنده
    public $search = '';
    public $selectedCustomerId = null;
    public $selectedAccount = null;
    public $filteredCustomers = [];
    public $selectedCustomer = null;
    public $accountType = 'نقدی';
    public $remittance_number;

    // جستجو برای مشتری گیرنده
    public $to_customer_search = '';
    public $to_customer_id = null;
    public $to_customer_filtered = [];
    public $to_customer_selected = null;

    // متغیرهای فرم
    public $to_sarafi = null;
    public $currency;
    public $amount;
    public $amountInWords;
    public $date;
    public $zone;
    public $description;
    public $file;

    // لیست‌ها
    public $currencies = [];
    public $sarafi_list = [];
    public $customers;
    public $transactions = [];

    // موجودی‌ها
    public $customerCashBalances = [];
    public $customerBankBalances = [];
    public $customerTotalBalances = [];

    // حالت‌ها
    public $confirmDeleteId = null;
    public $editId = null;
    public $isEditMode = false;

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->zone = Auth::guard('sarafi')->user()->zone;

        // لیست ارزها
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

        // لیست صرافی‌های دیگر
        $currentUser = Auth::guard('sarafi')->user();
        $this->sarafi_list = User::where('role', 'admin')
            ->where('id', '!=', $currentUser->id)
            ->get(['id', 'name', 'sarafi_name']);


        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $this->remittance_number = $this->generateRemittanceNumber($adminId);



        // بارگذاری مشتریان
        $this->loadCustomers();

        // بارگذاری تراکنش‌ها
        $this->updateTransactions();
    }

    private function loadCustomers()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->customers = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        $this->customers = Customer::with('admins')
            ->where(function ($query) use ($adminId) {
                $query->where('admin_id', $adminId)
                    ->orWhereHas('admins', function ($q) use ($adminId) {
                        $q->where('customer_admin.admin_id', $adminId);
                    });
            })
            ->orderBy('fullname')
            ->get();
    }



    public function toggleAccountType()
    {
        $this->accountType = $this->accountType === 'نقدی' ? 'بانکی' : 'نقدی';
    }


    public function updatedSearch($value)
    {
        if (empty($value)) {
            $this->filteredCustomers = [];
            $this->searchedCustomer = null;
            return;
        }

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
            ->get();
    }

    // جستجو برای مشتری گیرنده
    public function updatedToCustomerSearch($value)
    {
        if (empty($value)) {
            $this->to_customer_filtered = [];
            return;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $this->to_customer_filtered = Customer::with('admins')
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

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->selectedAccount = $customerId;
        $this->selectedCustomer = Customer::find($customerId);

        if ($this->selectedCustomer) {
            $this->search = $this->selectedCustomer->fullname;
            $this->updateCustomerCurrencyBalance();
        }

        $this->filteredCustomers = [];
        $this->updateTransactions();
    }

    // انتخاب مشتری گیرنده
    public function selectToCustomer($customerId)
    {
        $this->to_customer_id = $customerId;
        $this->to_customer_selected = Customer::find($customerId);

        if ($this->to_customer_selected) {
            $this->to_customer_search = $this->to_customer_selected->fullname;
        }

        $this->to_customer_filtered = [];
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

    private function generateRemittanceNumber($adminId)
    {
        $lastNumber = ChangerDeal::where('admin_id', $adminId)
            ->max('remittance_number');

        return ($lastNumber ?? 0) + 1;
    }



    public function submitRemittance()
    {

        $this->amount = str_replace(',', '', $this->amount);

        // اگر مشتری گیرنده انتخاب نشده، همان مشتری فرستنده را بگیر
        if (!$this->to_customer_id) {
            $this->to_customer_id = $this->selectedAccount;
            $this->to_customer_selected = $this->selectedCustomer;
        }

        $this->validate([
            'selectedAccount' => 'required|exists:sarafi.customers,id',
            'to_customer_id' => 'required|exists:sarafi.customers,id',
            'to_sarafi' => 'required|exists:sarafi.users,id',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required',
            'date' => 'required',
            'zone' => 'required',
        ]);

        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;
            $toSarafi = User::find($this->to_sarafi);

            $remittanceNumber = $this->generateRemittanceNumber($adminId);


            // گرفتن نام فارسی ارز
            $currencyName = $this->getCurrencyName($this->currency);

            // ایجاد توضیحات
            $fromCustomerName = $this->selectedCustomer->fullname;
            $toCustomerName = $this->to_customer_selected->fullname;

            if (empty($this->description)) {
                $withdrawalDescription = "برد از حساب {$fromCustomerName} برای ارسال به صرافی {$toSarafi->sarafi_name}";
                $depositDescription = "دریافت از صرافی {$user->sarafi_name} به حساب {$toCustomerName}";
            } else {
                $withdrawalDescription = $this->description;
                $depositDescription = $this->description;
            }

            // ایجاد رکورد در جدول changerdeals
            $changerDeal = ChangerDeal::create([
                'remittance_number' => $remittanceNumber,
                'from_customer' => $this->selectedAccount,
                'to_customer' => $this->to_customer_id,
                'from_sarafi' => $user->id,
                'to_sarafi' => $this->to_sarafi,
                'currency' => $this->currency,
                'zone' => $this->zone,
                'amount' => $this->amount,
                'account_type' => $this->accountType,
                'date' => $this->date,
                'description' => $this->description,
                'user_id' => $user->id,
                'admin_id' => $adminId,
            ]);

            // **تراکنش برد** از حساب مشتری فرستنده در صرافی مبدا
            Transaction::create([
                'customer_id' => $this->selectedAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'type' => 'برد',
                'account_type' => $this->accountType,
                'date' => $this->date,
                'description' => $withdrawalDescription,
                'zone' => $this->zone,
                'by' => $user->name,
                'changerdeal_id' => $changerDeal->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // **تراکنش رسید** به حساب مشتری گیرنده در صرافی مقصد
            Transaction::create([
                'customer_id' => $this->to_customer_id,
                'user_id' => $this->to_sarafi,
                'admin_id' => $this->to_sarafi,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'type' => 'رسید',
                'account_type' => $this->accountType,
                'date' => $this->date,
                'description' => "مبلغ " . number_format($this->amount) . " {$currencyName} از صرافی {$user->sarafi_name} دریافت گردید.",
                'zone' => $this->zone,
                'by' => $user->name,
                'changerdeal_id' => $changerDeal->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ذخیره فایل اگر وجود دارد
            if ($this->file) {
                $path = $this->file->store('changerdeals', 'public');
                $changerDeal->update(['file_path' => $path]);
            }

            session()->flash('message', 'ارسال پول به صرافی با موفقیت ثبت شد.');

            $this->resetForm();
            $this->updateTransactions();
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در ثبت ارسال: ' . $e->getMessage());
            Log::error('Error in submitRemittance: ' . $e->getMessage());
        }
        $this->updateCustomerCurrencyBalance();
    }

    private function resetForm()
    {
        $this->reset([
            'amount',
            'currency',
            'description',
            'to_sarafi',
            'to_customer_id',
            'to_customer_search',
            'to_customer_selected',
            'file'
        ]);
        $this->amountInWords = null;
        $this->isEditMode = false;
        $this->editId = null;
        $this->to_customer_filtered = [];
    }

    public function cancel()
    {
        $this->resetForm();
    }

    public function edit($transactionId)
    {
        $transaction = Transaction::with('changerdeal')->find($transactionId);

        if ($transaction && $transaction->changerdeal) {
            $this->editId = $transaction->changerdeal->id;
            $this->isEditMode = true;

            // تنظیم مشتری فرستنده
            $this->selectedAccount = $transaction->changerdeal->from_customer;
            $this->selectedCustomerId = $transaction->changerdeal->from_customer;
            $this->selectedCustomer = Customer::find($this->selectedAccount);

            // تنظیم مشتری گیرنده
            $this->to_customer_id = $transaction->changerdeal->to_customer;
            $this->to_customer_selected = Customer::find($this->to_customer_id);
            $this->to_customer_search = $this->to_customer_selected->fullname ?? '';

            $this->to_sarafi = $transaction->changerdeal->to_sarafi;
            $this->amount = $transaction->changerdeal->amount;
            $this->currency = $transaction->changerdeal->currency;
            $this->date = $transaction->changerdeal->date;
            $this->zone = $transaction->changerdeal->zone;
            $this->description = $transaction->changerdeal->description;

            $this->updatedAmount($this->amount);
        }
    }


    public function confirmDelete($transactionId)
    {
        $this->confirmDeleteId = $transactionId;
    }

    public function deleteConfirmed()
    {
        try {
            $transaction = Transaction::find($this->confirmDeleteId);

            if ($transaction && $transaction->changerdeal) {
                // حذف تراکنش‌های مرتبط
                Transaction::where('changerdeal_id', $transaction->changerdeal_id)->delete();

                // حذف رکورد اصلی
                $transaction->changerdeal->delete();

                session()->flash('message', 'تراکنش با موفقیت حذف شد.');
            }

            $this->confirmDeleteId = null;
            $this->updateTransactions();
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در حذف تراکنش: ' . $e->getMessage());
        }
    }

 public function print($transactionId)
{
    $transaction = Transaction::with(['customer', 'changerdeal.fromCustomer', 'changerdeal.toCustomer', 'changerdeal.fromSarafiUser', 'changerdeal.toSarafiUser'])
        ->findOrFail($transactionId);

    $mpdf = new \Mpdf\Mpdf([
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

    $html = view('pdf.Sarafi.changersdeal', compact('transaction'))->render();
    $mpdf->WriteHTML($html);

    $fileName = 'ترانزکشن_شماره_' . $transaction->id . '_به_اسم_' . $transaction->customer->fullname . '.pdf';

    return response()->streamDownload(function () use ($mpdf) {
        echo $mpdf->Output('', 'S');
    }, $fileName);
}




    public function submitAndPrint()
    {
        $this->submitRemittance();
        // منطق پرینت بعد از ثبت
    }

    public function updateCustomerCurrencyBalance()
    {
        if (!$this->selectedCustomerId) {
            $this->resetBalances();
            return;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // محاسبه موجودی‌ها
        $cashBalances = [];
        $bankBalances = [];
        $totalBalances = [];

        $transactions = Transaction::where('customer_id', $this->selectedCustomerId)
            ->where('admin_id', $adminId)
            ->get();

        foreach ($transactions as $transaction) {
            $currencyName = $this->getCurrencyName($transaction->currency);
            $amount = $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;

            if ($transaction->account_type === 'نقدی') {
                $cashBalances[$currencyName] = ($cashBalances[$currencyName] ?? 0) + $amount;
            } else {
                $bankBalances[$currencyName] = ($bankBalances[$currencyName] ?? 0) + $amount;
            }
            $totalBalances[$currencyName] = ($totalBalances[$currencyName] ?? 0) + $amount;
        }

        $this->customerCashBalances = $cashBalances;
        $this->customerBankBalances = $bankBalances;
        $this->customerTotalBalances = $totalBalances;
    }

    private function resetBalances()
    {
        $this->customerCashBalances = [];
        $this->customerBankBalances = [];
        $this->customerTotalBalances = [];
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

    public function clearSearch()
    {
        $this->search = '';
        $this->filteredCustomers = [];
    }

    public function clearToCustomerSearch()
    {
        $this->to_customer_search = '';
        $this->to_customer_filtered = [];
    }

    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->search = '';
        $this->selectedCustomer = null;
        $this->to_customer_id = null;
        $this->to_customer_selected = null;
        $this->to_customer_search = '';
        $this->filteredCustomers = [];
        $this->to_customer_filtered = [];
        $this->resetBalances();
        $this->updateTransactions();
    }

    public function clearSearchAndFilter()
    {
        $this->clearFilter();
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

    public function updateTransactions()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->transactions = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        $query = Transaction::with(['customer', 'changerdeal.toSarafiUser', 'changerdeal.fromCustomer', 'changerdeal.toCustomer'])
            ->where('admin_id', $adminId)
            ->whereNotNull('changerdeal_id')
            ->whereIn('type', ['برد', 'رسید']);

        if ($this->selectedCustomerId) {
            // نمایش تراکنش‌های مربوط به مشتری انتخاب شده (هم برد و هم رسید)
            $query->where('customer_id', $this->selectedCustomerId);
        }

        $this->transactions = $query->latest()->get();
    }

    public function getSarafiName($transaction)
    {
        if ($transaction->type === 'برد') {
            // در برد، نشان می‌دهیم که به کدام صرافی ارسال شده
            return $transaction->changerdeal->toSarafiUser->sarafi_name ?? 'ناشناخته';
        } else {
            // در رسید، نشان می‌دهیم که از کدام صرافی دریافت شده
            return $transaction->changerdeal->fromSarafiUser->sarafi_name ?? 'ناشناخته';
        }
    }

    // برای نمایش نام مشتری دیگر در تراکنش
    public function getOtherCustomerName($transaction)
    {
        if ($transaction->type === 'برد') {
            // در برد، نشان می‌دهیم که برای کدام مشتری دیگر ارسال شده
            return $transaction->changerdeal->toCustomer->fullname ?? '-';
        } else {
            // در رسید، نشان می‌دهیم که از کدام مشتری دیگر دریافت شده
            return $transaction->changerdeal->fromCustomer->fullname ?? '-';
        }
    }

    public function render()
    {
        return view('livewire.sarafi.changers-deal');
    }
}
