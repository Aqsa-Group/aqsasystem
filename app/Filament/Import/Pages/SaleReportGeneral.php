<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;
use App\Models\Import\SaleItem;
use App\Models\Import\Inventory;
use App\Models\Import\Sale;
use Illuminate\Contracts\Support\Htmlable;


class SaleReportGeneral extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.import.pages.sale-report-general';
    
    protected static ?string $navigationGroup = 'گزارشات و تنظیمات';
    protected static ?string $navigationLabel = 'گزارش فروش عمومی';
    protected static ?int $navigationSort = 12;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'گزارش فروش عمومی';

    
   
   
 
       public function getTitle(): string|Htmlable
    {
        return '';
    }



    public ?string $product_name = null;
    public array $report = [];
    public array $topProduct = [];
    public array $leastProduct = [];
    public $topWholesaleCustomer; 

    public function mount(): void
    {
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $query = SaleItem::with(['sale', 'warehouse']);

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

            $retailQuantity = $items->filter(fn($i) => $i->sale && $i->sale->sale_type === 'retail')->sum('quantity');
            $wholesaleQuantity = $items->filter(fn($i) => $i->sale && $i->sale->sale_type === 'wholesale')->sum('quantity');

            return [
                'name' => $warehouse->name ?? '---',
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

        
        $this->setTopAndLeast();
    }

    public function filterTopProduct(): void
    {
        $this->report = collect($this->report)->sortByDesc('total_quantity_sold')->values()->toArray();
        $this->setTopAndLeast();
    }

    public function filterLeastProduct(): void
    {
        $this->report = collect($this->report)->sortBy('total_quantity_sold')->values()->toArray();
        $this->setTopAndLeast();
    }

    public function showAllProducts(): void
    {
        $this->product_name = null;
        $this->generateReport();
    }

    protected function setTopAndLeast(): void
    {
        $collection = collect($this->report);

        $this->topProduct = $collection->sortByDesc('total_quantity_sold')->first() ?? [];
        $this->leastProduct = $collection->sortBy('total_quantity_sold')->first() ?? [];
    }

    

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
