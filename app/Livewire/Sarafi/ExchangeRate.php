<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use App\Models\Sarafi\ExchangeRates;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;
use NumberFormatter;


class ExchangeRate extends Component
{
    public $date;
public $source_currency = 'دالر';
    public $formData = [];
    public $currencies = [];

    public $editingId = null;
    public $isEditing = false;

    public $confirmDeleteId = null;
    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        try {
            $exchangeRate = ExchangeRates::find($this->confirmDeleteId);
            if ($exchangeRate) {
                $exchangeRate->delete();
                session()->flash('message', 'نرخ ارز با موفقیت حذف شد.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در حذف اطلاعات: ' . $e->getMessage());
        }

        $this->confirmDeleteId = null;
    }


    protected $rules = [
        'source_currency' => 'required|string',
        'date' => 'required|string',
        'formData.افغانی.buy' => 'required|numeric|min:0',
        'formData.افغانی.sell' => 'required|numeric|min:0',
        'formData.تومان.buy' => 'required|numeric|min:0',
        'formData.تومان.sell' => 'required|numeric|min:0',
        'formData.یورو.buy' => 'required|numeric|min:0',
        'formData.یورو.sell' => 'required|numeric|min:0',
        'formData.کلدار.buy' => 'required|numeric|min:0',
        'formData.کلدار.sell' => 'required|numeric|min:0',
        'formData.درهم.buy' => 'required|numeric|min:0',
        'formData.درهم.sell' => 'required|numeric|min:0',
        'formData.لیره.buy' => 'required|numeric|min:0',
        'formData.لیره.sell' => 'required|numeric|min:0',
        'formData.یوان چین.buy' => 'required|numeric|min:0',
        'formData.یوان چین.sell' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'formData.*.buy.required' => 'فیلد قیمت خرید نمی‌تواند خالی باشد.',
        'formData.*.sell.required' => 'فیلد قیمت فروش نمی‌تواند خالی باشد.',
        'formData.*.buy.numeric' => 'قیمت خرید باید عددی باشد.',
        'formData.*.sell.numeric' => 'قیمت فروش باید عددی باشد.',
        'formData.*.buy.min' => 'قیمت خرید نمی‌تواند منفی باشد.',
        'formData.*.sell.min' => 'قیمت فروش نمی‌تواند منفی باشد.',
    ];

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->initializeFormData();

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

    private function initializeFormData()
    {
        $currencies = ['افغانی', 'تومان', 'یورو', 'کلدار', 'درهم', 'لیره', 'یوان چین'];

        foreach ($currencies as $currency) {
            $this->formData[$currency] = [
                'buy' => '',
                'sell' => ''
            ];
        }
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
                'afn_buy' => $this->formData['افغانی']['buy'] ?: null,
                'afn_sell' => $this->formData['افغانی']['sell'] ?: null,
                'irr_buy' => $this->formData['تومان']['buy'] ?: null,
                'irr_sell' => $this->formData['تومان']['sell'] ?: null,
                'eur_buy' => $this->formData['یورو']['buy'] ?: null,
                'eur_sell' => $this->formData['یورو']['sell'] ?: null,
                'pkr_buy' => $this->formData['کلدار']['buy'] ?: null,
                'pkr_sell' => $this->formData['کلدار']['sell'] ?: null,
                'aed_buy' => $this->formData['درهم']['buy'] ?: null,
                'aed_sell' => $this->formData['درهم']['sell'] ?: null,
                'try_buy' => $this->formData['لیره']['buy'] ?: null,
                'try_sell' => $this->formData['لیره']['sell'] ?: null,
                'cny_buy' => $this->formData['یوان چین']['buy'] ?: null,
                'cny_sell' => $this->formData['یوان چین']['sell'] ?: null,
            ];

            if ($this->isEditing && $this->editingId) {
                $exchangeRate = ExchangeRates::find($this->editingId);
                if ($exchangeRate) {
                    $exchangeRate->update($data);
                    session()->flash('message', 'نرخ ارز با موفقیت بروزرسانی شد.');
                }
            } else {
                ExchangeRates::create($data);
                session()->flash('message', 'نرخ ارز با موفقیت ثبت شد.');
            }

            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در ثبت اطلاعات: ' . $e->getMessage());
        }
    }

    public function print($exchangeRateId)
    {
        $exchangeRate = ExchangeRates::with(['user', 'admin'])->findOrFail($exchangeRateId);

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
            'tempDir' => storage_path('app/mpdf/tmp'),

        ]);

        $mpdf->SetAutoPageBreak(false);

        $html = view('pdf.Sarafi.exchange-rate', compact('exchangeRate'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'نرخ_ارز_' . \Morilog\Jalali\Jalalian::fromDateTime($exchangeRate->created_at)->format('Y_m_d') . '.pdf';

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
        $this->source_currency = 'دالر';
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->editingId = null;
        $this->isEditing = false;
    }

    public function edit($id)
    {
        $exchangeRate = ExchangeRates::find($id);

        if ($exchangeRate) {
            $this->editingId = $id;
            $this->isEditing = true;
            $this->source_currency = $exchangeRate->source_currency;

            $this->date = Jalalian::fromDateTime($exchangeRate->created_at)->format('Y/m/d');

            $this->formData = [
                'افغانی' => [
                    'buy' => $exchangeRate->afn_buy,
                    'sell' => $exchangeRate->afn_sell
                ],
                'تومان' => [
                    'buy' => $exchangeRate->irr_buy,
                    'sell' => $exchangeRate->irr_sell
                ],
                'یورو' => [
                    'buy' => $exchangeRate->eur_buy,
                    'sell' => $exchangeRate->eur_sell
                ],
                'کلدار' => [
                    'buy' => $exchangeRate->pkr_buy,
                    'sell' => $exchangeRate->pkr_sell
                ],
                'درهم' => [
                    'buy' => $exchangeRate->aed_buy,
                    'sell' => $exchangeRate->aed_sell
                ],
                'لیره' => [
                    'buy' => $exchangeRate->try_buy,
                    'sell' => $exchangeRate->try_sell
                ],
                'یوان چین' => [
                    'buy' => $exchangeRate->cny_buy,
                    'sell' => $exchangeRate->cny_sell
                ],
            ];
        }
    }

    public function delete($id)
    {
        try {
            $exchangeRate = ExchangeRates::find($id);
            if ($exchangeRate) {
                $exchangeRate->delete();
                session()->flash('message', 'نرخ ارز با موفقیت حذف شد.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در حذف اطلاعات: ' . $e->getMessage());
        }
    }



  public function render()
    {
        $records = ExchangeRates::with(['user', 'admin'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.sarafi.exchange-rate', [
            'records' => $records
        ]);
    }
}

   