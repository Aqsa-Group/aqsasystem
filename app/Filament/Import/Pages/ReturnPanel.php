<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Sale;
use App\Models\Import\SaleItem;
use App\Models\Import\Warehouse;
use App\Models\Import\Safe;
use App\Models\Import\SaleReturn;
use App\Models\Import\CustomerBalance;
use App\Models\Import\CustomerStory;
use App\Models\Import\Document;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class ReturnPanel extends Page
{
    protected static string $view = 'filament.pages.return-panel';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'بخش خرید و فروش';
    protected static ?string $navigationLabel = 'برگشتی';
    protected static ?int $navigationSort = 3;
    protected static ?string $route = '/return-panel';

    public string $invoiceNumber = '';
    public ?Sale $sale = null;
    public array $rows = [];
    public float $totalReturn = 0;
    public float $refundNow = 0;

    public function getTitle(): string
    {
        return '';
    }

    public function loadSale(): void
    {
        $this->sale = Sale::with(['items.warehouse', 'customer'])
            ->where('id', $this->invoiceNumber)
            ->first();

        if (!$this->sale) {
            $this->resetState();
            Notification::make()->title('فاکتور یافت نشد')->danger()->send();
            return;
        }

        $this->rows = [];
        foreach ($this->sale->items as $item) {
            $alreadyReturned = SaleReturn::where('sale_id', $this->sale->id)
                ->where('sale_item_id', $item->id)
                ->sum('quantity');

            $maxQty = $item->quantity - $alreadyReturned;

            $this->rows[] = [
                'sale_item_id' => $item->id,
                'warehouse_id' => $item->warehouse_id,
                'name'         => optional($item->warehouse)->name ?? '-',
                'unit'         => $this->sale->sale_type === 'wholesale'
                    ? (optional($item->warehouse)->unit ?? '-')
                    : 'عدد',
                'sale_price'   => $item->price_per_unit,
                'cost_price'   => optional($item->warehouse)->price ?? 0,
                'sold_qty'     => max(0, $maxQty),
                'qty'          => 0,
                'total'        => 0,
            ];
        }
        $this->calcTotals();
    }

    public function updatedRows(): void
    {
        $this->calcTotals();
    }

    private function calcTotals(): void
    {
        $this->totalReturn = 0;
        foreach ($this->rows as $i => $row) {
            $qty = max(0, (int)($row['qty'] ?? 0));
            $qty = min($qty, (int)$row['sold_qty']);
            $this->rows[$i]['qty'] = $qty;
            $line = $qty * (float)$row['sale_price'];
            $this->rows[$i]['total'] = $this->roundAmount($line);
            $this->totalReturn += $line;
        }
        $this->totalReturn = $this->roundAmount($this->totalReturn);
        $this->refundNow = $this->totalReturn;
    }

    private function roundAmount($value): float
    {
        return round((float) $value, 3);
    }

    public function submitReturn(): void
    {
        if (!$this->sale) {
            Notification::make()->title('ابتدا فاکتور را بارگذاری کنید')->warning()->send();
            return;
        }

        $hasQty = collect($this->rows)->sum('qty') > 0;
        if (!$hasQty) {
            Notification::make()->title('هیچ مقداری برای برگشت وارد نشده است')->warning()->send();
            return;
        }

        DB::transaction(function () {
            $returnTotal = 0;

            // ۱. ثبت آیتم‌های برگشتی
            foreach ($this->rows as $row) {
                $qty = (int)$row['qty'];
                if ($qty <= 0) continue;

                $saleItem  = SaleItem::with('warehouse')->find($row['sale_item_id']);
                $warehouse = $saleItem?->warehouse;
                if (!$saleItem || !$warehouse) continue;

                $this->increaseWarehouseOnReturn($warehouse, $qty, $this->sale->sale_type);
                $warehouse->save();

                $lineSale = $this->roundAmount($qty * (float)$saleItem->price_per_unit);
                $lineCost = $this->roundAmount($qty * (float)$warehouse->price);

                $profitDelta = $this->roundAmount($lineSale - $lineCost);
                if ($profitDelta > 0) {
                    $saleItem->profit = $this->roundAmount(max(0, $saleItem->profit - $profitDelta));
                    $saleItem->save();
                }

                $returnTotal += $lineSale;

                SaleReturn::create([
                    'sale_id'        => $this->sale->id,
                    'sale_item_id'   => $saleItem->id,
                    'warehouse_id'   => $warehouse->id,
                    'quantity'       => $qty,
                    'price_per_unit' => $saleItem->price_per_unit,
                    'total_price'    => $lineSale,
                    'user_id'        => Auth::id(),
                ]);
            }

            $returnTotal = $this->roundAmount($returnTotal);
            if ($returnTotal <= 0) return;

            $oldTotalPrice = $this->roundAmount($this->sale->total_price);
            $oldRemainingAmount = $this->roundAmount($this->sale->remaining_amount);

            // ۲. محاسبه سهم قرض و نقد
            $loanRefund = 0;
            $cashRefund = 0;

            if ($this->sale->sale_type === 'wholesale') {
                if ($oldRemainingAmount > 0) {
                    $loanRefund = min($returnTotal, $oldRemainingAmount);
                    $cashRefund = $this->roundAmount($returnTotal - $loanRefund);
                } else {
                    $cashRefund = $returnTotal;
                }
            } else {
                $cashRefund = $returnTotal;
            }

            // ۳. آپدیت CustomerBalance (کاهش بدهی)
            if ($this->sale->customer_id && $loanRefund > 0) {
                $balance = CustomerBalance::where('customer_id', $this->sale->customer_id)->first();
                if ($balance) {
                    $balance->usd = $this->roundAmount($balance->usd + $loanRefund);
                    $balance->user_id = Auth::id();
                    $balance->save();
                }
            }

            // ۴. آپدیت CustomerStory (فقط برد این فاکتور)
            if ($this->sale->customer_id && $loanRefund > 0) {
                $story = CustomerStory::where('sale_id', $this->sale->id)
                    ->where('type', 'برد')
                    ->first();

                if ($story) {
                    $newAmount = $this->roundAmount(max(0, $story->amount - $loanRefund));
                    if ($newAmount <= 0) {
                        $story->delete();
                    } else {
                        $story->amount = $newAmount;
                        $story->save();
                    }
                }
            }

            // ۵. آپدیت sale
            $newRemaining = $this->roundAmount(max(0, $oldRemainingAmount - $loanRefund));
            $newTotalPrice = $this->roundAmount(max(0, $oldTotalPrice - $returnTotal));

            // ✅ اگر کل فاکتور برگشت خورده
            if ($newTotalPrice <= 0) {
                $saleId = $this->sale->id;

                // حذف Document
                $this->deleteOldDocuments();

                // حذف SaleItems
                SaleItem::where('sale_id', $saleId)->delete();

                // حذف SaleReturns
                SaleReturn::where('sale_id', $saleId)->delete();

                // حذف CustomerStory
                CustomerStory::where('sale_id', $saleId)->delete();

                // حذف خود Sale
                $this->sale->delete();

                // ریست auto-increment
                $maxId = Sale::max('id') ?? 0;
                DB::connection('import')->statement("ALTER TABLE sales AUTO_INCREMENT = " . ($maxId + 1));

                $this->sale = null;
                Notification::make()->title('کل فاکتور برگشت خورد - فاکتور حذف شد')->success()->send();
                $this->resetState();
                return;
            }

            if ($newRemaining > $newTotalPrice) {
                $newRemaining = $newTotalPrice;
            }

            $this->sale->remaining_amount = $newRemaining;
            $this->sale->total_price = $newTotalPrice;
            $this->sale->save();

            // ۶. آپدیت صندوق
            if ($cashRefund > 0) {
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

                $safe->USD = $this->roundAmount($safe->USD - $cashRefund);
                $safe->today = $this->roundAmount($safe->today - $cashRefund);
                $safe->save();
            }

            // ۷. بازسازی PDF
            $this->deleteOldDocuments();
            $this->regenerateInvoice($this->sale->id);
        });

        Notification::make()->title('برگشتی با موفقیت ثبت شد')->success()->send();
        $this->resetState();
    }

    private function deleteOldDocuments(): void
    {
        $oldDocs = Document::where('sale_id', $this->sale->id)->get();
        foreach ($oldDocs as $doc) {
            $fullPath = public_path($doc->file_path);
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
            $doc->delete();
        }
    }

    public function regenerateInvoice(int $saleId): void
    {
        $sale = Sale::with(['items.warehouse', 'customer'])->find($saleId);
        if (!$sale) return;

        $mpdf = $this->createMpdfInstance();

        $css = file_get_contents(resource_path('views/pdf/invoice.css'));
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

        $total = 0;
        foreach ($sale->items as $item) {
            $returnedQty = SaleReturn::where('sale_id', $sale->id)
                ->where('sale_item_id', $item->id)
                ->sum('quantity');

            $soldQty = max(0, $item->quantity - $returnedQty);
            $item->sold_qty = $soldQty;
            $unitPrice = (float) $item->price_per_unit;
            $item->sold_total = $this->roundAmount($soldQty * $unitPrice);
            $total += $item->sold_total;
        }

        $total = $this->roundAmount($total);
        $discount = (float) $sale->discount;
        $finalTotal = $this->roundAmount(max(0, $total - $discount));

        $previousLoanRemaining = $this->roundAmount($sale->previous_loan ?? 0);

        $html = view('pdf.invoice', [
            'sale'                   => $sale,
            'finalTotal'             => $finalTotal,
            'discount'               => $discount,
            'previousLoanRemaining'  => $previousLoanRemaining,
        ])->render();

        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        $fileName = 'invoice-' . $sale->id . '.pdf';
        $mpdf->Output(storage_path('app/public/' . $fileName), \Mpdf\Output\Destination::FILE);

        Document::updateOrCreate(
            ['sale_id' => $sale->id],
            [
                'invoice_number' => $sale->invoice_number,
                'buyer_name'     => $sale->buyer_name,
                'total_amount'   => $finalTotal,
                'discount'       => $discount,
                'paid_amount'    => $sale->received_amount,
                'sale_type'      => $sale->sale_type,
                'file_path'      => 'storage/' . $fileName,
            ]
        );
    }

    private function createMpdfInstance(): Mpdf
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $defaultFontConfig = (new FontVariables())->getDefaults();

        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_top'    => 10,
            'margin_bottom' => 2,
            'margin_left'   => 10,
            'margin_right'  => 10,
            'fontDir'       => array_merge($defaultConfig['fontDir'], [public_path('fonts/vazir/')]),
            'fontdata'      => $defaultFontConfig['fontdata'] + [
                'vazir' => [
                    'R'          => 'Vazir-Light.ttf',
                    'B'          => 'Vazir-Bold.ttf',
                    'useOTL'     => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font'  => 'vazir',
            'tempDir'       => storage_path('app/mpdf'),
        ]);

        $mpdf->SetDirectionality('rtl');
        return $mpdf;
    }

    private function increaseWarehouseOnReturn(Warehouse $w, int $qty, string $saleType): void
    {
        if ($w->unit === 'دانه') {
            $w->all_exist_number += $qty;
            return;
        }

        if ($saleType === 'wholesale') {
            $w->quantity += $qty;
            $w->all_exist_number += ($qty * max(0, (int)$w->big_quantity));
            return;
        }

        $oldAll = (int)$w->all_exist_number;
        $w->all_exist_number = $oldAll + $qty;

        $bq = max(0, (int)$w->big_quantity);
        if ($bq > 0) {
            $oldCartons = intdiv($oldAll, $bq);
            $newCartons = intdiv((int)$w->all_exist_number, $bq);
            $diff = $newCartons - $oldCartons;
            if ($diff > 0) {
                $w->quantity += $diff;
            }
        }
    }

    private function resetState(): void
    {
        $this->sale = null;
        $this->rows = [];
        $this->totalReturn = 0;
        $this->refundNow = 0;
        $this->invoiceNumber = '';
    }
}