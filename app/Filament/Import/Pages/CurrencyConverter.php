<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Import\Inventory;
use App\Models\Import\Warehouse;
use App\Models\Import\SaleItem;
use App\Models\Import\Sale;
use App\Models\Import\Safe;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class CurrencyConverter extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $title = '  تبدیل ارز سیستم';
    protected static ?string $navigationIcon = 'carbon-license-maintenance';
    protected static string $view = 'filament.import.pages.currency-converter';
    protected static ?string $navigationGroup = 'گزارشات و تنظیمات';
    protected static ?int $navigationSort = 12;

    public $currency; 
    public $rate; 
    public $currentCurrency; 

    public function mount()
    {
        $safe = DB::connection('import')->table('safes')->first();

        if ($safe) {
            $this->currentCurrency = $safe->currency == 1 ? 'USD' : 'AFN';
        } else {
            DB::connection('import')->table('safes')->insert([
                'currency' => 0,
            ]);
            $this->currentCurrency = 'AFN';
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('currency')
                ->label('واحد ارز جدید')
                ->options([
                    'AFN' => 'افغانی',
                    'USD' => 'دالر',
                ])
                ->required()
                ->helperText("واحد ارز فعلی: {$this->currentCurrency}"),

            TextInput::make('rate')
                ->label('نرخ هر دالر')
                ->numeric()
                ->required()
                ->default(1),
        ];
    }

    public function convert()
    {
        $this->form->validate();
        $rate = (float)$this->rate;

        if ($rate <= 0) {
            Notification::make()
                ->title('خطا')
                ->danger()
                ->body('نرخ باید بزرگتر از صفر باشد.')
                ->send();
            return;
        }

        $safe = DB::connection('import')->table('safes')->first();
        if (!$safe) {
            Notification::make()
                ->title('خطا')
                ->danger()
                ->body('رکورد safe یافت نشد!')
                ->send();
            return;
        }

        if ($this->currency === $this->currentCurrency) {
            Notification::make()
                ->title('توجه')
                ->warning()
                ->body("واحد ارز هم‌اکنون {$this->currentCurrency} است.")
                ->send();
            return;
        }

        $operation = $this->currency === 'USD' ? 'divide' : 'multiply';

        // تبدیل جداول
        $this->convertInventoryAndWarehouse($operation, $rate);
        $this->convertSalesAndItems($operation, $rate);

        // بروزرسانی currency در جدول safes
        DB::connection('import')->table('safes')->update([
            'currency' => $this->currency === 'USD' ? 1 : 0
        ]);

        // بروزرسانی today در جدول safes
        $this->convertSafeToday($rate, $operation);

        $this->currentCurrency = $this->currency;

        Notification::make()
            ->title('موفقیت')
            ->success()
            ->body('تمام قیمت‌ها با نرخ وارد شده تبدیل شدند.')
            ->send();
    }

    private function convertInventoryAndWarehouse($operation, $rate)
    {
        $fields = ['price', 'big_whole_price', 'retail_price', 'total_price'];

        Inventory::chunk(100, function ($items) use ($fields, $operation, $rate) {
            foreach ($items as $item) {
                $data = [];
                foreach ($fields as $field) {
                    if (!is_null($item->$field)) {
                        $data[$field] = $operation === 'divide'
                            ? $item->$field / $rate
                            : $item->$field * $rate;
                    }
                }
                $item->update($data);
            }
        });

        Warehouse::chunk(100, function ($items) use ($fields, $operation, $rate) {
            foreach ($items as $item) {
                $data = [];
                foreach ($fields as $field) {
                    if (!is_null($item->$field)) {
                        $data[$field] = $operation === 'divide'
                            ? $item->$field / $rate
                            : $item->$field * $rate;
                    }
                }
                $item->update($data);
            }
        });
    }

    private function convertSalesAndItems($operation, $rate)
    {
        $salesFields = ['total_price', 'received_amount', 'remaining_amount', 'discount'];
        $itemsFields = ['profit', 'loss', 'total_price', 'price_per_unit'];

        Sale::chunk(100, function ($sales) use ($salesFields, $operation, $rate) {
            foreach ($sales as $sale) {
                $data = [];
                foreach ($salesFields as $field) {
                    if (!is_null($sale->$field)) {
                        $data[$field] = $operation === 'divide'
                            ? $sale->$field / $rate
                            : $sale->$field * $rate;
                    }
                }
                $sale->update($data);
            }
        });

        SaleItem::chunk(100, function ($items) use ($itemsFields, $operation, $rate) {
            foreach ($items as $item) {
                $data = [];
                foreach ($itemsFields as $field) {
                    if (!is_null($item->$field)) {
                        $data[$field] = $operation === 'divide'
                            ? $item->$field / $rate
                            : $item->$field * $rate;
                    }
                }
                $item->update($data);
            }
        });
    }



    private function convertSafeToday($rate, $operation)
    {
        $safe = Safe::first();
        if ($safe) {
            $newToday = $operation === 'divide'
                ? $safe->today / $rate
                : $safe->today * $rate;

            $safe->update(['today' => $newToday]);
        }
    }
}
