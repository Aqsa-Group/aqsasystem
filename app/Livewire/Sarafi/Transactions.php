<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Currency;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;
use NumberFormatter;

class Transactions extends Component
{
    use WithFileUploads;

    // --- مشتری / کاربر ---
    public $customer_id;
    public $selectedAccount; // برای انتخاب شماره حساب
    public $byUser;          // کاربری که تراکنش توسط او ثبت می‌شود

    // --- ارز و مقدار ---
    public $currency;
    public $currencies = [];   // لیست ارزها
    public $amount;
    public $amountInWords;

    // --- تراکنش ---
    public $transactionType = 'برد'; // نوع تراکنش از toggle
    public $date;
    public $description;
    public $file;

    // --- زون ---
    public $zone;
    public $by;

    public $transactionId;

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

    // --- Mount ---
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
    }

    // --- Render ---
    public function render()
    {
        $customers = Customer::select('id', 'account_number', 'fullname')->get();
        $transactions = Transaction::latest()->get();
        return view('livewire.sarafi.transactions', compact('customers', 'transactions'));
    }

    // --- Amount Handling ---
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

    // --- Toggle تراکنش ---
    public function toggleTransactionType()
    {
        $this->transactionType = $this->transactionType === 'برد' ? 'رسید' : 'برد';
    }

    // --- Edit تراکنش ---
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

    // --- Delete تراکنش ---
  public function delete($id)
{
    $transaction = Transaction::findOrFail($id);
    $transaction->delete();

    // بدون استفاده از dispatchBrowserEvent
    session()->flash('message', 'تراکنش با موفقیت حذف شد.');
}

    // --- Submit تراکنش ---
    public function submitTransaction()
    {
        $this->selectedAccount = (int)$this->selectedAccount;
        $this->amount = str_replace(',', '', $this->amount);

        $this->validate([
            'selectedAccount' => 'required|exists:sarafi.customers,id',
            'byUser' => 'nullable|string|max:255',
            'currency' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'transactionType' => 'required|string',
            'date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'zone' => 'required|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $filePath = $this->file ? $this->file->store('transactions', 'public') : null;

        $data = [
            'customer_id' => $this->selectedAccount,
            'user_id' => Auth::guard('sarafi')->user()->id,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'type' => $this->transactionType,
            'date' => $this->date,
            'description' => $this->description,
            'zone' => $this->zone,
            'transaction_file' => $filePath,
            'admin_id' => Auth::guard('sarafi')->user()->admin_id,
            'by' => $this->byUser,
        ];

        if($this->transactionId){
            Transaction::findOrFail($this->transactionId)->update($data);
            session()->flash('message', 'تراکنش با موفقیت بروزرسانی شد.');
        } else {
            Transaction::create($data);
            session()->flash('message', 'تراکنش با موفقیت ثبت شد.');
        }

        $this->resetForm();
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
