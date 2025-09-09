<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Customer;
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

    public function getTitle(): string|Htmlable
    {
        return '';
    }

    public string $saleType = 'retail';
    public bool $showOverlayForm = true;

    public string $barcode = '';
    public string $name = '';
    public int $quantity = 1;
    public float $price = 0;
    public float $total = 0;
    public ?int $customer_id = null;

    public array $items = [];
    public ?Sale $lastSale = null;

    public string $searchName = '';
    public array $suggestions = [];

    public string $buyerName = '';
    public float $receivedAmount = 0;
    public float $discount = 0;


    public bool $productError = false;

    public function switchToRetail(): void
    {
        $this->saleType = 'retail';
        $this->showOverlayForm = true;
        $this->customer_id = null;

        foreach ($this->items as $index => $item) {
            $product = Warehouse::where('barcode', $item['barcode'])->first();
            if ($product) {
                $this->items[$index]['price'] = $product->retail_price;
                $this->items[$index]['total'] = $this->items[$index]['quantity'] * $product->retail_price;
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
                $this->items[$index]['price'] = $product->big_whole_price;
                $this->items[$index]['total'] = $this->items[$index]['quantity'] * $product->big_whole_price;
            }
        }
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
            $this->price = $this->saleType === 'retail' ? $product->retail_price : $product->big_whole_price;
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
            $this->price = $this->saleType === 'retail' ? $product->retail_price : $product->big_whole_price;
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
        $this->total = $this->quantity * $this->price;
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

        $existingKey = collect($this->items)->search(fn($item) => $item['barcode'] === $this->barcode && $this->barcode !== '');

        if ($existingKey !== false) {
            $this->items[$existingKey]['quantity'] += $this->quantity;

            $product = Warehouse::where('barcode', $this->barcode)->first();
            if ($product) {
                $price = $this->saleType === 'retail' ? $product->retail_price : $product->big_whole_price;
                $this->items[$existingKey]['price'] = $price;
                $this->items[$existingKey]['total'] = $this->items[$existingKey]['quantity'] * $price;
            }
        } else {
            $this->items[] = [
                'name' => $this->name,
                'barcode' => $this->barcode,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'total' => $this->total,
            ];
        }

        $this->resetForm();
        $this->searchName = '';
        $this->suggestions = [];
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
            $totalPrice = collect($this->items)->sum('total');
            $finalPrice = max(0, $totalPrice - $this->discount); 

            $sale = new Sale();
            $sale->sale_type = $this->saleType;
            $sale->total_price = $finalPrice; 
            $sale->discount = $this->discount; 
            $sale->customer_id = $this->customer_id;
            $sale->buyer_name = $this->customer_id ? optional(Customer::find($this->customer_id))->name : $this->buyerName;
            $sale->received_amount = $this->receivedAmount;
            $sale->remaining_amount = max(0, $finalPrice - $this->receivedAmount);
            $sale->user_id = Auth::id();
            $sale->save();


            $sale->invoice_number = $sale->id;
            $sale->save();
            $this->lastSale = $sale;

            foreach ($this->items as $item) {
                $warehouse = Warehouse::where('barcode', $item['barcode'])->first();
                if (!$warehouse) continue;

                // 📦 مدیریت کاهش موجودی
                if ($warehouse->unit === 'دانه') {
                    $warehouse->all_exist_number -= $item['quantity'];
                    if ($warehouse->all_exist_number < 0) $warehouse->all_exist_number = 0;
                } else {
                    if ($this->saleType === 'wholesale') {
                        $warehouse->quantity -= $item['quantity'];
                        $warehouse->all_exist_number -= ($item['quantity'] * $warehouse->big_quantity);
                        if ($warehouse->all_exist_number < 0) $warehouse->all_exist_number = 0;
                        if ($warehouse->quantity < 0) $warehouse->quantity = 0;
                    } else {
                        $warehouse->all_exist_number -= $item['quantity'];
                        if ($warehouse->all_exist_number < 0) $warehouse->all_exist_number = 0;

                        if ($warehouse->big_quantity > 0) {
                            $cartonsToReduce = intdiv($item['quantity'], $warehouse->big_quantity);
                            if ($cartonsToReduce > 0) {
                                $warehouse->quantity -= $cartonsToReduce;
                                if ($warehouse->quantity < 0) $warehouse->quantity = 0;
                            }
                        }

                        if ($warehouse->all_exist_number < $warehouse->big_quantity && $warehouse->all_exist_number > 0 && $warehouse->quantity > 0) {
                            $warehouse->quantity = 0;
                        }
                    }
                }

                if ($this->saleType === 'wholesale') {
                    $totalSale = $item['quantity'] * $warehouse->big_whole_price;
                    $totalCost = $item['quantity'] * $warehouse->price;
                } else {
                    $totalSale = $item['quantity'] * $warehouse->retail_price;
                    $totalCost = $item['quantity'] * $warehouse->price;
                }

              $totalProfit = $totalSale - $totalCost;


                $totalProfit -= ($sale->discount ?? 0);

                $profit = $totalProfit > 0 ? $totalProfit : 0;
                $loss   = $totalProfit < 0 ? abs($totalProfit) : 0;

                $warehouse->save();

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $item['price'],
                    'total_price' => $item['total'],
                    'profit' => $profit,
                    'user_id' => Auth::id(),
                    'loss' => $loss,
                ]);
            }

            if ($this->saleType === 'wholesale' && $sale->remaining_amount > 0) {
                Loan::create([
                    'customer_id' => $this->customer_id,
                    'amount' => $sale->remaining_amount,
                    'loan_recipt' => 0,
                    'reminded' => $sale->remaining_amount,
                    'type' => 'بردگی',
                    'user_id' => Auth::id(),
                    'date' => now(),
                ]);
            }

            $safe = Safe::firstOrCreate([], [
                'total' => 0,
                'today' => 0,
                'user_id' => Auth::id(),
                'last_update' => now()->toDateString(),
            ]);

            if ($safe->last_update !== now()->toDateString()) {
                $safe->today = 0;
                $safe->last_update = now()->toDateString();
            }

       if ($this->saleType === 'retail') {
            $safe->today += $finalPrice;
            $safe->total += $finalPrice;
        } else {
            $safe->today += $finalPrice;
            $safe->total += $this->receivedAmount;
        }


            $safe->save();
        });

        Notification::make()->title('فاکتور با موفقیت ثبت شد!')->success()->send();
    }

   public function printInvoice(): void
{
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
        'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
        'fontdata' => $fontData + [
            'vazir' => ['R' => 'ScheherazadeNew-Regular.ttf'],
        ],
        'default_font' => 'vazir',
    ]);

    $mpdf->SetDirectionality('rtl');
    $mpdf->autoScriptToLang = false;
    $mpdf->autoLangToFont = false;

    $css = file_get_contents(resource_path('views/pdf/invoice.css'));
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

    $discount = $sale->discount ?? 0;
    $finalPrice = max(0, $sale->total_price);

    $html = view('pdf.invoice', [
        'sale'       => $sale,
        'discount'   => $discount,
        'finalPrice' => $finalPrice,
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
        'paid_amount'    => $this->saleType === 'wholesale' ? $sale->received_amount : null,
        'sale_type'      => $this->saleType,
        'file_path'      => 'storage/' . $fileName,
    ]);

    $this->items = [];
    $this->lastSale = null;
    $this->buyerName = '';
    $this->receivedAmount = 0;
    $this->customer_id = null;
    $this->saleType = 'retail';
    $this->discount = 0;
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
        $this->price = 0;
        $this->total = 0;
        $this->productError = false;
    }

    public function increaseQuantity(int $index): void
    {
        if (!isset($this->items[$index])) return;
        $this->items[$index]['quantity']++;

        $product = Warehouse::where('barcode', $this->items[$index]['barcode'])->first();
        if ($product) {
            $price = $this->saleType === 'retail' ? $product->retail_price : $product->big_whole_price;
            $this->items[$index]['price'] = $price;
            $this->items[$index]['total'] = $this->items[$index]['quantity'] * $price;
        }
    }

    public function decreaseQuantity(int $index): void
    {
        if (!isset($this->items[$index])) return;
        if ($this->items[$index]['quantity'] > 1) {
            $this->items[$index]['quantity']--;

            $product = Warehouse::where('barcode', $this->items[$index]['barcode'])->first();
            if ($product) {
                $price = $this->saleType === 'retail' ? $product->retail_price : $product->big_whole_price;
                $this->items[$index]['price'] = $price;
                $this->items[$index]['total'] = $this->items[$index]['quantity'] * $price;
            }
        }
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

    public function getRemainingAmountProperty(): float
    {
        $total = collect($this->items)->sum('total');
        return max(0, $total - $this->receivedAmount);
    }
}
