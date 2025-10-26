<?php

namespace App\Livewire\ToolsPanel;

use App\Models\Tools\ShopSafe;
use App\Models\Tools\Customer;
use App\Models\Tools\ShopTransactions; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use NumberFormatter;

class ShopTransaction extends Component
{

    use WithFileUploads;
    
    public $confirmDeleteId = null;
    public $customer_id;
    public $selectedAccount;
    public $byUser;
    public $currency;
    public $currencies = [];
    public $amount;
    public $amountInWords;
    public $customers;
    public $transactionType = 'برد';
    public $date;
    public $description;
    public $file;
    public $by;
    public $transactionId;
    public $search = '';
    public $selectedCustomerId = null;
    public $transactions = [];
    public $filteredCustomers = [];
    public $additionalCustomers = [];
    public $accountSearch = '';
    public $shopSafeBalance = [];
    public $showInsufficientBalanceError = false;

    public $currenciesdefault = [
        ['name' => 'افغانی', 'value' => 0],
        ['name' => 'دالر', 'value' => 0],
        ['name' => 'تومان', 'value' => 0],
        ['name' => 'کلدار', 'value' => 0],
        ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
    ];

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');

        $this->currencies = [
            ['code' => 'usd', 'name_fa' => 'دالر'],
            ['code' => 'afn', 'name_fa' => 'افغانی'],
            ['code' => 'irr', 'name_fa' => 'تومان'],     
            ['code' => 'pkr', 'name_fa' => 'کلدار'],
        ];

        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance();
        $this->updateShopSafeBalance();

        $user = Auth::guard('tools')->user();
        if (!$user) {
            $this->customers = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = \App\Models\Tools\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        $this->customers = Customer::select('id', 'account_number', 'fullname')
            ->where(function ($query) use ($adminId, $relatedUserIds) {
                $query->where('admin_id', $adminId)
                    ->orWhereHas('shopTransactions', function ($t) use ($relatedUserIds) {
                        $t->whereIn('user_id', $relatedUserIds)
                            ->orWhereIn('admin_id', $relatedUserIds);
                    });
            })
            ->orderBy('fullname')
            ->get();

        $this->customers = collect($this->customers);
    }

    /**
     * به روزرسانی موجودی صندوق
     */
    public function updateShopSafeBalance()
    {
        $user = Auth::guard('tools')->user();
        if (!$user) {
            $this->shopSafeBalance = [
                'afn' => 0,
                'usd' => 0,
                'irr' => 0,
                'pkr' => 0,
            ];
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        
        $safe = ShopSafe::where('user_id', $adminId)
            ->where('admin_id', null)
            ->first();

        if ($safe) {
            $this->shopSafeBalance = [
                'afn' => $safe->afn,
                'usd' => $safe->usd,
                'irr' => $safe->irr,
                'pkr' => $safe->pkr,
            ];
        } else {
            $this->shopSafeBalance = [
                'afn' => 0,
                'usd' => 0,
                'irr' => 0,
                'pkr' => 0,
            ];
        }
    }

    /**
     * بررسی موجودی کافی در صندوق
     */
    public function checkSafeBalance()
    {
        if ($this->transactionType !== 'رسید' || !$this->currency || !$this->amount) {
            $this->showInsufficientBalanceError = false;
            return true;
        }

        $amount = (float) str_replace(',', '', $this->amount);
        $currentBalance = $this->shopSafeBalance[$this->currency] ?? 0;

        if ($currentBalance < $amount) {
            $this->showInsufficientBalanceError = true;
            return false;
        }

        $this->showInsufficientBalanceError = false;
        return true;
    }

    public function updatedAccountSearch($value)
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        $relatedUserIds = \App\Models\Tools\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        $this->filteredCustomers = Customer::where(function ($query) use ($adminId, $relatedUserIds) {
            $query->where('admin_id', $adminId)
                ->orWhereHas('shopTransactions', function ($t) use ($relatedUserIds) {
                    $t->whereIn('user_id', $relatedUserIds)
                        ->orWhereIn('admin_id', $relatedUserIds);
                });
        })
            ->where(function ($q) use ($value) {
                $q->where('fullname', 'like', "%{$value}%")
                    ->orWhere('account_number', 'like', "%{$value}%");
            })
            ->orderBy('fullname')
            ->limit(15)
            ->get();
    }

