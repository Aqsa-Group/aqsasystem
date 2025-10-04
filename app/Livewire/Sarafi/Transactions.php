<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use Livewire\Component;
use NumberFormatter;
use Morilog\Jalali\Jalalian;

class Transactions extends Component
{
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

    

    public function render()
    {

        $customer= Customer::all();
        return view('livewire.sarafi.transactions', compact('customer'));
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    // اکشن دکمه‌ها
    public function showReport($currencyName)
    {
        $this->dispatchBrowserEvent('report-alert', [
            'message' => "گزارش برای {$currencyName} نمایش داده خواهد شد"
        ]);
    }

    /**
     * وقتی مقدار تغییر کند
     */
    public function updatedAmount($value)
    {
        $number = str_replace(',', '', $value);
        $floatValue = (float) $number;

        if ($floatValue > 0) {
            $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
            $words = $formatter->format($floatValue);

            // اصلاحات سفارشی روی کلمات
            $words = str_replace('دویست', 'دوصد', $words);
            $words = str_replace('سیصد', 'سه صد', $words);
            $words = str_replace('پانصد', 'پنجصد', $words);

            $this->amountInWords = $words;
        } else {
            $this->amountInWords = null;
        }

        $this->amount = $number;
    }

       public $date; // تاریخ

    public function mount()
    {
        // مقدار اولیه تاریخ = امروز شمسی
        $this->date = Jalalian::now()->format('Y/m/d');
    }

    /**
     * وقتی کاربر از فیلد خارج شد (blur)
     * فرمت عدد انگلیسی + جداکننده هزارگان
     */
    public function formatAmount()
    {
        if ($this->amount) {
            $number = (int) str_replace(',', '', $this->amount);
            $this->amount = number_format($number); // مثل 1,234
        }
    }
}
