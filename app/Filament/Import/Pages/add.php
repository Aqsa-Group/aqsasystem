<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Safe;
use App\Models\Import\AddSafe;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class Add extends Page
{
    protected static ?string $navigationIcon = 'mdi-safe';
    protected static ?string $navigationLabel = 'افزودن به صندوق';
    protected static ?string $navigationGroup = 'حسابداری';
    protected static ?string $title = '';
    protected static ?int $navigationSort = 9;
    protected static string $view = 'filament.import.pages.addsafe';

    public $addAmount;
    public $currency;
    public $note;

    protected function rules(): array
    {
        return [
            'addAmount' => ['required', 'numeric', 'min:0.01'],
            'currency'  => ['required', 'in:AFN,USD'],
            'note'      => ['nullable', 'string'],
        ];
    }

    public function addToSafe()
    {
        $this->validate();

        DB::transaction(function () {

            // ثبت در جدول AddSafe
            AddSafe::create([
                'amount'      => $this->addAmount,
                'currency'    => $this->currency,
                'description' => $this->note,
            ]);

            // پیدا کردن اولین رکورد Safe
            $safe = Safe::first();

            // اگر موجود نیست، ایجاد رکورد اولیه
            if (! $safe) {
                $safe = Safe::create([
                    'USD' => 0,
                    'AFN' => 0,
                ]);
            }

            // بروزرسانی موجودی
            if ($this->currency === 'AFN') {
                $safe->increment('AFN', $this->addAmount);
            } elseif ($this->currency === 'USD') {
                $safe->increment('USD', $this->addAmount);
            }
        });

        // پاک کردن فرم بعد از ثبت
        $this->reset(['addAmount', 'currency', 'note']);

        // نمایش پیام موفقیت
        Notification::make()
            ->title('مبلغ به صندوق اضافه شد')
            ->success()
            ->send();
    }


    public function mount()
{
    $this->currency = 'AFN'; // مقدار پیش‌فرض افغانی
}

}