    public function updatedSearch($value)
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        if (empty($value)) {
            $this->selectedCustomerId = null;
            $this->filteredCustomers = [];
            $this->updateTransactions();
            return;
        }

        $this->filteredCustomers = Customer::where(function ($query) use ($value) {
            $query->where('fullname', 'like', "%{$value}%")
                ->orWhere('account_number', 'like', "%{$value}%");
        })
            ->limit(15)
            ->get();

      if (count($this->filteredCustomers) === 1) {
    $this->selectCustomer($this->filteredCustomers[0]['id']);
} else {
    $this->selectedCustomerId = null;
    $this->updateTransactions();
}

    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->selectedAccount = $customerId;
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

            $this->updateTransactions();
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

    public function updateCustomerCurrencyBalance()
    {
        if (!$this->selectedCustomerId) {
            $this->currenciesdefault = [
                ['name' => 'افغانی', 'value' => 0],
                ['name' => 'دالر', 'value' => 0],
                ['name' => 'تومان', 'value' => 0],
                ['name' => 'کلدار', 'value' => 0],
                ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
            ];
            return;
        }

        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        // استفاده از مدل ShopTransactions برای محاسبه موجودی
        $transactions = ShopTransactions::where('customer_id', $this->selectedCustomerId)
            ->where('admin_id', $adminId)
            ->get();
            
        $balances = [
            'افغانی' => 0,
            'دالر' => 0,
            'تومان' => 0,
            'کلدار' => 0,
        ];

        foreach ($transactions as $transaction) {
            $currencyName = $this->getCurrencyName($transaction->currency);
            $amount = $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;

            if (array_key_exists($currencyName, $balances)) {
                $balances[$currencyName] += $amount;
            }
        }

        $totalInUsd = 0;
        $exchangeRates = [
            'افغانی' => 0.011,
            'دالر' => 1,
            'تومان' => 0.000024,
            'کلدار' => 0.0036,
        ];

        foreach ($balances as $currency => $balance) {
            if ($currency !== 'خلاصه بیلانس به دالر' && isset($exchangeRates[$currency])) {
                $totalInUsd += $balance * $exchangeRates[$currency];
            }
        }

        $this->currenciesdefault = [
            ['name' => 'افغانی', 'value' => $balances['افغانی']],
            ['name' => 'دالر', 'value' => $balances['دالر']],
            ['name' => 'تومان', 'value' => $balances['تومان']],
            ['name' => 'کلدار', 'value' => $balances['کلدار']],
            ['name' => 'خلاصه بیلانس به دالر', 'value' => $totalInUsd],
        ];
    }

    private function getCurrencyName($currencyCode)
    {
        $currencyMap = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'irr' => 'تومان',
            'pkr' => 'کلدار',
        ];

