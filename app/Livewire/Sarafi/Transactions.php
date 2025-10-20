<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use NumberFormatter;



class Transactions extends Component
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

    public $zone;
    public $by;
    public $transactionId;

    public $search = '';

    public $selectedCustomerId = null;
    public $transactions = [];

    public $filteredCustomers;
    public $additionalCustomers = [];
    public $accountSearch = '';



    public function updatedAccountSearch($value)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $relatedUserIds = \App\Models\Sarafi\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        $this->filteredCustomers = Customer::where(function ($query) use ($adminId, $relatedUserIds) {
            $query->where('admin_id', $adminId)
                ->orWhereHas('transactions', function ($t) use ($relatedUserIds) {
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

        $this->updateTransactions();
        $this->updateCustomerCurrencyBalance();

        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->customers = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
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
            ->get();

        $this->customers = collect($this->customers);
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

        $this->filteredCustomers = Customer::where(function ($query) use ($value) {
            $query->where('fullname', 'like', "%{$value}%")
                ->orWhere('account_number', 'like', "%{$value}%");
        })
            ->limit(15)
            ->get();


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
        $this->selectedAccount = $customerId;
        $this->filteredCustomers = [];

        $customer = Customer::find($customerId);
        if ($customer) {
            $this->search = $customer->fullname; // این خط اضافه شده

            if (!$this->customers->contains('id', $customer->id)) {
                $this->customers->push($customer);
            }

            $this->dispatch('account-selected', [
                'id' => $customer->id,
                'text' => $customer->account_number . ' - ' . $customer->fullname,
            ]);

            $this->updateTransactions();
            $this->updateCustomerCurrencyBalance();

            // لاگ برای دیباگ
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

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $transactions = Transaction::where('customer_id', $this->selectedCustomerId)
            ->where('admin_id', $adminId)
            ->get();
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
    $user = Auth::guard('sarafi')->user();
    if (!$user) {
        $this->transactions = collect();
        return;
    }

    $adminId = $user->admin_id ?? $user->id;

    $query = Transaction::with('customer')
        ->where('admin_id', $adminId)
        ->whereIn('type', ['برد', 'رسید']); 

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
        $relatedUserIds = \App\Models\Sarafi\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        if (!$this->customers || $this->customers->isEmpty()) {
            $this->customers = Customer::select('id', 'account_number', 'fullname')
                ->where(function ($query) use ($adminId, $relatedUserIds) {
                    $query->where('admin_id', $adminId)
                        ->orWhereHas('transactions', function ($t) use ($relatedUserIds) {
                            $t->whereIn('user_id', $relatedUserIds)
                                ->orWhereIn('admin_id', $relatedUserIds);
                        });
                })
                ->orderBy('fullname')
                ->get();

            $this->customers = collect($this->customers);
        }

        return view('livewire.sarafi.transactions', [
            'customers' => $this->customers,
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


    public function setDefaultZone()
{
    if (empty($this->zone)) {
        $this->zone = Auth::guard('sarafi')->user()->zone;
    }
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


    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        $transaction = Transaction::findOrFail($this->confirmDeleteId);

        $user = Auth::guard('sarafi')->user();
        $this->applyCurrencyChange($user, $transaction->currency, $transaction->amount, $transaction->type, true);

        $transaction->delete();

        session()->flash('message', 'ترانزکشن موفقـــــانــــــه حذف گردید.');

        $this->updateTransactions();

        $this->confirmDeleteId = null;
    }



    public function submitTransaction()
    {


        $this->selectedAccount = (int) $this->selectedAccount;
        $this->amount = str_replace(',', '', $this->amount);
        $user = Auth::guard('sarafi')->user();

        $this->validate([
            'selectedAccount' => 'required|integer|exists:sarafi.customers,id',
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



    public function submitAndPrint()
    {
        $this->selectedAccount = (int) $this->selectedAccount;
        $this->amount = str_replace(',', '', $this->amount);
        $user = Auth::guard('sarafi')->user();

        $this->validate([
            'selectedAccount'  => 'required|exists:sarafi.customers,id',
            'byUser'           => 'nullable|string|max:255',
            'currency'         => 'required|string',
            'amount'           => 'required|numeric|min:1',
            'transactionType'  => 'required|string',
            'date'             => 'required|date',
            'description'      => 'nullable|string|max:500',
            'zone'             => 'required|string',
            'file'             => 'nullable|file|max:10240',
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

            $transaction = $old;

            session()->flash('message', 'تراکنش با موفقیت بروزرسانی شد.');
        } else {
            $transaction = Transaction::create($data);

            $this->applyCurrencyChange($user, $this->currency, $this->amount, $this->transactionType);

            session()->flash('message', 'تراکنش با موفقیت ثبت شد.');
        }

        $this->updateTransactions();
        $this->resetForm();



        $html = view('pdf.Sarafi.transaction', ['transaction' => $transaction])->render();


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
        $transaction = Transaction::with(['customer', 'user'])->findOrFail($transactionId);

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

        $html = view('pdf.Sarafi.transaction', compact('transaction'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'ترانزکشن_شماره_' . $transaction->id . '_به_اسم_' . $transaction->customer->fullname . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
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




    public function showReport()
    {
        if (!$this->selectedCustomerId) {
            session()->flash('error', 'لطفاً ابتدا یک مشتری را انتخاب کنید');
            return;
        }

        // ذخیره ID مشتری در سشن
        session(['selected_customer_id' => $this->selectedCustomerId]);

        // انتقال به صفحه گزارشات
        return redirect()->route('sarafi.transaction-reports'); // مسیر صفحه گزارشات
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
            'transactionId',
        ]);

        $this->date = Jalalian::now()->format('Y/m/d');
        $this->transactionType = 'برد';
        $this->zone = Auth::guard('sarafi')->user()->zone; 
    }
}
