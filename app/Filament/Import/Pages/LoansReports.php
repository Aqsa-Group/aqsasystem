<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Loan;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;


class LoansReports extends Page
{
    protected static ?string $slug = 'loans-reports';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.import.pages.loans-reports';
    protected static ?string $title = '💳 گزارش قرضه‌ها';

      public function getTitle(): string|Htmlable
    {
        return '';
    }

    public $loans = [];

    public $customer_name = '';
    public $type = '';
    public $date = '';

    public function updated()
    {
        $this->loadLoans();
    }

    public function mount()
    {
        $this->loadLoans();
    }

    public function loadLoans()
    {
        $userId = Auth::id();
        $role = Auth::user()?->role;

        $query = Loan::with('customer')
            ->when($role !== 'superadmin', fn($q) => $q->where('user_id', $userId));

        if ($this->customer_name) {
            $query->whereHas('customer', function ($q) {
                $q->where('name', 'like', "%{$this->customer_name}%");
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->date) {
            $query->whereDate('date', $this->date);
        }

        $this->loans = $query->orderBy('date', 'desc')->get()->toArray();
    }
}
