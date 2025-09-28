<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;
use App\Models\Import\SaleItem;
use App\Models\Import\Inventory;
use App\Models\Import\Sale;
use Illuminate\Support\Collection;

class SaleReportGeneral extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.import.pages.sale-report-general';
    
    protected static ?string $navigationGroup = 'گزارشات';
    protected static ?string $navigationLabel = 'گزارش فروش عمومی';
    protected static ?int $navigationSort = 1;

    public ?string $product_name = null;
    public array $report = [];
    public array $topProduct = [];
    public array $leastProduct = [];
    public $topWholesaleCustomer;

    public function mount(): void
    {
        $this->generateReport();
    }

    public function updatedProductName(): void
    {
        $this->generateReport();
    }

    public function filterTopProduct(): void
    {
        $this->report = collect($this->report)
            ->sortByDesc('total_quantity_sold')
            ->values()
            ->toArray();
        $this->setTopAndLeast();
    }

    public function filterLeastProduct(): void
    {
        $this->report = collect($this->report)
            ->sortBy('total_quantity_sold')
            ->values()
            ->toArray();
        $this->setTopAndLeast();
    }

    public function showAllProducts(): void
    {
        $this->product_name = null;
        $this->generateReport();
    }

    protected function generateReport(): void
    {
        $query = SaleItem::query()->with(['sale', 'warehouse']);

        if ($this->product_name) {
            $query->whereHas('warehouse', function($q) {
                $q->where('name', 'like', '%' . $this->product_name . '%');
            });
        }

        $saleItems = $query->get();

        $this->report = $saleItems->groupBy('warehouse_id')->map(function($items) {
            $warehouse = $items->first()->warehouse;
            if (!$warehouse) return null;
            
            $inventory = Inventory::where('name', $warehouse->name)->first();

            $retailQuantity = $items->where('sale.sale_type', 'retail')->sum('quantity');
            $wholesaleQuantity = $items->where('sale.sale_type', 'wholesale')->sum('quantity');

            return [
                'name' => $warehouse->name ?? 'نامشخص',
                'all_exist_number' => $warehouse->all_exist_number ?? 0,
                'all_exist_number_inventory' => $inventory->all_exist_number ?? 0,
                'retail_quantity' => $retailQuantity,
                'wholesale_quantity' => $wholesaleQuantity,
                'total_quantity_sold' => $items->sum('quantity'),
                'total_profit' => $items->sum('profit'),
                'total_loss' => $items->sum('loss'),
                'total_in_warehouse' => ($warehouse->all_exist_number ?? 0) + ($inventory->all_exist_number ?? 0),
            ];
        })->filter()->values()->toArray();

        $this->topWholesaleCustomer = Sale::where('sale_type', 'wholesale')
            ->selectRaw('buyer_name, SUM(total_price) as total_spent')
            ->groupBy('buyer_name')
            ->orderByDesc('total_spent')
            ->first();

        $this->setTopAndLeast();
    }

    protected function setTopAndLeast(): void
    {
        $collection = collect($this->report);

        if ($collection->isNotEmpty()) {
            $this->topProduct = $collection->sortByDesc('total_quantity_sold')->first() ?? [];
            $this->leastProduct = $collection->sortBy('total_quantity_sold')->first() ?? [];
        } else {
            $this->topProduct = [];
            $this->leastProduct = [];
        }
    }

    public function getTitle(): string
    {
        return 'گزارش فروش محصولات';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}