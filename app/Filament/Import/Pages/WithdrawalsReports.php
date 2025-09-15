<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Withdraw;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;


class WithdrawalsReports extends Page
{
    protected static ?string $slug = 'withdrawals-reports';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.import.pages.withdrawals-reports';
    protected static ?string $title = '💸 گزارش برداشت‌ها';
            public function getTitle(): string|Htmlable
    {
        return '';
    }

    public $withdrawals = [];

   
    public $staff_name = '';
    public $type = '';
    public $date = '';

    public function updated()
    {
        $this->loadWithdrawals();
    }

    public function mount()
    {
        $this->loadWithdrawals();
    }

    public function loadWithdrawals()
    {
        $userId = Auth::id();
        $role = Auth::user()?->role;

        $query = Withdraw::with('staff')
            ->when($role !== 'superadmin', fn($q) => $q->where('user_id', $userId));

        if ($this->staff_name) {
            $query->whereHas('staff', function ($q) {
                $q->where('name', 'like', "%{$this->staff_name}%");
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        $this->withdrawals = $query->latest()->get();
    }
}
