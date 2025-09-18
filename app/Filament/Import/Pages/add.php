<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Safe;
use App\Models\Import\AddSafe; // اضافه شده

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class add extends Page
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
            // استفاده از مدل AddSafe
            AddSafe::create([
                'amount'      => $this->addAmount,
                'currency'    => $this->currency,
                'description' => $this->note,
            ]);

            $safe = Safe::first();

            if (! $safe) {
                $safe = Safe::create([
                    'USD' => 0,
                    'AFN' => 0,
                ]);
            }

            if ($this->currency === 'AFN') {
                $safe->increment('AFN', $this->addAmount);
            } elseif ($this->currency === 'USD') {
                $safe->increment('USD', $this->addAmount);
            }
        });

        $this->addAmount = null;
        $this->currency = null;
        $this->note = null;

        Notification::make()
            ->title('مبلغ به صندوق اضافه شد')
            ->success()
            ->send();
    }
}
