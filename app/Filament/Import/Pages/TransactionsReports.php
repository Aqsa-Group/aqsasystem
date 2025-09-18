<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Transaction;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;

class TransactionsReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'گزارش ترانزکشن‌ها';
    protected static ?string $navigationGroup = 'گزارشات و تنظیمات';
    protected static ?string $title = '💰 گزارش ترانزکشن‌ها';
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.import.pages.transactions-reports';


       public function getTitle(): string|Htmlable
    {
        return '';
    }


    public $transaction_type = '';
    public $user_name = '';
    public $sarafi_name = '';
    public $transactions = [];

    public function updated()
    {
        $this->loadTransactions();
    }

    public function mount()
    {
        $this->loadTransactions();
    }

    public function loadTransactions()
    {
        $query = Transaction::query()->with(['customer', 'staff', 'sarafi']);

        if ($this->transaction_type) {
            $query->where('type', $this->transaction_type);
        }

        if ($this->user_name) {
            $query->where(function($q) {
                $q->where('person', 'like', "%{$this->user_name}%")
                  ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$this->user_name}%"))
                  ->orWhereHas('staff', fn($q2) => $q2->where('name', 'like', "%{$this->user_name}%"));
            });
        }

        if ($this->sarafi_name) {
            $query->whereHas('sarafi', fn($q) => $q->where('name', 'like', "%{$this->sarafi_name}%"));
        }

        $this->transactions = $query->latest()->get()->toArray();
    }
}
