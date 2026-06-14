<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\CustomerStory;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;
use Carbon\Carbon;

class LoansReports extends Page
{
    protected static ?string $slug = 'loans-reports';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.import.pages.loans-reports';
    protected static ?string $title = '💳 گزارش قرضه‌ها';

    public $loans = [];
    public $customer_name = '';
    public $type = '';
    public $currency = '';
    public $date = '';
    public $startDate = '';
    public $endDate = '';

    public function getTitle(): string|Htmlable
    {
        return '💳 گزارش قرضه‌ها';
    }

    public function updated($property)
    {
        $this->loadLoans();
    }

    public function mount()
    {
        $this->loadLoans();
    }

    public function resetFilters()
    {
        $this->customer_name = '';
        $this->type = '';
        $this->currency = '';
        $this->date = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->loadLoans();
    }

    public function loadLoans()
    {
        $userId = Auth::id();
        $role = Auth::user()?->role;

        // ساخت کوئری پایه
        $query = CustomerStory::with(['customer', 'user', 'sale'])
            ->when($role !== 'superadmin', function ($q) use ($userId) {
                return $q->where('user_id', $userId);
            });

        // اعمال فیلتر نام مشتری
        if (!empty($this->customer_name)) {
            $query->whereHas('customer', function ($q) {
                $q->where('name', 'like', '%' . $this->customer_name . '%');
            });
        }

        // اعمال فیلتر نوع تراکنش
        if (!empty($this->type)) {
            $query->where('type', $this->type);
        }

        // اعمال فیلتر ارز
        if (!empty($this->currency)) {
            $query->where('currency', $this->currency);
        }

        // اعمال فیلتر تاریخ دقیق
        if (!empty($this->date)) {
            $query->whereDate('date', $this->date);
        }

        // اعمال فیلتر بازه تاریخ
        if (!empty($this->startDate)) {
            $query->whereDate('date', '>=', $this->startDate);
        }

        if (!empty($this->endDate)) {
            $query->whereDate('date', '<=', $this->endDate);
        }

        // دریافت و مرتب‌سازی داده‌ها
        $allStories = $query->orderBy('date', 'asc')
                           ->orderBy('created_at', 'asc')
                           ->orderBy('id', 'asc')
                           ->get();

        // محاسبه مانده برای هر مشتری و ارز
        $balances = [];
        $processedLoans = [];

        foreach ($allStories as $story) {
            // کلید یکتا برای هر مشتری و ارز
            $key = $story->customer_id . '_' . $story->currency;

            // مقداردهی اولیه
            if (!isset($balances[$key])) {
                $balances[$key] = [
                    'total_borrowed' => 0,
                    'total_receipt' => 0,
                    'balance' => 0,
                ];
            }

            // ذخیره مانده قبلی
            $previousBalance = $balances[$key]['balance'];

            // محاسبه بر اساس نوع تراکنش
            if ($story->type === 'برد') {
                $balances[$key]['total_borrowed'] += $story->amount;
                $balances[$key]['balance'] += $story->amount;
                
                $processedLoans[] = [
                    'id' => $story->id,
                    'date' => $story->date instanceof Carbon ? $story->date->format('Y-m-d') : $story->date,
                    'type' => 'برد',
                    'currency' => $story->currency,
                    'customer' => [
                        'name' => $story->customer->name ?? 'نامشخص',
                        'id' => $story->customer_id,
                    ],
                    'description' => $story->description,
                    'amount' => $story->amount,
                    'loan_recipt' => 0,
                    'reminded' => $balances[$key]['balance'],
                    'created_at' => $story->created_at instanceof Carbon ? 
                                   $story->created_at->format('Y-m-d H:i:s') : 
                                   $story->created_at,
                    'sale_id' => $story->sale_id,
                ];
                
            } elseif ($story->type === 'رسید') {
                $balances[$key]['total_receipt'] += $story->amount;
                $balances[$key]['balance'] = max(0, $balances[$key]['balance'] - $story->amount);
                
                $processedLoans[] = [
                    'id' => $story->id,
                    'date' => $story->date instanceof Carbon ? $story->date->format('Y-m-d') : $story->date,
                    'type' => 'رسید',
                    'currency' => $story->currency,
                    'customer' => [
                        'name' => $story->customer->name ?? 'نامشخص',
                        'id' => $story->customer_id,
                    ],
                    'description' => $story->description,
                    'amount' => $previousBalance, // مانده قبل از رسید
                    'loan_recipt' => $story->amount,
                    'reminded' => $balances[$key]['balance'],
                    'created_at' => $story->created_at instanceof Carbon ? 
                                   $story->created_at->format('Y-m-d H:i:s') : 
                                   $story->created_at,
                    'sale_id' => $story->sale_id,
                ];
            }
        }

        // معکوس کردن آرایه برای نمایش از جدید به قدیم
        $this->loans = array_reverse($processedLoans);
    }

    // محاسبه خلاصه آماری
    public function getSummaryStats()
    {
        $totalBorrowed = 0;
        $totalReceipts = 0;
        
        foreach ($this->loans as $loan) {
            if ($loan['type'] === 'برد') {
                $totalBorrowed += $loan['amount'];
            }
            if ($loan['type'] === 'رسید') {
                $totalReceipts += $loan['loan_recipt'];
            }
        }
        
        return [
            'total_borrowed' => $totalBorrowed,
            'total_receipts' => $totalReceipts,
            'total_remaining' => $totalBorrowed - $totalReceipts,
        ];
    }

    // دریافت مانده مشتریان خاص
    public function getCustomerBalances()
    {
        $balances = [];
        
        foreach ($this->loans as $loan) {
            $key = $loan['customer']['id'] . '_' . $loan['currency'];
            
            if (!isset($balances[$key])) {
                $balances[$key] = [
                    'customer_name' => $loan['customer']['name'],
                    'currency' => $loan['currency'],
                    'balance' => $loan['reminded'],
                ];
            } else {
                // آخرین مانده رو نگه میداریم
                $balances[$key]['balance'] = $loan['reminded'];
            }
        }
        
        return $balances;
    }
}