<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Loan;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Htmlable;

class LoansReports extends Page
{
    protected static ?string $slug = 'loans-reports';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.import.pages.loans-reports';
    protected static ?string $title = '💳 گزارش قرضه‌ها';

    public $loans = [];
    public $customer_name = '';
    public $type = '';
    public $date = '';

    public function getTitle(): string|Htmlable
    {
        return '';
    }

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

        $this->loans = $query->orderBy('date', 'desc')->get()->map(function ($loan) {
            $loan->reminded = Loan::where('customer_id', $loan->customer_id)
                ->where('currency', $loan->currency)
                ->sum(DB::raw("CASE WHEN type = 'بردگی' THEN amount ELSE 0 END"))
                - Loan::where('customer_id', $loan->customer_id)
                ->where('currency', $loan->currency)
                ->sum(DB::raw("CASE WHEN type = 'رسید' THEN loan_recipt ELSE 0 END"));
            return $loan;
        });
    }
}
