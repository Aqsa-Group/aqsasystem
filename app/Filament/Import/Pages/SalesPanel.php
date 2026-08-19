<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Customer;
use App\Models\Import\CustomerBalance;
use App\Models\Import\CustomerStory;
use App\Models\Import\Loan;
use App\Models\Import\Safe;
use App\Models\Import\Sale;
use App\Models\Import\SaleItem;
use App\Models\Import\Warehouse;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class SalesPanel extends Page
{
    protected static string $view = 'filament.pages.sales-panel';
    protected static ?string $navigationIcon = 'simpleline-basket';
    protected static ?string $navigationGroup = 'بخش خرید و فروش';
    protected static ?string $navigationLabel = 'فروشات';
    protected static ?string $title = null;
    protected static ?int $navigationSort = 2;
    protected static ?string $route = '/sales-panel';
    public string $selectedUnit = '';

    public function getTitle(): string|Htmlable
    {
        return '';
    }

    // state
    public string $saleType = 'retail';
    public bool $showOverlayForm = true;
    public  $gradient;


    public string $barcode = '';
    public string $name = '';
    public int $quantity = 1;
    public float $price = 0.000;
    public float $total = 0.000;
    public ?int $customer_id = null;

    public array $items = [];
    public ?Sale $lastSale = null;

    public string $searchName = '';
    public array $suggestions = [];

    public string $buyerName = '';
    public string $receivedAmount = '0';
    public float $discount = 0.000;

    public bool $productError = false;

    public ?string $receivedCurrency = null;
    public ?float $usdToAfnRate = 0.000;
    public float $convertedReceivedAmount = 0;

    public function getConvertedReceivedAmountProperty(): float
    {
        if ($this->saleType === 'retail') {
            return collect($this->items)->sum('total') - $this->discount;
        }

        if ($this->receivedCurrency === 'AFN' && $this->usdToAfnRate > 0) {
            return $this->receivedAmount / $this->usdToAfnRate;
        }

        return $this->receivedAmount;
    }
    private function roundAmount($value): float
    {
        return round((float) $value, 3);
    }

    public function switchToRetail(): void
    {
        $this->saleType = 'retail';
        $this->showOverlayForm = true;
        $this->customer_id = null;

        foreach ($this->items as $index => $item) {
            $product = Warehouse::where('barcode', $item['barcode'])->first();
            if ($product) {
                $price = $this->roundAmount($product->retail_price);
                $this->items[$index]['price'] = $price;
                $this->items[$index]['total'] = $this->roundAmount($this->items[$index]['quantity'] * $price);
            }
        }
    }

    public function switchToWholesale(): void
    {
        $this->saleType = 'wholesale';
        $this->showOverlayForm = true;

        foreach ($this->items as $index => $item) {
            $product = Warehouse::where('barcode', $item['barcode'])->first();
            if ($product) {
                $price = $this->roundAmount($product->big_whole_price);
                $this->items[$index]['price'] = $price;
                $this->items[$index]['total'] = $this->roundAmount($this->items[$index]['quantity'] * $price);
            }
        }
    }

    public function updateItemPrice(int $index): void
    {
        if (!isset($this->items[$index])) return;

        $quantity = $this->items[$index]['quantity'] ?? 0;
        $price = $this->items[$index]['price'] ?? 0;

        $price = is_numeric($price) ? (float) $price : 0;

        $this->items[$index]['total'] = $this->roundAmount($quantity * $price);
    }


    public function updatedBarcode(): void
    {
        $this->barcode = $this->convertPersianToEnglish($this->barcode);
        $product = Warehouse::where('barcode', $this->barcode)->first();

        if ($product) {
            if ($this->saleType === 'wholesale' && $product->quantity <= 0 && $product->unit !== 'دانه') {
                Notification::make()->title("⚠️ محصول «{$product->name}» موجودی ندارد!")->danger()->send();
                $this->resetForm();
                $this->productError = true;
                return;
            }

            if ($this->saleType === 'retail' && $product->all_exist_number <= 0) {
                Notification::make()->title("⚠️ محصول «{$product->name}» موجودی ندارد!")->danger()->send();
                $this->resetForm();
                $this->productError = true;
                return;
            }

            $this->productError = false;
            $this->name = $product->name;
            $this->selectedUnit = $product->unit;
            $this->price = $this->roundAmount($this->saleType === 'retail' ? $product->retail_price : $product->big_whole_price);
            $this->quantity = 1;
            $this->calculateTotal();
        }
    }

    public function updatedSearchName()
    {
        if (strlen($this->searchName) > 1) {
            $this->suggestions = Warehouse::where('name', 'like', '%' . $this->searchName . '%')
                ->limit(5)
                ->get()
                ->toArray();
        } else {
            $this->suggestions = [];
        }
    }

    public function selectProduct($id)
    {
        $product = Warehouse::find($id);
        if ($product) {
            if ($this->saleType === 'wholesale' && $product->quantity <= 0 && $product->unit !== 'دانه') {
                Notification::make()->title("⚠️ محصول «{$product->name}» موجودی ندارد!")->danger()->send();
                $this->resetForm();
                $this->productError = true;
                return;
            }

            if ($this->saleType === 'retail' && $product->all_exist_number <= 0) {
                Notification::make()->title("⚠️ محصول «{$product->name}» موجودی ندارد!")->danger()->send();
                $this->resetForm();
                $this->productError = true;
                return;
            }

            $this->productError = false;
            $this->barcode = $product->barcode;
            $this->name = $product->name;
            $this->selectedUnit = $product->unit;
            $this->price = $this->roundAmount($this->saleType === 'retail' ? $product->retail_price : $product->big_whole_price);
            $this->quantity = 1;
            $this->calculateTotal();
        }
        $this->searchName = '';
        $this->suggestions = [];
    }

    public function updatedQuantity(): void
    {
        $this->calculateTotal();
    }

    public function calculateTotal(): void
    {
        $this->total = $this->roundAmount($this->quantity * $this->price);
    }

    public function submitForm(): void
    {
        if ($this->productError) {
            $this->productError = false;
            return;
        }

        if (empty($this->name)) {
            Notification::make()->title('محصول انتخاب نشده است!')->danger()->send();
            return;
        }

        $product = Warehouse::where('barcode', $this->barcode)->first();

        $existingKey = collect($this->items)->search(fn($item) => $item['barcode'] === $this->barcode && $this->barcode !== '');

        if ($existingKey !== false) {
            $this->items[$existingKey]['quantity'] += $this->quantity;

            $product = Warehouse::where('barcode', $this->barcode)->first();
            if ($product) {
                $price = $this->roundAmount($this->saleType === 'retail' ? $product->retail_price : $product->big_whole_price);
                $this->items[$existingKey]['price'] = $price;
                $this->items[$existingKey]['total'] = $this->roundAmount($this->items[$existingKey]['quantity'] * $price);
            }
        } else {
            $this->items[] = [
                'name' => $this->name,
                'barcode' => $this->barcode,
                'quantity' => $this->quantity,
                'unit' => $this->selectedUnit ?: ($product?->unit ?? '-'),
                'price' => $this->roundAmount($this->price),
                'total' => $this->roundAmount($this->total),
            ];
        }

        $this->resetForm();
        $this->searchName = '';
        $this->suggestions = [];
    }

    public function finalizeAndPrintInvoice(): void
    {
        // اول ثبت
        $this->finalizeInvoice();

        // بعد چاپ
        $this->printInvoice();
    }

    public function finalizeInvoice(): void
    {
        if ($this->saleType === 'wholesale' && empty($this->customer_id)) {
            Notification::make()->title('⚠️ لطفاً خریدار را انتخاب کنید!')->warning()->send();
            return;
        }

        if (empty($this->items)) {
            Notification::make()->title('⚠️ کالایی برای ثبت وجود ندارد!')->warning()->send();
            return;
        }

        DB::transaction(function () {

            $totalPrice = $this->roundAmount(collect($this->items)->sum('total'));
            $finalPrice = $this->roundAmount(max(0, $totalPrice - $this->discount));

            $convertedReceived = $this->saleType === 'retail'
                ? $finalPrice
                : ($this->receivedCurrency === 'AFN'
                    ? $this->roundAmount($this->receivedAmount / max(0.0001, $this->usdToAfnRate))
                    : $this->roundAmount($this->receivedAmount)
                );

            // ایجاد فاکتور
            $sale = new Sale();
            $sale->sale_type = $this->saleType;
            $sale->total_price = $finalPrice;
            $sale->discount = $this->roundAmount($this->discount);
            $sale->customer_id = $this->customer_id;
            $sale->buyer_name = $this->saleType === 'retail'
                ? ($this->buyerName ?: 'خریدار نقدی')
                : optional(Customer::find($this->customer_id))->name;
            $sale->received_amount = $convertedReceived;
            $sale->remaining_amount = $this->saleType === 'retail'
                ? 0.000
                : $this->roundAmount(max(0, $finalPrice - $convertedReceived));
            $sale->user_id = Auth::id();

            // ✅ محاسبه قرض قبلی از CustomerBalance
            if ($this->saleType === 'wholesale' && $this->customer_id) {
                $balance = CustomerBalance::where('customer_id', $this->customer_id)->first();

                if ($balance) {
                    // |usd| = کل بدهی قبل از این فاکتور
                    $sale->previous_loan = abs($balance->usd);
                } else {
                    $sale->previous_loan = 0.000;
                }
            } else {
                $sale->previous_loan = 0.000;
            }

            $sale->save();
            $sale->invoice_number = $sale->id;
            $sale->save();
            $this->lastSale = $sale;

            // بروزرسانی صندوق
            $safe = Safe::firstOrCreate([], [
                'USD'         => 0,
                'AFN'         => 0,
                'today'       => 0,
                'user_id'     => Auth::id(),
                'last_update' => now()->toDateString(),
            ]);

            if ($safe->last_update !== now()->toDateString()) {
                $safe->today = 0;
                $safe->last_update = now()->toDateString();
            }

            if ($this->saleType === 'retail') {
                if ($this->receivedCurrency === 'AFN') {
                    $afnAmount = $this->roundAmount($finalPrice * $this->usdToAfnRate);
                    $safe->AFN = $this->roundAmount($safe->AFN + $afnAmount);
                } else {
                    $safe->USD = $this->roundAmount($safe->USD + $finalPrice);
                }
            } else {
                if ($this->receivedCurrency === 'AFN') {
                    $safe->AFN = $this->roundAmount($safe->AFN + $this->receivedAmount);
                } else {
                    $safe->USD = $this->roundAmount($safe->USD + $this->receivedAmount);
                }
            }

            $safe->today = $this->roundAmount($safe->today + $finalPrice);
            $safe->save();

            // پردازش آیتم‌ها و کاهش موجودی
            foreach ($this->items as $item) {
                $warehouse = Warehouse::where('barcode', $item['barcode'])->first();
                if (!$warehouse) continue;

                if ($warehouse->unit === 'دانه') {
                    $warehouse->all_exist_number -= $item['quantity'];
                    $warehouse->all_exist_number = max(0, $warehouse->all_exist_number);
                } else {
                    if ($this->saleType === 'wholesale') {
                        $warehouse->quantity -= $item['quantity'];
                        $warehouse->all_exist_number -= ($item['quantity'] * $warehouse->big_quantity);
                        $warehouse->quantity = max(0, $warehouse->quantity);
                        $warehouse->all_exist_number = max(0, $warehouse->all_exist_number);
                    } else {
                        $warehouse->all_exist_number -= $item['quantity'];
                        $warehouse->all_exist_number = max(0, $warehouse->all_exist_number);

                        if ($warehouse->big_quantity > 0) {
                            $cartonsToReduce = intdiv($item['quantity'], $warehouse->big_quantity);
                            if ($cartonsToReduce > 0) {
                                $warehouse->quantity = max(0, $warehouse->quantity - $cartonsToReduce);
                            }
                        }

                        if (
                            $warehouse->all_exist_number < $warehouse->big_quantity &&
                            $warehouse->all_exist_number > 0 &&
                            $warehouse->quantity > 0
                        ) {
                            $warehouse->quantity = 0;
                        }
                    }
                }

                $unitPrice = $this->roundAmount($item['price']);
                $totalSale = $this->roundAmount($item['quantity'] * $unitPrice);
                $totalCost = $this->roundAmount($item['quantity'] * $warehouse->price);

                $discountShare = 0.000;
                if ($sale->discount > 0 && $totalPrice > 0) {
                    $discountShare = $this->roundAmount(($totalSale / $totalPrice) * $sale->discount);
                }

                $totalProfit = $this->roundAmount(($totalSale - $discountShare) - $totalCost);
                $profit = $totalProfit > 0 ? $totalProfit : 0.000;
                $loss = $totalProfit < 0 ? abs($totalProfit) : 0.000;

                $warehouse->save();

                SaleItem::create([
                    'sale_id'        => $sale->id,
                    'warehouse_id'   => $warehouse->id,
                    'quantity'       => $item['quantity'],
                    'unit'           => $item['unit'],
                    'price_per_unit' => $unitPrice,
                    'total_price'    => $totalSale,
                    'profit'         => $profit,
                    'loss'           => $loss,
                    'user_id'        => Auth::id(),
                ]);
            }

            // ایجاد قرض و آپدیت CustomerBalance/CustomerStory فقط برای فروش عمده
            if ($this->saleType === 'wholesale') {
                $calculatedLoanAmount = $this->roundAmount(max(0, $finalPrice - $convertedReceived));

                if ($calculatedLoanAmount > 0) {
                    CustomerStory::create([
                        'customer_id' => $this->customer_id,
                        'type'        => 'برد',
                        'amount'      => $calculatedLoanAmount,
                        'currency'    => 'USD',
                        'date'        => now(),
                        'description' => "قرض بابت فاکتور شماره {$sale->invoice_number}",
                        'user_id'     => Auth::id(),
                        'admin_id'    => Auth::id(),
                        'sale_id'     => $sale->id,
                    ]);

                    $balance = CustomerBalance::firstOrCreate(
                        ['customer_id' => $this->customer_id],
                        [
                            'afn'      => 0.000,
                            'usd'      => 0.000,
                            'pkr'      => 0.000,
                            'eur'      => 0.000,
                            'user_id'  => Auth::id(),
                            'admin_id' => Auth::id(),
                        ]
                    );

                    if (!$balance->admin_id) {
                        $balance->admin_id = Auth::id();
                    }

                    $balance->usd = $this->roundAmount($balance->usd - $calculatedLoanAmount);
                    $balance->user_id = Auth::id();
                    $balance->save();
                }
            }
        });

        Notification::make()->title('فاکتور با موفقیت ثبت شد!')->success()->send();
    }

    public function printInvoice(): void
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');
        if ($this->saleType === 'wholesale' && empty($this->customer_id)) {
            Notification::make()->title('لطفاً خریدار را انتخاب کنید!')->warning()->send();
            return;
        }

        if (!$this->lastSale) {
            Notification::make()->title(' ابتدا فاکتور را ثبت کنید!')->warning()->send();
            return;
        }

        $sale = $this->lastSale->load('items.warehouse');

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 2,
            'margin_left' => 10,
            'margin_right' => 10,
            'memory_limit' => '1024M',
            'fontDir' => array_merge(
                (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                [public_path('fonts/vazir/')]
            ),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'vazir' => [
                    'R' => 'Vazir-Light.ttf',
                    'B' => 'Vazir-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'vazir',
            'tempDir' => storage_path('app/mpdf'),
        ]);
        $mpdf->SetDirectionality('rtl');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;

        $css = file_get_contents(resource_path('views/pdf/invoice.css'));
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

        $discount = $this->roundAmount($sale->discount ?? 0);
        $finalPrice = $this->roundAmount(max(0, $sale->total_price));

        foreach ($sale->items as $si) {
            $si->price_per_unit = $this->roundAmount($si->price_per_unit ?? ($si->warehouse->retail_price ?? 0));
            $si->total_price = $this->roundAmount($si->total_price ?? 0);
            $si->profit = $this->roundAmount($si->profit ?? 0);
            $si->loss = $this->roundAmount($si->loss ?? 0);
        }

        // ========== تغییر: خواندن قرض قبلی از CustomerBalance ==========
        $previousLoanRemaining = 0;

        if ($sale->sale_type === 'wholesale' && $sale->customer_id) {
            $balance = CustomerBalance::where('customer_id', $sale->customer_id)->first();

            if ($balance) {
                $currentBalance = abs($balance->usd); // قدر مطلق چون منفی هست
                $previousLoanRemaining = $this->roundAmount($sale->previous_loan ?? 0);
            }
        }
        // ================================================================

        $html = view('pdf.invoice', [
            'sale'       => $sale,
            'discount'   => $discount,
            'finalPrice' => $finalPrice,
            'previousLoanRemaining' => $previousLoanRemaining,
        ])->render();

        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        $fileName = 'invoice-' . now()->timestamp . '.pdf';
        $mpdf->Output(storage_path('app/public/' . $fileName), \Mpdf\Output\Destination::FILE);

        \App\Models\Import\Document::create([
            'sale_id'        => $sale->id,
            'invoice_number' => $sale->invoice_number,
            'buyer_name'     => $this->saleType === 'wholesale' ? $sale->buyer_name : null,
            'total_amount'   => $finalPrice,
            'discount'       => $discount,
            'paid_amount'    => $this->saleType === 'wholesale' ? $this->roundAmount($sale->received_amount) : null,
            'sale_type'      => $this->saleType,
            'file_path'      => 'storage/' . $fileName,
        ]);

        $this->items = [];
        $this->lastSale = null;
        $this->buyerName = '';
        $this->receivedAmount = 0.000;
        $this->customer_id = null;
        $this->saleType = 'retail';
        $this->discount = 0.000;
        $this->showOverlayForm = true;
        $this->resetForm();

        Notification::make()->title('🖨️ فاکتور آماده چاپ شد!')->success()->send();
        $this->dispatch('download-invoice', url: asset('storage/' . $fileName));
    }
    private function resetForm(): void
    {
        $this->barcode = '';
        $this->name = '';
        $this->quantity = 1;
        $this->price = 0.000;
        $this->total = 0.000;
        $this->selectedUnit = '';
        $this->productError = false;
    }

    public function increaseQuantity(int $index): void
    {
        if (!$this->hasItem($index)) {
            return;
        }

        $item = $this->items[$index];

        $warehouse = Warehouse::where('barcode', $item['barcode'])->first();

        if (!$warehouse) {
            return;
        }

        $newQuantity = $this->items[$index]['quantity'] + 1;

        // اگر واحد دانه باشد
        if (($item['unit'] ?? $warehouse->unit) === 'دانه') {

            if ($newQuantity > $warehouse->all_exist_number) {

                Notification::make()
                    ->title("موجودی کافی نیست!")
                    ->danger()
                    ->send();

                return;
            }
        } else {

            // فروش عمده
            if ($this->saleType === 'wholesale') {

                if ($newQuantity > $warehouse->quantity) {

                    Notification::make()
                        ->title("موجودی کارتُن/بسته کافی نیست!")
                        ->danger()
                        ->send();

                    return;
                }
            } else {

                // فروش پرچون
                if ($newQuantity > $warehouse->all_exist_number) {

                    Notification::make()
                        ->title("موجودی کافی نیست!")
                        ->danger()
                        ->send();

                    return;
                }
            }
        }

        $this->items[$index]['quantity'] = $newQuantity;

        $this->updateItemTotal($index);
    }

    public function decreaseQuantity(int $index): void
    {
        if (!$this->hasItem($index)) {
            return;
        }

        if ($this->items[$index]['quantity'] <= 1) {
            return;
        }

        $this->items[$index]['quantity']--;

        $this->updateItemTotal($index);
    }

    public function updatedItems($value, $key): void
    {
        if (!str_contains($key, 'quantity')) {
            return;
        }

        $parts = explode('.', $key);
        $index = (int) ($parts[0] ?? -1);

        if (!isset($this->items[$index])) {
            return;
        }

        /*
     * وقتی کاربر 1 را پاک می‌کند،
     * فعلاً هیچ کاری نکن.
     *
     * اجازه بده کاربر 22 را کامل تایپ کند.
     */
        if ($value === '' || $value === null) {
            return;
        }

        $warehouse = Warehouse::where(
            'barcode',
            $this->items[$index]['barcode']
        )->first();

        if (!$warehouse) {
            return;
        }

        $qty = (int) $value;

        /*
     * اگر مقدار کمتر از 1 بود،
     * فقط در این مرحله اصلاحش کن.
     */
        if ($qty < 1) {
            $qty = 1;
        }

        // حداکثر موجودی
        if (($this->items[$index]['unit'] ?? $warehouse->unit) === 'دانه') {

            $maxQty = (int) $warehouse->all_exist_number;
        } else {

            $maxQty = $this->saleType === 'wholesale'
                ? (int) $warehouse->quantity
                : (int) $warehouse->all_exist_number;
        }

        // اگر موجودی کافی نیست
        if ($maxQty <= 0) {

            $this->items[$index]['quantity'] = 1;

            Notification::make()
                ->title('موجودی کافی نیست!')
                ->danger()
                ->send();

            return;
        }

        // بیشتر از موجودی
        if ($qty > $maxQty) {

            $qty = $maxQty;

            Notification::make()
                ->title("حداکثر موجودی: {$maxQty}")
                ->warning()
                ->send();
        }

        // ذخیره تعداد
        $this->items[$index]['quantity'] = $qty;

        // محاسبه فوری قیمت
        $this->updateItemTotal($index);
    }
    /**
     * بررسی وجود آیتم
     */
    protected function hasItem(int $index): bool
    {
        return isset($this->items[$index]);
    }

    /**
     * محاسبه total هر آیتم
     */
    protected function updateItemTotal(int $index): void
    {
        $item = $this->items[$index];

        $this->items[$index]['total'] = $this->roundAmount(
            $item['quantity'] * $item['price']
        );
    }


    public function removeItem(int $index): void
    {
        if (!isset($this->items[$index])) return;
        array_splice($this->items, $index, 1);
    }

    private function convertPersianToEnglish(string $input): string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($persian, $english, $input);
    }

    public function getLiveRemainingAmountProperty(): float
    {
        if ($this->saleType === 'retail') {
            return 0.000;
        }

        $total = $this->roundAmount(collect($this->items)->sum('total'));
        $finalPrice = max(0, $total - $this->discount);

        // محاسبه دریافتی تبدیل شده
        $convertedReceived = 0;
        if ($this->receivedCurrency === 'AFN' && $this->usdToAfnRate > 0) {
            $convertedReceived = $this->roundAmount($this->receivedAmount / $this->usdToAfnRate);
        } else {
            $convertedReceived = $this->roundAmount($this->receivedAmount);
        }

        return $this->roundAmount(max(0, $finalPrice - $convertedReceived));
    }
}