        return $currencyMap[$currencyCode] ?? $currencyCode;
    }

    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->search = '';
        $this->filteredCustomers = [];
        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->filteredCustomers = [];
        $this->updateCustomerCurrencyBalance();
    }

    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->filteredCustomers = [];
        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance(); 
    }

    public function updateTransactions()
    {
        $user = Auth::guard('tools')->user();
        if (!$user) {
            $this->transactions = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        // استفاده از مدل ShopTransactions برای نمایش تراکنش‌ها
        $query = ShopTransactions::with('customer')
            ->where('admin_id', $adminId)
            ->whereIn('type', ['برد', 'رسید']); 

        if ($this->selectedCustomerId) {
            $query->where('customer_id', $this->selectedCustomerId);
        }

        $this->transactions = $query->latest()->get();
    }

    public function render()
    {
        $user = Auth::guard('tools')->user();

        if (!$user) {
            return view('livewire.tools-panel.shop-transactions', [
                'customers' => collect(),
                'transactions' => collect(),
            ]);
        }

        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = \App\Models\Tools\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        if (!$this->customers || $this->customers->isEmpty()) {
            $this->customers = Customer::select('id', 'account_number', 'fullname')
                ->where(function ($query) use ($adminId, $relatedUserIds) {
                    $query->where('admin_id', $adminId)
                        ->orWhereHas('shopTransactions', function ($t) use ($relatedUserIds) {
                            $t->whereIn('user_id', $relatedUserIds)
                                ->orWhereIn('admin_id', $relatedUserIds);
                        });
                })
                ->orderBy('fullname')
                ->get();

            $this->customers = collect($this->customers);
        }

        return view('livewire.tools-panel.shop-transaction', [
            'customers' => $this->customers,
            'transactions' => $this->transactions,
            'shopSafeBalance' => $this->shopSafeBalance,
            'showInsufficientBalanceError' => $this->showInsufficientBalanceError,
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

        // بررسی موجودی هنگام تغییر مقدار
        $this->checkSafeBalance();
    }

    public function updatedCurrency($value)
    {
        // بررسی موجودی هنگام تغییر ارز
        $this->checkSafeBalance();
    }

    public function updatedTransactionType($value)
    {
        // بررسی موجودی هنگام تغییر نوع تراکنش
        $this->checkSafeBalance();
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
        $this->checkSafeBalance();
    }

    public function edit($id)
    {
        $transaction = ShopTransactions::findOrFail($id);
        $this->transactionId = $id;
        $this->selectedAccount = $transaction->customer_id;
        $this->amount = $transaction->amount;
        $this->currency = $transaction->currency;
        $this->byUser = $transaction->by;
        $this->date = $transaction->date;
        $this->description = $transaction->description;
        $this->transactionType = $transaction->type;
        
        $this->checkSafeBalance();
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        $transaction = ShopTransactions::findOrFail($this->confirmDeleteId);

        $user = Auth::guard('tools')->user();
        $this->applyShopSafeChange($user, $transaction->currency, $transaction->amount, $transaction->type, true);

        $transaction->delete();

        session()->flash('message', 'ترانزکشن موفقانه حذف گردید.');
        $this->updateTransactions();
        $this->updateShopSafeBalance();
        $this->confirmDeleteId = null;
    }

    public function submitTransaction()
    {
        // بررسی موجودی کافی
        if (!$this->checkSafeBalance()) {
            session()->flash('error', 'موجودی صندوق کافی نیست!');
            return;
        }

        $this->selectedAccount = (int) $this->selectedAccount;
        $this->amount = str_replace(',', '', $this->amount);
        $user = Auth::guard('tools')->user();

        $this->validate([
            'selectedAccount' => 'required|integer|exists:tools.customers,id',
            'byUser'         => 'nullable|string|max:255',
            'currency'       => 'required|string',
            'amount'         => 'required|numeric|min:1',
            'transactionType' => 'required|string',
            'date'           => 'required|date',
            'description'    => 'nullable|string|max:500',
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
            'by'               => $this->byUser,
        ];

        if ($this->transactionId) {
            $oldTransaction = ShopTransactions::findOrFail($this->transactionId);
            
            // بازگرداندن تغییرات قبلی
            $this->applyShopSafeChange($user, $oldTransaction->currency, $oldTransaction->amount, $oldTransaction->type, true);
            
            // بروزرسانی تراکنش
            $oldTransaction->update($data);
            
            // اعمال تغییرات جدید
            $this->applyShopSafeChange($user, $this->currency, $this->amount, $this->transactionType);
            
            session()->flash('message', 'تراکنش با موفقیت بروزرسانی شد.');
        } else {
            $transaction = ShopTransactions::create($data);
            $this->applyShopSafeChange($user, $this->currency, $this->amount, $this->transactionType);
            session()->flash('message', 'تراکنش با موفقیت ثبت شد.');
        }

        $this->updateTransactions();
        $this->updateShopSafeBalance();
        $this->resetForm();
    }

    public function submitAndPrint()
    {
        // بررسی موجودی کافی
        if (!$this->checkSafeBalance()) {
            session()->flash('error', 'موجودی صندوق کافی نیست!');
            return;
        }

        $this->selectedAccount = (int) $this->selectedAccount;
        $this->amount = str_replace(',', '', $this->amount);
        $user = Auth::guard('tools')->user();

        $this->validate([
            'selectedAccount'  => 'required|exists:tools.customers,id',
            'byUser'           => 'nullable|string|max:255',
            'currency'         => 'required|string',
            'amount'           => 'required|numeric|min:1',
            'transactionType'  => 'required|string',
            'date'             => 'required|date',
            'description'      => 'nullable|string|max:500',
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
            'by'               => $this->byUser,
        ];

        if ($this->transactionId) {
            $oldTransaction = ShopTransactions::findOrFail($this->transactionId);
            $this->applyShopSafeChange($user, $oldTransaction->currency, $oldTransaction->amount, $oldTransaction->type, true);
            $oldTransaction->update($data);
            $this->applyShopSafeChange($user, $this->currency, $this->amount, $this->transactionType);
            $transaction = $oldTransaction;
            session()->flash('message', 'تراکنش با موفقیت بروزرسانی شد.');
        } else {
            $transaction = ShopTransactions::create($data);
            $this->applyShopSafeChange($user, $this->currency, $this->amount, $this->transactionType);
            session()->flash('message', 'تراکنش با موفقیت ثبت شد.');
        }

        $this->updateTransactions();
        $this->updateShopSafeBalance();
        $this->resetForm();

        // چاپ رسید
        $html = view('pdf.Tools.transaction', ['transaction' => $transaction])->render();

        $mpdf = new Mpdf([
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
        $mpdf->WriteHTML($html);

        $fileName = 'ترانزکشن_شماره_' . $transaction->id . '_به_اسم_' . $transaction->customer->fullname . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    public function print($transactionId)
    {
        $transaction = ShopTransactions::with(['customer', 'user'])->findOrFail($transactionId);

        $mpdf = new Mpdf([
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
        $html = view('pdf.Tools.transaction', compact('transaction'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'ترانزکشن_شماره_' . $transaction->id . '_به_اسم_' . $transaction->customer->fullname . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    /**
     * اعمال تغییرات در صندوق دوکان
     */
    private function applyShopSafeChange($user, $currency, $amount, $transactionType, $reverse = false)
    {
        $adminId = $user->admin_id ?? $user->id;
        $factor = $reverse ? -1 : 1;

        // منطق کسب و کار:
        // - رسید: از صندوق کم شده و به حساب مشتری اضافه می‌شود
        // - برد: از حساب مشتری کم شده و به صندوق اضافه می‌شود
        
        if ($transactionType === 'رسید') {
            $safeChange = -$amount * $factor; // از صندوق کم می‌شود
        } else {
            $safeChange = $amount * $factor; // به صندوق اضافه می‌شود
        }

        // پیدا کردن یا ایجاد رکورد صندوق
        $safe = ShopSafe::firstOrCreate(
            [
                'user_id' => $adminId,
                'admin_id' => null
            ],
            [
                'afn' => 0,
                'usd' => 0,
                'irr' => 0,
                'pkr' => 0,
            ]
        );

        // به روزرسانی موجودی
        if (isset($safe->$currency)) {
            $safe->$currency += $safeChange;
            
            // جلوگیری از موجودی منفی
            if ($safe->$currency < 0) {
                $safe->$currency = 0;
            }
            
            $safe->save();
        }

        Log::debug("Shop safe updated", [
            'user_id' => $adminId,
            'currency' => $currency,
            'amount' => $amount,
            'transaction_type' => $transactionType,
            'safe_change' => $safeChange,
            'new_balance' => $safe->$currency
        ]);
    }

    public function showReport()
    {
        if (!$this->selectedCustomerId) {
            session()->flash('error', 'لطفاً ابتدا یک مشتری را انتخاب کنید');
            return;
        }

        session(['selected_customer_id' => $this->selectedCustomerId]);
        return redirect()->route('tools.transaction-reports');
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
            'transactionId',
        ]);

        $this->date = Jalalian::now()->format('Y/m/d');
        $this->transactionType = 'برد';
        $this->showInsufficientBalanceError = false;
        $this->updateShopSafeBalance();
    }
}