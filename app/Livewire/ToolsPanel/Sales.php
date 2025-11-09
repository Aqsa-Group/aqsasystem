<?php

namespace App\Livewire\ToolsPanel;

use App\Models\Tools\Warehouses;
use App\Models\Tools\Customer;
use App\Models\Tools\Sale;
use App\Models\Tools\Loan;
use App\Models\Tools\ShopSafe;
use App\Models\Tools\SaleItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Sales extends Component
{
    use WithPagination;

    public $saleType = 'retail';
    public $customerId = null;
    public $barcode = '';
    public $productName = '';
    public $quantity = 1;
    public $unitPrice = 0;
    public $totalAmount = 0;
    public $cartItems = [];
    public $filterInvoice = '';
    public $paidAmount = 0;
    public $remainingAmount = 0;
    public $discount = 0;
    public $description = '';
    public $date;
    public $searchCustomer = '';
    public $searchProduct = '';
    public $filterDate = '';
    public $filteredCustomers = [];
    public $filteredProducts = [];
    public $selectedProduct = null;
    public $selectedCustomer = null;
    public $autoSelectEnabled = true;

    // متغیرهای کارت‌های آماری
    public $todaySales = 0;
    public $todayProfit = 0;
    public $monthSales = 0;
    public $monthProfit = 0;
    public $totalSales = 0;
    public $totalProfit = 0;

    public $selectedSaleForReturn = null;
    public $returnItems = [];
    public $returnReason = '';
    public $returnTotal = 0;
    public $refundAmount = 0;

    // متغیر برای نمایش مجموع سبد خرید
    public $cartTotal = 0;
    public $cartProfit = 0; // سود خالص پس از کسر تخفیف
    public $cartProfitBeforeDiscount = 0; // سود قبل از تخفیف
    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->calculateStats();
    }

    public function calculateStats()
    {
        $user = Auth::guard('tools')->user();
        $userId = $user->id;
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        // تاریخ امروز
        $today = Carbon::today();
        $todayStart = $today->copy()->startOfDay();
        $todayEnd = $today->copy()->endOfDay();

        // تاریخ شروع ماه
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        // فروش امروز
        $todaySalesData = Sale::where('user_id', $userId)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->get();

        $this->todaySales = $todaySalesData->sum('total_price');
        $this->todayProfit = $todaySalesData->sum(function ($sale) {
            return $sale->saleItems->sum('profit');
        });

        // فروش ماه
        $monthSalesData = Sale::where('user_id', $userId)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->get();

        $this->monthSales = $monthSalesData->sum('total_price');
        $this->monthProfit = $monthSalesData->sum(function ($sale) {
            return $sale->saleItems->sum('profit');
        });

        // کل فروش
        $allSales = Sale::where('user_id', $userId)->get();
        $this->totalSales = $allSales->sum('total_price');
        $this->totalProfit = $allSales->sum(function ($sale) {
            return $sale->saleItems->sum('profit');
        });
    }

    public function switchToRetail()
    {
        $this->saleType = 'retail';
        $this->customerId = null;
        $this->selectedCustomer = null;
        $this->searchCustomer = '';

        foreach ($this->cartItems as $index => $item) {
            $product = Warehouses::find($item['product_id']);
            if ($product) {
                $this->cartItems[$index]['unit_price'] = $product->retail_price;
                $this->cartItems[$index]['total'] = $this->cartItems[$index]['quantity'] * $product->retail_price;
                $this->cartItems[$index]['profit'] = ($product->retail_price - $this->cartItems[$index]['purchase_price']) * $this->cartItems[$index]['quantity'];
            }
        }

        $this->calculateCartTotals();
    }

    public function switchToWholesale()
    {
        $this->saleType = 'wholesale';

        foreach ($this->cartItems as $index => $item) {
            $product = Warehouses::find($item['product_id']);
            if ($product) {
                $this->cartItems[$index]['unit_price'] = $product->wholesale_price;
                $this->cartItems[$index]['total'] = $this->cartItems[$index]['quantity'] * $product->wholesale_price;
                $this->cartItems[$index]['profit'] = ($product->wholesale_price - $this->cartItems[$index]['purchase_price']) * $this->cartItems[$index]['quantity'];
            }
        }

        $this->calculateCartTotals();
    }

    public function updatedSearchCustomer($value)
    {
        if (empty($value)) {
            $this->filteredCustomers = [];
            return;
        }

        $user = Auth::guard('tools')->user();
        $this->filteredCustomers = Customer::where('user_id', $user->id)
            ->where(function ($query) use ($value) {
                $query->where('fullname', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%");
            })->limit(10)->get();
    }

    public function selectCustomer($customerId)
    {
        $this->customerId = $customerId;
        $this->selectedCustomer = Customer::find($customerId);
        $this->searchCustomer = $this->selectedCustomer->fullname;
        $this->filteredCustomers = [];
    }

    public function clearCustomerSelection()
    {
        $this->customerId = null;
        $this->selectedCustomer = null;
        $this->searchCustomer = '';
        $this->filteredCustomers = [];
    }

    public function updatedSearchProduct($value)
    {
        if (empty($value)) {
            $this->filteredProducts = [];
            $this->clearCurrentProduct();
            return;
        }

        $user = Auth::guard('tools')->user();

        $exactBarcodeMatch = Warehouses::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('barcode', $value)
            ->first();

        if ($exactBarcodeMatch && $this->autoSelectEnabled) {
            $this->selectProduct($exactBarcodeMatch->id);
            return;
        }

        $this->filteredProducts = Warehouses::where('user_id', $user->id)
            ->where('is_active', true)
            ->where(function ($query) use ($value) {
                $query->where('product_name', 'like', "%{$value}%")
                    ->orWhere('barcode', 'like', "%{$value}%");
            })->limit(15)->get();

        if (count($this->filteredProducts) === 1 && $this->autoSelectEnabled && !$this->selectedProduct) {
            $this->selectProduct($this->filteredProducts[0]['id']);
        }
    }

    public function selectProduct($productId)
    {
        $this->selectedProduct = Warehouses::find($productId);
        if ($this->selectedProduct) {
            $this->barcode = $this->selectedProduct->barcode;
            $this->productName = $this->selectedProduct->product_name;
            $this->unitPrice = $this->saleType === 'retail'
                ? $this->selectedProduct->retail_price
                : $this->selectedProduct->wholesale_price;

            if ($this->quantity > $this->selectedProduct->total_quantity) {
                $this->quantity = max(1, $this->selectedProduct->total_quantity);
            }

            $this->filteredProducts = [];
            $this->calculateCurrentTotal();
            $this->dispatch('focus-quantity');
        }
    }

    public function addToCart()
    {
        if (!$this->selectedProduct) {
            session()->flash('error', 'لطفاً ابتدا محصولی انتخاب کنید.');
            return;
        }

        if ($this->selectedProduct->total_quantity < $this->quantity) {
            session()->flash('error', "موجودی محصول کافی نیست! موجودی: {$this->selectedProduct->total_quantity}");
            return;
        }

        $existingIndex = collect($this->cartItems)->search(function ($item) {
            return $item['product_id'] == $this->selectedProduct->id;
        });

        if ($existingIndex !== false) {
            $this->cartItems[$existingIndex]['quantity'] += $this->quantity;
            $this->cartItems[$existingIndex]['total'] = $this->cartItems[$existingIndex]['quantity'] * $this->cartItems[$existingIndex]['unit_price'];
            $this->cartItems[$existingIndex]['profit'] = ($this->cartItems[$existingIndex]['unit_price'] - $this->cartItems[$existingIndex]['purchase_price']) * $this->cartItems[$existingIndex]['quantity'];
        } else {
            $this->cartItems[] = [
                'product_id' => $this->selectedProduct->id,
                'product_name' => $this->selectedProduct->product_name,
                'barcode' => $this->selectedProduct->barcode,
                'quantity' => $this->quantity,
                'unit_price' => $this->unitPrice,
                'total' => $this->totalAmount,
                'purchase_price' => $this->selectedProduct->purchase_price_per_unit,
                'profit' => ($this->unitPrice - $this->selectedProduct->purchase_price_per_unit) * $this->quantity
            ];
        }

        $this->clearCurrentProduct();
        $this->calculateCartTotals();
    }


    public function calculateCartTotals()
    {
        // محاسبه مجموع سبد خرید از آیتم‌های موجود در سبد
        $this->cartTotal = collect($this->cartItems)->sum('total');

        // محاسبه سود کل قبل از تخفیف
        $this->cartProfitBeforeDiscount = collect($this->cartItems)->sum('profit');

        // محاسبه سود خالص پس از کسر تخفیف
        if ($this->discount > 0) {
            // روش صحیح: تخفیف مستقیماً از سود کسر می‌شود
            $this->cartProfit = max(0, $this->cartProfitBeforeDiscount - $this->discount);
        } else {
            $this->cartProfit = $this->cartProfitBeforeDiscount;
        }

        // تبدیل مقادیر به عدد برای جلوگیری از خطا
        $paidAmount = floatval($this->paidAmount);
        $discount = floatval($this->discount);

        // در فروش پرچون، مبلغ پرداختی باید برابر با کل مبلغ پس از تخفیف باشد
        if ($this->saleType === 'retail') {
            $this->paidAmount = max(0, $this->cartTotal - $discount);
            $this->remainingAmount = 0;
        } else {
            // در فروش عمده، محاسبه عادی
            $this->remainingAmount = max(0, $this->cartTotal - $paidAmount - $discount);
        }
    }

    public function calculateCurrentTotal()
    {
        $this->totalAmount = floatval($this->quantity) * floatval($this->unitPrice);
    }

    public function updatedQuantity()
    {
        $this->quantity = floatval($this->quantity);
        $this->calculateCurrentTotal();
    }

    public function updatedUnitPrice()
    {
        $this->unitPrice = floatval($this->unitPrice);
        $this->calculateCurrentTotal();
    }

    public function updatedPaidAmount()
    {
        // در فروش پرچون، کاربر نمی‌تواند مبلغ پرداختی را تغییر دهد
        if ($this->saleType === 'retail') {
            $this->paidAmount = max(0, $this->cartTotal - $this->discount);
            return;
        }

        $this->paidAmount = floatval($this->paidAmount);
        $this->calculateCartTotals();
    }

    public function updatedDiscount()
    {
        $this->discount = floatval($this->discount);

        // محدود کردن تخفیف به حداکثر سود ممکن
        if ($this->discount > $this->cartProfitBeforeDiscount) {
            $this->discount = $this->cartProfitBeforeDiscount;
            session()->flash('error', 'تخفیف نمی‌تواند بیشتر از سود کل باشد!');
        }

        // در فروش پرچون، پس از تغییر تخفیف، مبلغ پرداختی را به روز کن
        if ($this->saleType === 'retail') {
            $this->paidAmount = max(0, $this->cartTotal - $this->discount);
            $this->remainingAmount = 0;
        }

        $this->calculateCartTotals();
    }


    public function updatedSaleType($value)
    {
        if ($this->selectedProduct) {
            $this->unitPrice = $value === 'retail'
                ? $this->selectedProduct->retail_price
                : $this->selectedProduct->wholesale_price;
            $this->calculateCurrentTotal();
        }

        if ($value === 'retail') {
            $this->clearCustomerSelection();
            // در فروش پرچون، مبلغ پرداختی برابر با کل مبلغ پس از تخفیف است
            $this->paidAmount = max(0, $this->cartTotal - $this->discount);
            $this->remainingAmount = 0;
        }

        $this->calculateCartTotals();
    }

    public function increaseCartQuantity($index)
    {
        if (isset($this->cartItems[$index])) {
            $this->cartItems[$index]['quantity']++;
            $this->cartItems[$index]['total'] = $this->cartItems[$index]['quantity'] * $this->cartItems[$index]['unit_price'];
            $this->cartItems[$index]['profit'] = ($this->cartItems[$index]['unit_price'] - $this->cartItems[$index]['purchase_price']) * $this->cartItems[$index]['quantity'];
            $this->calculateCartTotals();
        }
    }

    public function decreaseCartQuantity($index)
    {
        if (isset($this->cartItems[$index]) && $this->cartItems[$index]['quantity'] > 1) {
            $this->cartItems[$index]['quantity']--;
            $this->cartItems[$index]['total'] = $this->cartItems[$index]['quantity'] * $this->cartItems[$index]['unit_price'];
            $this->cartItems[$index]['profit'] = ($this->cartItems[$index]['unit_price'] - $this->cartItems[$index]['purchase_price']) * $this->cartItems[$index]['quantity'];
            $this->calculateCartTotals();
        }
    }

    public function removeFromCart($index)
    {
        if (isset($this->cartItems[$index])) {
            array_splice($this->cartItems, $index, 1);
            $this->calculateCartTotals();
        }
    }

    public function submitSale()
    {

        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;
        if (empty($this->cartItems)) {
            session()->flash('error', 'سبد خرید خالی است!');
            return;
        }

        if ($this->saleType === 'wholesale' && !$this->customerId) {
            session()->flash('error', 'برای فروش عمده لطفاً مشتری انتخاب کنید.');
            return;
        }

        // اعتبارسنجی برای فروش پرچون - مبلغ پرداختی باید برابر با کل مبلغ پس از تخفیف باشد
        if ($this->saleType === 'retail' && $this->paidAmount < ($this->cartTotal - $this->discount)) {
            session()->flash('error', 'در فروش پرچون مبلغ پرداختی باید برابر با کل مبلغ فاکتور باشد.');
            return;
        }

        // اعتبارسنجی تخفیف
        if ($this->discount > $this->cartProfitBeforeDiscount) {
            session()->flash('error', 'تخفیف نمی‌تواند بیشتر از سود کل باشد!');
            return;
        }

        DB::transaction(function () {
            $user = Auth::guard('tools')->user();
            $adminId = $user->admin_id ?? $user->id;


            // محاسبه سود نهایی پس از کسر تخفیف
            $finalProfit = $this->cartProfit;

            // ایجاد فروش
            $sale = Sale::create([
                'sale_type' => $this->saleType,
                'customer_id' => $this->customerId,
                'total_price' => $this->cartTotal,
                'received_amount' => $this->paidAmount,
                'remaining_amount' => $this->remainingAmount,
                'discount' => $this->discount,
                'buyer_name' => $this->saleType === 'retail'
                    ? ($this->description ?: 'خریدار نقدی')
                    : optional(Customer::find($this->customerId))->fullname,
                'user_id'          => $user->id,
                'admin_id'         => $adminId,
                'invoice_number' => Sale::max('invoice_number') + 1,
                'final_profit' => $finalProfit // ذخیره سود نهایی پس از تخفیف
            ]);

            // محاسبه نسبت تخفیف برای تخصیص به آیتم‌ها
            $discountRatio = 0;
            if ($this->discount > 0 && $this->cartProfitBeforeDiscount > 0) {
                $discountRatio = $this->discount / $this->cartProfitBeforeDiscount;
            }

            // ایجاد آیتم‌های فروش و کاهش موجودی
            foreach ($this->cartItems as $item) {
                $product = Warehouses::find($item['product_id']);

                if ($product) {
                    $soldQuantity = $item['quantity'];

                    // بررسی موجودی کافی
                    if ($soldQuantity > $product->total_quantity) {
                        throw new \Exception("موجودی محصول {$product->product_name} کافی نیست! موجودی: {$product->total_quantity}");
                    }

                    // محاسبه تعداد بسته‌های کامل فروخته شده
                    $fullPackagesSold = floor($soldQuantity / $product->quantity_per_package);

                    // کاهش موجودی - total_quantity
                    $product->total_quantity -= $soldQuantity;

                    // اگر تعداد فروخته شده مضرب quantity_per_package بود، از total_packages کم کن
                    if ($fullPackagesSold > 0) {
                        $product->total_packages -= $fullPackagesSold;
                    }

                    // محاسبه مبلغ خرید کسر شده
                    $purchaseAmountReduction = $soldQuantity * $product->purchase_price_per_unit;

                    // کاهش از total_purchase_amount
                    $product->total_purchase_amount -= $purchaseAmountReduction;

                    // به روزرسانی وضعیت
                    if ($product->total_quantity <= 0) {
                        $product->status = 'ناموجود';
                        $product->total_quantity = 0;
                        $product->total_packages = 0;
                        $product->total_purchase_amount = 0;
                    } elseif ($product->total_quantity <= $product->min_stock_level) {
                        $product->status = 'در حال تکمیل';
                    } else {
                        $product->status = 'موجود';
                    }

                    // محاسبه مجدد سود/ضرر
                    if ($product->wholesale_price > 0 && $product->purchase_price_per_unit > 0) {
                        $product->profit_loss_per_unit = $product->wholesale_price - $product->purchase_price_per_unit;
                        $product->total_profit_loss = $product->profit_loss_per_unit * $product->total_quantity;
                    }

                    // استفاده از saveQuietly برای جلوگیری از اجرای boot مدل
                    $product->saveQuietly();

                    // محاسبه سود این آیتم با در نظر گرفتن تخفیف
                    $itemProfit = $item['profit'];
                    if ($discountRatio > 0) {
                        // کسر تخفیف از سود این آیتم به نسبت سهم آن از سود کل
                        $discountFromThisItem = $item['profit'] * $discountRatio;
                        $itemProfit = max(0, $item['profit'] - $discountFromThisItem);
                    }

                    // ثبت آیتم فروش
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'warehouse_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price_per_unit' => $item['unit_price'],
                        'total_price' => $item['total'],
                        'profit' => $itemProfit, // سود این آیتم پس از کسر تخفیف
                        'user_id'          => $user->id,
                        'admin_id'         => $adminId,
                    ]);
                }
            }

            // ثبت قرضه برای فروش عمده (فقط زمانی که باقیمانده وجود دارد)
            if ($this->saleType === 'wholesale' && $this->remainingAmount > 0) {
                Loan::create([
                    'customer_id' => $this->customerId,
                    'user_id'          => $user->id,
                    'admin_id'         => $adminId,
                    'amount' => $this->remainingAmount,
                    'type' => 'برد',
                    'currency' => 'afn',
                    'date' => $this->date,
                    'description' => 'باقی مانده فاکتور فروش شماره ' . $sale->invoice_number
                ]);
            }

            // بروزرسانی صندوق
            $this->updateShopSafe($this->paidAmount);

            session()->flash('message', 'فروش با موفقیت ثبت شد! شماره فاکتور: ' . $sale->invoice_number . ' - سود خالص: ' . number_format($finalProfit) . ' افغانی');

            // بروزرسانی آمار پس از ثبت فروش
            $this->calculateStats();
        });

        $this->resetForm();
    }

    private function updateShopSafe($amount)
    {
        $user = Auth::guard('tools')->user();
            $adminId = $user->admin_id ?? $user->id;
        $safe = ShopSafe::firstOrCreate(
            ['user_id' => $user->id,
            'admin_id' =>$adminId

        ],
            ['afn' => 0]
        );
        $safe->afn += $amount;
        $safe->save();
    }

    public function clearCurrentProduct()
    {
        $this->selectedProduct = null;
        $this->barcode = '';
        $this->productName = '';
        $this->quantity = 1;
        $this->unitPrice = 0;
        $this->totalAmount = 0;
        $this->searchProduct = '';
        $this->filteredProducts = [];
    }

    public function resetForm()
    {
        $this->clearCurrentProduct();
        $this->cartItems = [];
        $this->paidAmount = 0;
        $this->discount = 0;
        $this->remainingAmount = 0;
        $this->description = '';
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->clearCustomerSelection();
        $this->saleType = 'retail';
        $this->cartTotal = 0;
        $this->cartProfit = 0;
        $this->cartProfitBeforeDiscount = 0;
    }
    public function printInvoice($saleId)
    {
        $sale = Sale::with(['saleItems.warehouse', 'customer'])->findOrFail($saleId);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 150],
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
        $html = view('pdf.Tools.sale-invoice', compact('sale'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'فاکتور_فروش_' . $sale->invoice_number . '.pdf';
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    public function scanBarcode($barcode)
    {
        $user = Auth::guard('tools')->user();
        $product = Warehouses::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('barcode', $barcode)
            ->first();

        if ($product) {
            $this->selectProduct($product->id);
            session()->flash('message', 'محصول با بارکد ' . $barcode . ' پیدا شد.');
        } else {
            session()->flash('error', 'محصولی با بارکد ' . $barcode . ' یافت نشد.');
            $this->searchProduct = $barcode;
        }
    }

    public function disableAutoSelect()
    {
        $this->autoSelectEnabled = false;
    }

    public function enableAutoSelect()
    {
        $this->autoSelectEnabled = true;
    }

    public function increaseReturnQuantity($index)
    {
        if (isset($this->returnItems[$index])) {
            if ($this->returnItems[$index]['return_quantity'] < $this->returnItems[$index]['max_returnable']) {
                $this->returnItems[$index]['return_quantity']++;
                $this->calculateReturnTotal();
            }
        }
    }

    public function decreaseReturnQuantity($index)
    {
        if (isset($this->returnItems[$index]) && $this->returnItems[$index]['return_quantity'] > 0) {
            $this->returnItems[$index]['return_quantity']--;
            $this->calculateReturnTotal();
        }
    }

    public function selectSaleForReturn($saleId)
    {
        $this->selectedSaleForReturn = Sale::with(['saleItems.warehouse', 'customer'])->find($saleId);
        $this->returnItems = [];
        $this->returnTotal = 0;
        $this->refundAmount = 0;
        $this->returnReason = '';

        if ($this->selectedSaleForReturn) {
            // پر کردن آیتم‌های قابل برگشت
            foreach ($this->selectedSaleForReturn->saleItems as $saleItem) {
                $this->returnItems[] = [
                    'sale_item_id' => $saleItem->id,
                    'product_name' => $saleItem->warehouse->product_name,
                    'barcode' => $saleItem->warehouse->barcode,
                    'quantity' => $saleItem->quantity,
                    'return_quantity' => 0,
                    'unit_price' => $saleItem->price_per_unit,
                    'total_price' => $saleItem->total_price,
                    'max_returnable' => $saleItem->quantity
                ];
            }
        }
    }

    public function updatedReturnItems()
    {
        $this->calculateReturnTotal();
    }

    public function calculateReturnTotal()
    {
        $this->returnTotal = 0;
        foreach ($this->returnItems as $item) {
            if ($item['return_quantity'] > 0) {
                $this->returnTotal += $item['return_quantity'] * $item['unit_price'];
            }
        }

        // در صورت برگشت کامل، مبلغ بازگشتی برابر با مبلغ پرداختی اصلی است
        $this->refundAmount = $this->returnTotal;
    }

    public function submitReturn()
    {
        if (!$this->selectedSaleForReturn) {
            session()->flash('error', 'لطفاً ابتدا فاکتور را انتخاب کنید.');
            return;
        }

        $totalReturnQuantity = collect($this->returnItems)->sum('return_quantity');
        if ($totalReturnQuantity <= 0) {
            session()->flash('error', 'لطفاً تعداد کالاهای برگشتی را مشخص کنید.');
            return;
        }

        if (empty($this->returnReason)) {
            session()->flash('error', 'لطفاً دلیل برگشت را وارد کنید.');
            return;
        }

        DB::transaction(function () {
            $user = Auth::guard('tools')->user();
            $userId = $user->id;

            // ایجاد رکورد برگشت
            $returnSale = Sale::create([
                'sale_type' => $this->selectedSaleForReturn->sale_type,
                'customer_id' => $this->selectedSaleForReturn->customer_id,
                'total_price' => -$this->returnTotal, // مقدار منفی برای برگشت
                'received_amount' => -$this->refundAmount, // مقدار منفی برای بازگشت وجه
                'remaining_amount' => 0,
                'discount' => 0,
                'buyer_name' => 'برگشت فاکتور شماره ' . $this->selectedSaleForReturn->invoice_number,
                'user_id' => $userId,
                'invoice_number' => Sale::max('invoice_number') + 1,
                'return_reason' => $this->returnReason,
                'original_sale_id' => $this->selectedSaleForReturn->id,
                'is_return' => true
            ]);

            // ایجاد آیتم‌های برگشت و افزایش موجودی
            foreach ($this->returnItems as $returnItem) {
                if ($returnItem['return_quantity'] > 0) {
                    $saleItem = SaleItem::find($returnItem['sale_item_id']);
                    $product = Warehouses::find($saleItem->warehouse_id);

                    if ($product) {
                        $returnedQuantity = $returnItem['return_quantity'];

                        // افزایش موجودی
                        $product->total_quantity += $returnedQuantity;

                        // محاسبه تعداد بسته‌های کامل برگشتی
                        $fullPackagesReturned = floor($returnedQuantity / $product->quantity_per_package);
                        if ($fullPackagesReturned > 0) {
                            $product->total_packages += $fullPackagesReturned;
                        }

                        // افزایش مبلغ خرید
                        $purchaseAmountAddition = $returnedQuantity * $product->purchase_price_per_unit;
                        $product->total_purchase_amount += $purchaseAmountAddition;

                        // به روزرسانی وضعیت
                        if ($product->total_quantity > 0) {
                            if ($product->total_quantity <= $product->min_stock_level) {
                                $product->status = 'در حال تکمیل';
                            } else {
                                $product->status = 'موجود';
                            }
                        }

                        // محاسبه مجدد سود/ضرر
                        if ($product->wholesale_price > 0 && $product->purchase_price_per_unit > 0) {
                            $product->profit_loss_per_unit = $product->wholesale_price - $product->purchase_price_per_unit;
                            $product->total_profit_loss = $product->profit_loss_per_unit * $product->total_quantity;
                        }

                        $product->saveQuietly();

                        // ثبت آیتم برگشت
                        SaleItem::create([
                            'sale_id' => $returnSale->id,
                            'warehouse_id' => $product->id,
                            'quantity' => $returnedQuantity,
                            'price_per_unit' => -$returnItem['unit_price'], // مقدار منفی
                            'total_price' => - ($returnedQuantity * $returnItem['unit_price']), // مقدار منفی
                            'profit' => - ($returnedQuantity * ($returnItem['unit_price'] - $product->purchase_price_per_unit)), // مقدار منفی
                            'user_id' => $userId,
                        ]);
                    }
                }
            }

            // بروزرسانی صندوق (کاهش موجودی به دلیل بازگشت وجه)
            $this->updateShopSafe(-$this->refundAmount);
            $adminId = $user->admin_id ?? $user->id;

            // بروزرسانی قرضه در صورت فروش عمده
            if ($this->selectedSaleForReturn->sale_type === 'wholesale' && $this->selectedSaleForReturn->customer_id) {
                // اگر مشتری بدهکار بود، بدهی کاهش می‌یابد
                $customerTotalDebt = Loan::where('customer_id', $this->selectedSaleForReturn->customer_id)
                    ->where('type', 'برد')
                    ->sum('amount');

                if ($customerTotalDebt > 0) {
                    // ایجاد رکورد برای کاهش بدهی
                    Loan::create([
                        'customer_id' => $this->selectedSaleForReturn->customer_id,
                        'user_id'          => $user->id,
                        'admin_id'         => $adminId,
                        'amount' => -min($this->refundAmount, $customerTotalDebt),
                        'type' => 'برد',
                        'currency' => 'afn',
                        'date' => $this->date,
                        'description' => 'کاهش بدهی به دلیل برگشت فاکتور شماره ' . $this->selectedSaleForReturn->invoice_number
                    ]);
                }
            }

            session()->flash('message', 'برگشت کالا با موفقیت ثبت شد! شماره فاکتور برگشت: ' . $returnSale->invoice_number);

            // بروزرسانی آمار
            $this->calculateStats();

            // بستن مودال
            $this->resetReturn();
        });
    }


    public function resetReturn()
    {
        $this->selectedSaleForReturn = null;
        $this->returnItems = [];
        $this->returnTotal = 0;
        $this->refundAmount = 0;
        $this->returnReason = '';
    }

    public function printReturnInvoice($saleId)
    {
        $sale = Sale::with(['saleItems.warehouse', 'customer'])->findOrFail($saleId);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 150],
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
        $html = view('pdf.Tools.return-invoice', compact('sale'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'فاکتور_برگشت_' . $sale->invoice_number . '.pdf';
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }
    public function render()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        $sales = Sale::with(['saleItems.warehouse', 'customer'])
            ->where(function ($query) use ($user, $adminId) {
                $query->where('user_id', $user->id)
                    ->orWhere('admin_id', $adminId);
            })
            ->where('is_return', false)
            ->when($this->filterDate, function ($query) {
                $query->whereDate('created_at', $this->filterDate);
            })
            ->when($this->filterInvoice, function ($query) {
                $query->where('invoice_number', 'like', '%' . $this->filterInvoice . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.tools-panel.sales', [
            'sales' => $sales,
            'cartCount' => count($this->cartItems),
            'totalProfit' => collect($this->cartItems)->sum('profit')
        ]);
    }
}
