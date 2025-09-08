<?php
namespace App\Filament\Import\Pages;

use App\Models\Import\Loan;
use App\Models\Import\Safe;
use App\Models\Import\Sale;
use App\Models\Import\Withdraw;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;


class SalesReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'گزارشات';
    protected static ?string $navigationGroup = 'گزارشات و تنظیمات';
    protected static ?string $title = '';
    protected static ?string $route = '/sales-reports';
    protected static ?int $navigationSort = 9;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected static string $view = 'filament.pages.sales-reports';

    public $sales = [];
    public $loans = [];
    public $safeSummary = [];
    public $withdrawals = [];


    public function getTitle(): string|Htmlable
    {
        return '';
    }

    public function mount()
    {
        $userId = Auth::id();
        $userRole = Auth::user()?->role;
    
        $this->sales = Sale::with('customer')
            ->when($userRole !== 'superadmin', fn($query) => $query->where('user_id', $userId))
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    
        $this->loans = Loan::with('customer')
            ->when($userRole !== 'superadmin', fn($query) => $query->where('user_id', $userId))
            ->orderBy('date', 'desc')
            ->get()
            ->toArray();
    
        $safe = Safe::orderBy('created_at', 'desc')->first();
        $this->safeSummary = $safe ? [
            'total' => $safe->total,
            'today' => $safe->today,
            'last_update' => $safe->last_update,
        ] : [
            'total' => 0,
            'today' => 0,
            'last_update' => null,
        ];
    
      
        $this->withdrawals = Withdraw::with('staff')
            ->when($userRole !== 'superadmin', fn($query) => $query->where('user_id', $userId))
            ->latest()
            ->get();
    }
    
}
