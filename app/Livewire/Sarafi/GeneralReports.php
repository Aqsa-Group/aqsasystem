<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\ExchangeRates;
use Livewire\Component;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralReports extends Component
{
    public $selectedCategory = null;
    public $selectedSubCategory = null;
    public $search = '';
    public $selectedCustomer = '';
    public $selectedCurrency = '';
    public $date;

    public $subCategories = [
        'customers' => ['گزارش بیلانس مشتریان', 'پرداخت‌های مشتری', 'صورتحساب‌ها'],
        'accounts' => ['گزارش صندوق', 'حساب‌های بانکی', 'ترازنامه'],
        'transactions' => ['معاملات خرید', 'معاملات فروش', 'تراکنش‌ها'],
        'management' => ['گزارش مدیریتی', 'تحلیل فروش', 'نمودارها']
    ];

    public $currencies = [
        'usd' => 'دالر',
        'afn' => 'افغانی',
        'irr' => 'تومان',
        'eur' => 'یورو',
        'pkr' => 'کلدار',
        'aed' => 'درهم',
        'try' => 'لیره',
        'cny' => 'یوان'
    ];

    public $reports = [];

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');

        $this->selectedCategory = 'customers';
        $this->selectedSubCategory = 'گزارش بیلانس مشتریان';

        $this->generateCustomerBalanceReport();
    }

    public function selectCategory($category)
    {
        $this->selectedCategory = $category;
        $this->selectedSubCategory = null;
        $this->reports = [];
    }

    public function updatedSelectedSubCategory($sub)
    {
        $this->selectedSubCategory = $sub;

        if ($sub === 'گزارش بیلانس مشتریان') {
            $this->generateCustomerBalanceReport();
        } else {

            $this->reports = [];
        }
    }


    private function generateCustomerBalanceReport()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $baseQuery = Customer::where('admin_id', $adminId);

        if ($this->selectedCustomer) {
            $baseQuery->where('related_customer_id', $this->selectedCustomer);
        }

        if ($this->search) {
            $baseQuery->where(function ($query) {
                $query->where('fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('account_number', 'like', '%' . $this->search . '%');
            });
        }

        $customers = $baseQuery->get();

        $this->reports = [];
        foreach ($customers as $customer) {
            $report = [
                'id' => $customer->id,
                'account_number' => $customer->account_number,
                'fullname' => $customer->fullname,
                'related_customer_name' => $this->getRelatedCustomerName($customer->related_customer_id),
                'last_date' => null,
                'balances' => [],
                'total_balance' => 0,
                'has_balance' => false
            ];

            foreach ($this->currencies as $currencyCode => $currencyName) {
                $balance = $this->calculateBalance($customer->id, $currencyCode);
                $report['balances'][$currencyCode] = $balance;

                if ($balance != 0 && !$report['last_date']) {
                    $report['last_date'] = $this->getLastTransactionDate($customer->id, $currencyCode);
                    $report['has_balance'] = true;
                }
            }

            $report['total_balance'] = $this->calculateTotalBalance($report['balances']);

            if ($report['has_balance']) {
                $this->reports[] = $report;
            }
        }

        if ($this->selectedCurrency) {
            $this->reports = array_filter($this->reports, function ($report) {
                return ($report['balances'][$this->selectedCurrency] ?? 0) != 0;
            });
        }
    }

    private function getRelatedCustomerName($relatedCustomerId)
    {
        if (!$relatedCustomerId) return null;
        $customer = Customer::find($relatedCustomerId);
        return $customer ? $customer->fullname : 'نامشخص';
    }

    private function calculateBalance($customerId, $currency)
    {
        return Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->select(DB::raw('SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance'))
            ->value('balance') ?? 0;
    }

    private function getLastTransactionDate($customerId, $currency)
    {
        return Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->max('date');
    }

    private function calculateTotalBalance($balances)
    {
        $latestExchangeRate = ExchangeRates::latest()->first();
        $exchangeRates = [
            'afn' => $latestExchangeRate->afn_buy ?? 0.011,
            'usd' => 1,
            'irr' => $latestExchangeRate->irr_buy ?? 0.000024,
            'eur' => $latestExchangeRate->eur_buy ?? 1.07,
            'pkr' => $latestExchangeRate->pkr_buy ?? 0.0036,
            'aed' => $latestExchangeRate->aed_buy ?? 0.27,
            'try' => $latestExchangeRate->try_buy ?? 0.031,
            'cny' => $latestExchangeRate->cny_buy ?? 0.14,
        ];

        $total = 0;
        foreach ($balances as $currency => $balance) {
            if (isset($exchangeRates[$currency]) && $balance != 0) {
                $total += $balance / $exchangeRates[$currency];
            }
        }
        return $total;
    }

    public function render()
    {
        return view('livewire.sarafi.general-reports');
    }
}
