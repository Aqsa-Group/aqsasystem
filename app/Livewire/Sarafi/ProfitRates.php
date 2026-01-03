<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use App\Models\Sarafi\ProfitRate;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;

class ProfitRates extends Component
{
    public $date;
    public $source_currency = 'usd';
    public $formData = [];
    public $currencies = [];

    public $editingId = null;
    public $isEditing = false;
    public $confirmDeleteId = null;

    // ارزهای سیستمی (تأیید شده توسط کاربر)
    private $currencyCodes = ['usd', 'afn', 'irr', 'eur', 'pkr', 'aed', 'try', 'cny'];

    // نگاشت نام فارسی
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
    ];

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->initializeCurrencies();
        $this->initializeFormData();
    }

    private function initializeCurrencies()
    {
        $this->currencies = array_map(function ($code) {
            return ['code' => $code, 'name_fa' => $this->getCurrencyName($code)];
        }, $this->currencyCodes);
    }

    public function getCurrencyName($currencyCode)
    {
        return $this->currencyNameMap[$currencyCode] ?? $currencyCode;
    }

    private function initializeFormData()
    {
        // بازنشانی فرم‌دیتا به تمام ارزها به جز ارز مبدأ
        $this->formData = [];
        foreach ($this->currencyCodes as $code) {
            if ($code === $this->source_currency) continue;
            $this->formData[$code] = [
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

            // مقداردهی اولیه تمام ارزها با صفر
            foreach ($this->currencyCodes as $c) {
                $data[$c . '_buy_cash'] = 0;
                $data[$c . '_buy_bank'] = 0;
                $data[$c . '_sell_cash'] = 0;
                $data[$c . '_sell_bank'] = 0;
            }

            // انتقال فرم‌دیتا
            foreach ($this->formData as $code => $rates) {
                $data[$code . '_buy_cash']  = $rates['buy_cash']  !== '' ? floatval($rates['buy_cash']) : 0;
                $data[$code . '_buy_bank']  = $rates['buy_bank']  !== '' ? floatval($rates['buy_bank']) : 0;
                $data[$code . '_sell_cash'] = $rates['sell_cash'] !== '' ? floatval($rates['sell_cash']) : 0;
                $data[$code . '_sell_bank'] = $rates['sell_bank'] !== '' ? floatval($rates['sell_bank']) : 0;
            }

            // حالت ویرایش
            if ($this->isEditing && $this->editingId) {
                $profitRate = ProfitRate::find($this->editingId);

                if ($profitRate) {
                    $profitRate->update($data);

                    // اگر USD ویرایش شد → کل سیستم را بازسازی کن
                    if ($this->source_currency === 'usd') {
                        $this->generateAllReverseRates($data);
                    }

                    session()->flash('message', 'نرخ ارز با موفقیت بروزرسانی شد.');
                }
            }

            // ایجاد جدید
            else {
                $created = ProfitRate::create($data);
                session()->flash('message', 'نرخ ارز با موفقیت ثبت شد.');

                // اگر USD ساخته شد → بقیه ارزها تولید شود
                if ($this->source_currency === 'usd') {
                    $this->generateAllReverseRates($data);
                }
            }

            $this->resetForm();
        } catch (\Exception $e) {
            Log::error('خطا در ثبت نرخ: ' . $e->getMessage());
            session()->flash('error', 'خطا در ثبت اطلاعات: ' . $e->getMessage());
        }
    }

    /**
     * تولید رکوردهای معکوس و تکمیل جدول نرخ‌ها
     */
     private function generateAllReverseRates(array $baseRecord)
    {
        $baseSource = $baseRecord['source_currency'];
        $baseCurrencyCode = strtolower($baseSource);

        foreach ($this->currencyCodes as $targetCurrency) {
            if ($targetCurrency === $baseSource) continue;

            $reverse = [
                'user_id' => $baseRecord['user_id'],
                'admin_id' => $baseRecord['admin_id'],
                'source_currency' => $targetCurrency,
            ];

            // مقداردهی اولیه تمام نرخ‌ها به صفر
            foreach ($this->currencyCodes as $other) {
                $reverse[$other . '_buy_cash'] = 0;
                $reverse[$other . '_buy_bank'] = 0;
                $reverse[$other . '_sell_cash'] = 0;
                $reverse[$other . '_sell_bank'] = 0;
            }

            // تنظیم نرخ خود ارز به صفر
            $reverse[$targetCurrency . '_buy_cash'] = 0;
            $reverse[$targetCurrency . '_buy_bank'] = 0;
            $reverse[$targetCurrency . '_sell_cash'] = 0;
            $reverse[$targetCurrency . '_sell_bank'] = 0;

            foreach ($this->currencyCodes as $other) {
                if ($other === $targetCurrency) continue;

                // استثنا برای جفت تومان-افغانی و افغانی-تومان
                // در این حالت نرخ در هر دو جهت یکسان است
                if (($targetCurrency === 'afn' && $other === 'irr') ||
                    ($targetCurrency === 'irr' && $other === 'afn')) {
                    
                    // محاسبه نرخ تومان به افغانی از طریق دلار
                    $afnBuyCash = $baseRecord['afn_buy_cash'] ?? 0;
                    $afnBuyBank = $baseRecord['afn_buy_bank'] ?? 0;
                    $afnSellCash = $baseRecord['afn_sell_cash'] ?? 0;
                    $afnSellBank = $baseRecord['afn_sell_bank'] ?? 0;
                    
                    $irrBuyCash = $baseRecord['irr_buy_cash'] ?? 0;
                    $irrBuyBank = $baseRecord['irr_buy_bank'] ?? 0;
                    $irrSellCash = $baseRecord['irr_sell_cash'] ?? 0;
                    $irrSellBank = $baseRecord['irr_sell_bank'] ?? 0;

                    // محاسبه نرخ تومان به افغانی برای هر نوع معامله
                    // خرید نقدی: (نرخ دلار به افغانی) ÷ (نرخ دلار به تومان) × 1000
                    if ($irrBuyCash > 0 && $afnBuyCash > 0) {
                        $rateBuyCash = ($afnBuyCash / $irrBuyCash) * 1000;
                    } else {
                        $rateBuyCash = 0;
                    }
                    
                    if ($irrBuyBank > 0 && $afnBuyBank > 0) {
                        $rateBuyBank = ($afnBuyBank / $irrBuyBank) * 1000;
                    } else {
                        $rateBuyBank = 0;
                    }
                    
                    if ($irrSellCash > 0 && $afnSellCash > 0) {
                        $rateSellCash = ($afnSellCash / $irrSellCash) * 1000;
                    } else {
                        $rateSellCash = 0;
                    }
                    
                    if ($irrSellBank > 0 && $afnSellBank > 0) {
                        $rateSellBank = ($afnSellBank / $irrSellBank) * 1000;
                    } else {
                        $rateSellBank = 0;
                    }
                    
                    // برای هر دو جهت (افغانی به تومان و تومان به افغانی) نرخ یکسان است
                    $reverse[$other . '_buy_cash'] = $rateBuyCash;
                    $reverse[$other . '_buy_bank'] = $rateBuyBank;
                    $reverse[$other . '_sell_cash'] = $rateSellCash;
                    $reverse[$other . '_sell_bank'] = $rateSellBank;
                    
                    continue;
                }

                // محاسبه نرخ‌های عادی برای سایر ارزها
                if ($other === $baseCurrencyCode) {
                    // اگر other همان ارز پایه (USD) است
                    $targetRateBuyCash = $baseRecord[$targetCurrency . '_buy_cash'] ?? 0;
                    $targetRateSellCash = $baseRecord[$targetCurrency . '_sell_cash'] ?? 0;
                    $targetRateBuyBank = $baseRecord[$targetCurrency . '_buy_bank'] ?? 0;
                    $targetRateSellBank = $baseRecord[$targetCurrency . '_sell_bank'] ?? 0;

                    // محاسبه معکوس نرخ USD به target برای به دست آوردن target به USD
                    if ($targetRateBuyCash > 0) {
                        $reverse[$other . '_buy_cash'] = 1 / $targetRateBuyCash;
                    }
                    if ($targetRateSellCash > 0) {
                        $reverse[$other . '_sell_cash'] = 1 / $targetRateSellCash;
                    }
                    if ($targetRateBuyBank > 0) {
                        $reverse[$other . '_buy_bank'] = 1 / $targetRateBuyBank;
                    }
                    if ($targetRateSellBank > 0) {
                        $reverse[$other . '_sell_bank'] = 1 / $targetRateSellBank;
                    }
                } else {
                    // محاسبه نرخ ارز target به other از طریق دلار
                    // فرمول: (نرخ دلار به other) ÷ (نرخ دلار به target)
                    $otherRateBuyCash = $baseRecord[$other . '_buy_cash'] ?? 0;
                    $otherRateSellCash = $baseRecord[$other . '_sell_cash'] ?? 0;
                    $otherRateBuyBank = $baseRecord[$other . '_buy_bank'] ?? 0;
                    $otherRateSellBank = $baseRecord[$other . '_sell_bank'] ?? 0;
                    
                    $targetRateBuyCash = $baseRecord[$targetCurrency . '_buy_cash'] ?? 0;
                    $targetRateSellCash = $baseRecord[$targetCurrency . '_sell_cash'] ?? 0;
                    $targetRateBuyBank = $baseRecord[$targetCurrency . '_buy_bank'] ?? 0;
                    $targetRateSellBank = $baseRecord[$targetCurrency . '_sell_bank'] ?? 0;

                    // محاسبه برای هر نوع نرخ به صورت جداگانه
                    if ($otherRateBuyCash > 0 && $targetRateBuyCash > 0) {
                        $reverse[$other . '_buy_cash'] = $otherRateBuyCash / $targetRateBuyCash;
                    }
                    if ($otherRateSellCash > 0 && $targetRateSellCash > 0) {
                        $reverse[$other . '_sell_cash'] = $otherRateSellCash / $targetRateSellCash;
                    }
                    if ($otherRateBuyBank > 0 && $targetRateBuyBank > 0) {
                        $reverse[$other . '_buy_bank'] = $otherRateBuyBank / $targetRateBuyBank;
                    }
                    if ($otherRateSellBank > 0 && $targetRateSellBank > 0) {
                        $reverse[$other . '_sell_bank'] = $otherRateSellBank / $targetRateSellBank;
                    }
                }
            }

            // ذخیره یا بروزرسانی رکورد
            try {
                ProfitRate::updateOrCreate(
                    [
                        'source_currency' => $targetCurrency,
                        'admin_id' => $baseRecord['admin_id'],
                        'created_at' => now()->format('Y-m-d')
                    ],
                    $reverse
                );
            } catch (\Exception $e) {
                Log::error("خطا در تولید نرخ معکوس برای {$targetCurrency}: {$e->getMessage()}");
            }
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
                'Shabnam' => ['R' => 'Shabnam-FD.ttf',],
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

    private function resetForm()
    {
        $this->source_currency = 'usd';
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->initializeFormData();
        $this->editingId = null;
        $this->isEditing = false;
        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $user = Auth::guard('sarafi')->user();

        $query = ProfitRate::with(['user', 'admin'])
            ->orderBy('created_at', 'desc');

        if ($user->admin_id === null) {
            $query->where(function ($q) use ($user) {
                $q->where('admin_id', $user->id)
                  ->orWhere('user_id', $user->id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        $records = $query->take(10)->get();

        return view('livewire.sarafi.profit-rates', compact('records'));
    }
}