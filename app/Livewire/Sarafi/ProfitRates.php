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
        // reset formData to all currencies except source
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

            // EDIT MODE
            if ($this->isEditing && $this->editingId) {
                $profitRate = ProfitRate::find($this->editingId);

                if ($profitRate) {
                    $profitRate->update($data);

                    // ✳ فقط اگر USD ویرایش شد → کل سیستم را بازسازی کن
                    if ($this->source_currency === 'usd') {
                        $this->generateAllReverseRates($data);
                    }

                    session()->flash('message', 'نرخ ارز با موفقیت بروزرسانی شد.');
                }
            }

            // CREATE NEW
            else {
                $created = ProfitRate::create($data);
                session()->flash('message', 'نرخ ارز با موفقیت ثبت شد.');

                // ✳ فقط اگر USD ساخته شد → بقیه ارزها تولید شود
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
     * این تابع از رکورد پایه (مثلاً source_currency = usd) استفاده می‌کند
     * و برای هر ارز دیگر رکوردی می‌سازد که نرخ‌ها نسبت به آن ارز نوشته شده باشد.
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

        // Initialize all rates to 0
        foreach ($this->currencyCodes as $other) {
            $reverse[$other . '_buy_cash'] = 0;
            $reverse[$other . '_buy_bank'] = 0;
            $reverse[$other . '_sell_cash'] = 0;
            $reverse[$other . '_sell_bank'] = 0;
        }

        // Set self rate to 0 for same currency
        $reverse[$targetCurrency . '_buy_cash'] = 0;
        $reverse[$targetCurrency . '_buy_bank'] = 0;
        $reverse[$targetCurrency . '_sell_cash'] = 0;
        $reverse[$targetCurrency . '_sell_bank'] = 0;

        foreach ($this->currencyCodes as $other) {
            if ($other === $targetCurrency) continue;

            // حالت خاص AFN ↔ IRR - نرخ یکسان در هر دو جهت
            if (($targetCurrency === 'afn' && $other === 'irr') ||
                ($targetCurrency === 'irr' && $other === 'afn')
            ) {
                // محاسبه نرخ تومان به افغانی از baseRecord
                $afnRateBuyCash = $baseRecord['afn_buy_cash'] ?? 0;
                $irrRateBuyCash = $baseRecord['irr_buy_cash'] ?? 0;
                
                // نرخ تومان به افغانی = (نرخ دلار به افغانی) / (نرخ دلار به تومان)
                $rate = 0;
                if ($afnRateBuyCash > 0 && $irrRateBuyCash > 0) {
                    $rate = $afnRateBuyCash / $irrRateBuyCash;
                }
                
                // اعمال ضریب 1000
                $rate = $rate > 0 ? 1000 * $rate : 0;
                
                // برای تمام فیلدها نرخ یکسان
                $reverse[$other . '_buy_cash'] = $rate;
                $reverse[$other . '_sell_cash'] = $rate;
                $reverse[$other . '_buy_bank'] = $rate;
                $reverse[$other . '_sell_bank'] = $rate;
                
                continue;
            }

            // محاسبه نرمال برای سایر جفت ارزها
            if ($other === $baseCurrencyCode) {
                // اگر other همان ارز پایه است (USD)
                $targetRateBuyCash = $baseRecord[$targetCurrency . '_buy_cash'] ?? 0;
                $targetRateSellCash = $baseRecord[$targetCurrency . '_sell_cash'] ?? 0;
                $targetRateBuyBank = $baseRecord[$targetCurrency . '_buy_bank'] ?? 0;
                $targetRateSellBank = $baseRecord[$targetCurrency . '_sell_bank'] ?? 0;

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
                // محاسبه نرخ other به target از طریق base
                // فرمول: (نرخ other به USD) ÷ (نرخ target به USD)
                $otherRateBuyCash = $baseRecord[$other . '_buy_cash'] ?? 0;
                $otherRateSellCash = $baseRecord[$other . '_sell_cash'] ?? 0;
                $otherRateBuyBank = $baseRecord[$other . '_buy_bank'] ?? 0;
                $otherRateSellBank = $baseRecord[$other . '_sell_bank'] ?? 0;
                
                $targetRateBuyCash = $baseRecord[$targetCurrency . '_buy_cash'] ?? 0;
                $targetRateSellCash = $baseRecord[$targetCurrency . '_sell_cash'] ?? 0;
                $targetRateBuyBank = $baseRecord[$targetCurrency . '_buy_bank'] ?? 0;
                $targetRateSellBank = $baseRecord[$targetCurrency . '_sell_bank'] ?? 0;

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

        // ذخیره یا بروزرسانی
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
        $records = ProfitRate::with(['user', 'admin'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.sarafi.profit-rates', [
            'records' => $records
        ]);
    }
}
