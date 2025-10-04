<?php

namespace App\Livewire\Sarafi;



use App\Models\Sarafi\Customer;
use App\Models\Sarafi\User;
use App\Models\Sarafi\Currency;
use App\Models\Sarafi\Transaction;


use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;
use NumberFormatter;

class Transactions extends Component
{
    use WithFileUploads;

    public $customer_id;
    public $user_id;
    public $admin_id;
    public $currency_id;
   
    public $type; // برد/پرداخت یا هر نوع تراکنش
    public $date;
    public $description;
    public $transaction_file;

 



    public $activeTab = 'general'; 

    public $currencies = [
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

    public $amount;           // مقدار عددی
    public $currency;         // ارز انتخابی
    public $amountInWords;    // مقدار به حروف فارسی


    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
    }

    public function render()
    {
        $customer = Customer::all();
        $users = User::all();
        $currencies = Currency::all();

        return view('livewire.sarafi.transactions', compact('customer', 'users', 'currencies'));
    }

    public function updatedAmount($value)
    {
        $number = str_replace(',', '', $value);
        $floatValue = (float)$number;

        if ($floatValue > 0) {
            $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
            $words = $formatter->format($floatValue);

            $words = str_replace('دویست', 'دوصد', $words);
            $words = str_replace('سیصد', 'سه صد', $words);
            $words = str_replace('پانصد', 'پنجصد', $words);

            $this->amountInWords = $words;
        } else {
            $this->amountInWords = null;
        }

        $this->amount = $number;
    }

    public function formatAmount()
    {
        if ($this->amount) {
            $number = (int)str_replace(',', '', $this->amount);
            $this->amount = number_format($number);
        }
    }

    public function saveTransaction()
    {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            'admin_id' => 'nullable|exists:users,id',
            'currency_id' => 'required|exists:currencies,id',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|string',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'transaction_file' => 'nullable|file|max:10240', // 10MB
        ]);

        $filePath = null;
        if ($this->transaction_file) {
            $filePath = $this->transaction_file->store('transactions', 'public');
        }

        Transaction::create([
            'customer_id' => $this->customer_id,
            'user_id' => $this->user_id,
            'admin_id' => $this->admin_id,
            'currency_id' => $this->currency_id,
            'amount' => $this->amount,
            'type' => $this->type,
            'date' => $this->date,
            'description' => $this->description,
            'transaction_file' => $filePath,
        ]);

        session()->flash('message', 'تراکنش با موفقیت ثبت شد.');

        // ریست فیلدها
        $this->reset(['customer_id','user_id','admin_id','currency_id','amount','type','description','transaction_file','amountInWords']);
        $this->date = Jalalian::now()->format('Y/m/d');
    }


    public $transactionType = 'برد'; // مقدار اولیه

public function toggleTransactionType()
{
    if ($this->transactionType === 'برد') {
        $this->transactionType = 'رسید';
    } else {
        $this->transactionType = 'برد';
    }
}

}
