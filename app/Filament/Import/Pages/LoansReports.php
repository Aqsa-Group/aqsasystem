<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Loan;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;
use App\Exports\LoansPdfExport;
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

    // تابع کمکی برای رفع مشکل UTF-8
    private function decodeUtf8($str)
    {
        return mb_convert_encoding($str, 'UTF-8', 'UTF-8');
    }

    public function exportPdf()
    {
        $export = new LoansPdfExport(
            $this->customer_name,
            $this->type,
            $this->currency,
            $this->date,
            $this->loans
        );

        return $export->generatePdf();
    }

    // تابع جدید برای گرفتن داده‌های فیلتر شده برای پرینت
    public function getFilteredLoansForPrint()
    {
        return $this->loans;
    }

    // تابع جدید برای گرفتن اطلاعات فیلترها
    public function getActiveFilters()
    {
        return [
            'customer_name' => $this->customer_name,
            'type' => $this->type,
            'currency' => $this->currency,
            'date' => $this->date,
        ];
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

        if ($this->currency) {
            $query->where('currency', $this->currency);
        }

        if ($this->date) {
            $query->whereDate('date', $this->date);
        }

        $allLoans = $query->orderBy('date', 'asc')
                          ->orderBy('created_at', 'asc')
                          ->get();

        $totals = [];
        $processedLoans = [];

        foreach ($allLoans as $loan) {
            $key = $loan->customer_id . '_' . $loan->currency;

            if (!isset($totals[$key])) {
                $totals[$key] = ['total_loan' => 0, 'total_receipt' => 0, 'balance' => 0];
            }

            $previous_balance = $totals[$key]['balance'];

            if ($loan->type === 'بردگی') {
                $totals[$key]['total_loan'] += $loan->amount ?? 0;
                $totals[$key]['balance'] += $loan->amount ?? 0;
            } elseif ($loan->type === 'رسید') {
                $totals[$key]['total_receipt'] += $loan->loan_recipt ?? 0;
                $totals[$key]['balance'] = max(0, $totals[$key]['balance'] - ($loan->loan_recipt ?? 0));
            }

            // آماده سازی برای نمایش
            if ($loan->type === 'بردگی') {
                $display_amount = $loan->amount ?? 0;
                $display_receipt = 0;
                $display_balance = $totals[$key]['balance'];
            } elseif ($loan->type === 'رسید') {
                $display_amount = $previous_balance;
                $display_receipt = $loan->loan_recipt ?? 0;
                $display_balance = $totals[$key]['balance'];
            }

            $processedLoans[] = [
                'id' => $loan->id,
                'date' => is_string($loan->date) ? Carbon::parse($loan->date)->format('Y-m-d') : $loan->date->format('Y-m-d'),
                'type' => $this->decodeUtf8($loan->type ?? '---'),
                'currency' => $this->decodeUtf8($loan->currency ?? '---'),
                'customer' => [
                    'name' => $this->decodeUtf8($loan->customer->name ?? '---'),
                ],
                'amount' => $display_amount,
                'loan_recipt' => $display_receipt,
                'reminded' => $display_balance,
                'created_at' => is_string($loan->created_at) ? Carbon::parse($loan->created_at)->format('Y-m-d H:i:s') : $loan->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $this->loans = array_reverse($processedLoans);
    }
}