<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Sale;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;


class SalesReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'گزارشات فروش';
    protected static ?string $navigationGroup = 'گزارشات و تنظیمات';
    protected static ?string $title = '📊 گزارش فروش';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 9;

    protected static string $view = 'filament.import.pages.sales-reports';

    public function getTitle(): string|Htmlable
    {
        return '';
    }

    public $buyer_name = '';
    public $sale_type = '';
    public $created_at = ''; // فقط یک تاریخ

    public $sales = [];

    public function updated()
    {
        $this->loadSales();
    }

    public function mount()
    {
        $this->loadSales();
    }

    public function loadSales()
    {
        $userId = Auth::id();
        $userRole = Auth::user()?->role;

        $query = Sale::with('customer')
            ->when($userRole !== 'superadmin', fn($q) => $q->where('user_id', $userId));

        if ($this->buyer_name) {
            $query->where('buyer_name', 'like', "%{$this->buyer_name}%");
        }

        if ($this->sale_type) {
            $query->where('sale_type', $this->sale_type);
        }

        if ($this->created_at) {
            $query->whereDate('created_at', $this->created_at);
        }

        $this->sales = $query->latest()->get()->toArray();
    }
}
