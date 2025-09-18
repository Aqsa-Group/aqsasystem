<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;
use App\Models\Import\Company;
use App\Models\Import\CompanyPayment;
use Illuminate\Contracts\Support\Htmlable;


class CompanyPayments extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.import.pages.company-payments';
    protected static bool $shouldRegisterNavigation = false;




       public function getTitle(): string|Htmlable
    {
        return '';
    }


    public $selectedCompany = null;
    public $companies = [];
    public $payments = [];

    public function mount(): void
    {
        $this->companies = Company::all();
        $this->loadPayments();
    }

    public function updatedSelectedCompany(): void
    {
        $this->loadPayments();
    }

    private function loadPayments(): void
    {
        $query = CompanyPayment::query()->with('company');

        if ($this->selectedCompany) {
            $query->where('company_id', $this->selectedCompany);
        }

        $this->payments = $query->get();
    }
}
