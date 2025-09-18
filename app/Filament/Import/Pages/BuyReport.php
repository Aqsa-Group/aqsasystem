<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;
use App\Models\Import\Buy;
use App\Models\Import\Company;
use Illuminate\Contracts\Support\Htmlable;

class BuyReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.import.pages.buy-report';
    protected static bool $shouldRegisterNavigation = false;

    public $selectedCompany = null; 
    public $companies = [];
    public $buys = [];

    /**
     * عنوان صفحه
     */
    public function getTitle(): string|Htmlable
    {
        return '';
    }

    /**
     * بارگذاری اولیه داده‌ها هنگام mount شدن صفحه
     */
    public function mount(): void
    {
        $this->companies = Company::orderBy('name')->get();
        $this->loadBuys();
    }

    /**
     * هر زمان که شرکت انتخاب شده تغییر کند
     */
    public function updatedSelectedCompany(): void
    {
        $this->loadBuys();
    }

private function loadBuys(): void
{
    $query = Buy::query()->with('company');

    if ($this->selectedCompany) {
        // خریدهای آن شرکت و همچنین خریدهای بدون شرکت
        $query->where(function($q) {
            $q->where('company_id', $this->selectedCompany)
              ->orWhereNull('company_id');
        });
    }

    $this->buys = $query->orderBy('created_at', 'desc')->get();
}



}
