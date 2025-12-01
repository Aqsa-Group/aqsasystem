<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use App\Models\Sarafi\ProfitRate;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class ProfitRates extends Component
{
    public $date;
    public $source_currency = 'usd';
    public $formData = [];
    public $currencies = [];

    public $editingId = null;
    public $isEditing = false;
    public $confirmDeleteId = null;

    // نقشه تبدیل کد ارز به نام فارسی
    private $currencyNameMap = [
        'usd' => 'دالر',
        'afn' => 'افغانی',
        'irr' => 'تومان',
        'eur' => 'یورو',
        'pkr' => 'کلدار',
        'aed' => 'درهم',
        'try' => 'لیره',
        'cny' => 'یوان چین'
    ];

    protected $rules = [
        'source_currency' => 'required|string',
        'date' => 'required|string',
        'formData.*.buy_cash' => 'nullable|numeric|min:0',
        'formData.*.buy_bank' => 'nullable|numeric|min:0',
        'formData.*.sell_cash' => 'nullable|numeric|min:0',
        'formData.*.sell_bank' => 'nullable|numeric|min:0',
    ];

    protected $messages = [
        'formData.*.buy_cash.numeric' => 'خرید نقدی باید عددی باشد.',
        'formData.*.buy_bank.numeric' => 'خرید بانکی باید عددی باشد.',
        'formData.*.sell_cash.numeric' => 'فروش نقدی باید عددی باشد.',
        'formData.*.sell_bank.numeric' => 'فروش بانکی باید عددی باشد.',
        'formData.*.buy_cash.min' => 'خرید نقدی نمی‌تواند منفی باشد.',
        'formData.*.buy_bank.min' => 'خرید بانکی نمی‌تواند منفی باشد.',
        'formData.*.sell_cash.min' => 'فروش نقدی نمی‌تواند منفی باشد.',
        'formData.*.sell_bank.min' => 'فروش بانکی نمی‌تواند منفی باشد.',
    ];

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->initializeFormData();
        $this->initializeCurrencies();
    }

    private function initializeCurrencies()
    {
        $this->currencies = [
            ['code' => 'usd', 'name_fa' => 'دالر'],
            ['code' => 'afn', 'name_fa' => 'افغانی'],
            ['code' => 'irr', 'name_fa' => 'تومان'],
            ['code' => 'eur', 'name_fa' => 'یورو'],
            ['code' => 'pkr', 'name_fa' => 'کلدار'],
            ['code' => 'aed', 'name_fa' => 'درهم'],
            ['code' => 'try', 'name_fa' => 'لیره'],
            ['code' => 'cny', 'name_fa' => 'یوان چین'],
        ];
    }

    public function getCurrencyName($currencyCode)
    {
        return $this->currencyNameMap[$currencyCode] ?? $currencyCode;
    }

    private function initializeFormData()
    {
        $allCurrencies = ['usd', 'afn', 'irr', 'eur', 'pkr', 'aed', 'try', 'cny'];
        $formCurrencies = array_filter($allCurrencies, function($currency) {
            return $currency !== $this->source_currency;
        });

        foreach ($formCurrencies as $currencyCode) {
            $this->formData[$currencyCode] = [
                'buy_cash' => '',
                'buy_bank' => '',
                'sell_cash' => '',
                'sell_bank' => '',
            ];
        }
    }

    public function updatedSourceCurrency()
    {
        $this->initializeFormData();
    }

    public function submit()
    {
        $this->validate();

        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            $data = [
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'source_currency' => $this->source_currency,
            ];

            // پر کردن داده‌ها برای هر ارز
            foreach ($this->formData as $currencyCode => $rates) {
                $data[$currencyCode . '_buy_cash'] = $rates['buy_cash'] ?: 0;
                $data[$currencyCode . '_buy_bank'] = $rates['buy_bank'] ?: 0;
                $data[$currencyCode . '_sell_cash'] = $rates['sell_cash'] ?: 0;
                $data[$currencyCode . '_sell_bank'] = $rates['sell_bank'] ?: 0;
            }

            if ($this->isEditing && $this->editingId) {
                $profitRate = ProfitRate::find($this->editingId);
                if ($profitRate) {
                    $profitRate->update($data);
                    session()->flash('message', 'نرخ ارز با موفقیت بروزرسانی شد.');
                }
            } else {
                ProfitRate::create($data);
                session()->flash('message', 'نرخ ارز با موفقیت ثبت شد.');
            }

            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در ثبت اطلاعات: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $profitRate = ProfitRate::find($id);

        if ($profitRate) {
            $this->editingId = $id;
            $this->isEditing = true;
            $this->source_currency = $profitRate->source_currency;
            $this->date = Jalalian::fromDateTime($profitRate->created_at)->format('Y/m/d');

            // بارگذاری داده‌های موجود
            $this->initializeFormData();
            
            foreach ($this->formData as $currencyCode => &$rates) {
                $rates['buy_cash'] = $profitRate->{$currencyCode . '_buy_cash'} ?? '';
                $rates['buy_bank'] = $profitRate->{$currencyCode . '_buy_bank'} ?? '';
                $rates['sell_cash'] = $profitRate->{$currencyCode . '_sell_cash'} ?? '';
                $rates['sell_bank'] = $profitRate->{$currencyCode . '_sell_bank'} ?? '';
            }
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        try {
            $profitRate = ProfitRate::find($this->confirmDeleteId);
            if ($profitRate) {
                $profitRate->delete();
                session()->flash('message', 'نرخ ارز با موفقیت حذف شد.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در حذف اطلاعات: ' . $e->getMessage());
        }

        $this->confirmDeleteId = null;
    }

    public function print($profitRateId)
    {
        $profitRate = ProfitRate::with(['user', 'admin'])->findOrFail($profitRateId);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 100],
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

        $html = view('pdf.Sarafi.profit-rate', compact('profitRate'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'نرخ_ارز_' . \Morilog\Jalali\Jalalian::fromDateTime($profitRate->created_at)->format('Y_m_d') . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    public function cancel()
    {
        $this->resetForm();
        session()->flash('message', 'عملیات لغو شد.');
    }

    private function resetForm()
    {
        $this->initializeFormData();
        $this->source_currency = 'usd';
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->editingId = null;
        $this->isEditing = false;
        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $records = ProfitRate::with(['user', 'admin'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.sarafi.profit-rates', [
            'records' => $records
        ]);
    }
}