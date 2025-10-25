<?php

namespace App\Livewire\ToolsPanel;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tools\CashExchange;
use App\Models\Tools\CurrencySafe;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
use Morilog\Jalali\Jalalian;

class BuySellCurrency extends Component
{
    use WithFileUploads;

    // Component Properties
    public $transactionType = 'خرید';
    public $currencies = [];
    public $search = '';
    public $editingId = null;
    public $isEditing = false;
    public $confirmDeleteId = null;
    public $amountInWords = '';
    public $eqAmountInWords = '';
    public $exchangeRateInWords = '';
    

    // Form Fields
    public $currency = 'usd';
    public $to_currency = 'afn';
    public $amount = '';
    public $eq_amount = '';
    public $exchange_rate = '';
    public $date;
    public $description = '';
    public $transaction_file;

    // Calculations
    public $totalBuy = [];
    public $totalSell = [];
    public $netAmounts = [];

    // ==================== COMPONENT LIFECYCLE METHODS ====================

    /**
     * Render the component
     */
    public function render()
    {
        $user = Auth::guard('tools')->user();
        $transactions = CashExchange::when($this->search, function ($query) {
            $query->where('description', 'like', '%' . $this->search . '%')
                ->orWhere('type', 'like', '%' . $this->search . '%');
        })->latest()->get();

        $this->calculateTotals();

        return view('livewire.tools-panel.buy-sell-currency', [
            'transactions' => $transactions
        ]);
    }

    /**
     * Initialize component on mount
     */
    public function mount()
    {
        $this->currencies = [
            ['code' => 'usd', 'name_fa' => 'دالر'],
            ['code' => 'afn', 'name_fa' => 'افغانی'],
            ['code' => 'irr', 'name_fa' => 'تومان'],
            ['code' => 'pkr', 'name_fa' => 'کلدار'],
        ];

        $this->date = Jalalian::now()->format('Y/m/d');
        $this->calculateTotals();
    }

    // ==================== FORM FIELD UPDATES ====================

    /**
     * Handle amount field update
     */
    public function updatedAmount($value)
    {
        $this->calculateEquivalentAmount();
        $this->convertAmountToWords($value, 'amountInWords');
    }

    /**
     * Handle exchange rate field update
     */
    public function updatedExchangeRate($value)
    {
        $this->calculateEquivalentAmount();
        $this->convertAmountToWords($value, 'exchangeRateInWords');
    }

    /**
     * Handle currency field update
     */
    public function updatedCurrency()
    {
        $this->calculateEquivalentAmount();
        if ($this->eq_amount) {
            $this->convertAmountToWords($this->eq_amount, 'eqAmountInWords');
        }
    }

    /**
     * Handle to_currency field update
     */
    public function updatedToCurrency()
    {
        $this->calculateEquivalentAmount();
        if ($this->eq_amount) {
            $this->convertAmountToWords($this->eq_amount, 'eqAmountInWords');
        }
    }

    /**
     * Handle eq_amount field update
     */
    public function updatedEqAmount($value)
    {
        $this->convertAmountToWords($value, 'eqAmountInWords');
    }

    // ==================== CALCULATION METHODS ====================

    /**
     * Calculate equivalent amount based on exchange rate
     */
    public function calculateEquivalentAmount()
    {
        if ($this->amount && $this->exchange_rate) {
            $fromCurrency = $this->currency;
            $toCurrency = $this->to_currency;
            $amount = floatval($this->amount);
            $rate = floatval($this->exchange_rate);

            $shouldDivide = $this->shouldUseDivision($fromCurrency, $toCurrency);

            if ($shouldDivide) {
                $this->eq_amount = number_format($amount / $rate, 2, '.', '');
            } else {
                $this->eq_amount = number_format($amount * $rate, 2, '.', '');
            }
            
            // Convert equivalent amount to words
            $this->convertAmountToWords($this->eq_amount, 'eqAmountInWords');
        } else {
            $this->eq_amount = '';
            $this->eqAmountInWords = '';
        }
    }

    /**
     * Determine if division should be used for calculation
     */
    private function shouldUseDivision($fromCurrency, $toCurrency)
    {
        $localCurrencies = ['afn', 'irr', 'pkr'];
        $baseCurrencies = ['usd', 'eur', 'gbp'];

        if (in_array($fromCurrency, $localCurrencies) && in_array($toCurrency, $baseCurrencies)) {
            return true;
        }

        if (in_array($fromCurrency, $localCurrencies) && in_array($toCurrency, $localCurrencies)) {
            return true;
        }

        return false;
    }

    /**
     * Convert numeric amount to Persian words
     */
    private function convertAmountToWords($value, $property)
    {
        if ($value && is_numeric($value)) {
            $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
            $words = $formatter->format(floatval($value));
            $words = str_replace(['دویست', 'سیصد', 'پانصد'], ['دوصد', 'سه صد', 'پنجصد'], $words);
            $this->$property = $words;
        } else {
            $this->$property = '';
        }
    }

    /**
     * Calculate totals and net amounts
     */
    private function calculateTotals()
    {
        $user = Auth::guard('tools')->user();
        if (!$user) {
            return;
        }

        $userId = $user->id;
        $adminId = $user->admin_id ?? $user->id;

        // Try to find user's currency safe
        $safe = CurrencySafe::where('user_id', $userId)->first();

        // If user safe not found, use admin safe
        if (!$safe) {
            $safe = CurrencySafe::where('user_id', $adminId)->first();
        }

        // Display actual balances if safe exists
        if ($safe) {
            foreach ($this->currencies as $currency) {
                $code = $currency['code'];
                $this->netAmounts[$code] = $safe->{$code} ?? 0;
            }
        } else {
            // Display zero if no safe exists
            foreach ($this->currencies as $currency) {
                $code = $currency['code'];
                $this->netAmounts[$code] = 0;
            }
        }

        // Calculate buy and sell totals
        $this->totalBuy = array_fill_keys(array_column($this->currencies, 'code'), 0);
        $this->totalSell = array_fill_keys(array_column($this->currencies, 'code'), 0);

        $allTransactions = CashExchange::all();

        foreach ($allTransactions as $transaction) {
            if ($transaction->type === 'خرید') {
                $this->totalBuy[$transaction->to_currency] += $transaction->eq_amount;
                $this->totalSell[$transaction->from_currency] += $transaction->amount;
            } else {
                $this->totalSell[$transaction->from_currency] += $transaction->amount;
                $this->totalBuy[$transaction->to_currency] += $transaction->eq_amount;
            }
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get user balance for specific currency
     */
    public function getUserBalance($currency)
    {
        $user = Auth::guard('tools')->user();

        if (!$user) {
            return 0;
        }

        $userId = $user->id;

        // First try to find user's safe
        $safe = CurrencySafe::where('user_id', $userId)->first();

        // If user safe not found, use admin safe
        if (!$safe) {
            $adminId = $user->admin_id ?? $user->id;
            $safe = CurrencySafe::where('user_id', $adminId)->first();
        }

        return $safe->{$currency} ?? 0;
    }

    /**
     * Get currency name in Persian
     */
    public function getCurrencyName($code)
    {
        $currencyNames = [
            'usd' => 'دالر',
            'afn' => 'افغانی',
            'irr' => 'تومان',
            'pkr' => 'کلدار',
          
        ];

        return $currencyNames[$code] ?? $code;
    }

    /**
     * Get calculation formula for display
     */
    public function getCalculationFormula()
    {
        if (!$this->amount || !$this->exchange_rate) {
            return '';
        }

        $from = $this->getCurrencyName($this->currency);
        $to = $this->getCurrencyName($this->to_currency);

        $shouldDivide = $this->shouldUseDivision($this->currency, $this->to_currency);

        if ($shouldDivide) {
            return "{$this->amount} {$from} ÷ {$this->exchange_rate} = {$this->eq_amount} {$to}";
        } else {
            return "{$this->amount} {$from} × {$this->exchange_rate} = {$this->eq_amount} {$to}";
        }
    }

    /**
     * Get rate hint for user guidance
     */
    public function getRateHint()
    {
        if (!$this->currency || !$this->to_currency) {
            return '';
        }

        $from = $this->getCurrencyName($this->currency);
        $to = $this->getCurrencyName($this->to_currency);

        $shouldDivide = $this->shouldUseDivision($this->currency, $this->to_currency);

        if ($shouldDivide) {
            return "📊 نرخ: هر 1 {$to} چند {$from} است؟ (مثلاً 1 {$to} = ? {$from})";
        } else {
            return "📊 نرخ: هر 1 {$from} چند {$to} است؟ (مثلاً 1 {$from} = ? {$to})";
        }
    }

    // ==================== TRANSACTION OPERATIONS ====================

    /**
     * Toggle transaction type between buy and sell
     */
    public function toggleTransactionType()
    {
        $this->transactionType = $this->transactionType === 'خرید' ? 'فروش' : 'خرید';
    }

    /**
     * Swap source and destination currencies
     */
    public function swapCurrencies()
    {
        // Swap source and destination currencies
        $temp = $this->currency;
        $this->currency = $this->to_currency;
        $this->to_currency = $temp;

        // Reverse exchange rate if exists
        if ($this->exchange_rate && floatval($this->exchange_rate) > 0) {
            $currentRate = floatval($this->exchange_rate);
            $this->exchange_rate = number_format(1 / $currentRate, 4, '.', '');
        }

        // Recalculate equivalent amount
        $this->calculateEquivalentAmount();
    }

    /**
     * Check balance before transaction
     */
    public function checkBalance()
    {
        if (!$this->amount || !$this->currency) {
            return false;
        }

        $currentBalance = $this->getUserBalance($this->currency);
        $requiredAmount = floatval($this->amount);

        // Logic for balance check
        if ($this->transactionType === 'خرید') {
            // Buy: Check balance of currency we're giving
            if ($currentBalance < $requiredAmount) {
                session()->flash('message', "موجودی کافی نیست! موجودی {$this->getCurrencyName($this->currency)} شما: " . number_format($currentBalance));
                return false;
            }
        } else {
            // Sell: Check balance of currency we're selling
            $toCurrencyBalance = $this->getUserBalance($this->to_currency);
            $requiredEqAmount = floatval($this->eq_amount);
            if ($toCurrencyBalance < $requiredEqAmount) {
                session()->flash('message', "موجودی کافی نیست! موجودی {$this->getCurrencyName($this->to_currency)} شما: " . number_format($toCurrencyBalance));
                return false;
            }
        }

        return true;
    }

    /**
     * Submit transaction form
     */
    public function submitTransaction()
    {
        $this->validate([
            'currency' => 'required|string',
            'to_currency' => 'required|string|different:currency',
            'amount' => 'required|numeric|min:0.01',
            'exchange_rate' => 'required|numeric|min:0.01',
            'eq_amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'required|string|min:3',
            'transaction_file' => 'nullable|file|max:10240',
        ]);

        // Check balance (only for new transactions)
        if (!$this->isEditing && !$this->checkBalance()) {
            return;
        }

        try {
            DB::transaction(function () {
                $user = Auth::guard('tools')->user();
                $userId = $user->id;
                $adminId = $user->admin_id ?? $user->id;
                $amount = floatval($this->amount);
                $eqAmount = floatval($this->eq_amount);

                // If editing existing transaction
                if ($this->isEditing && $this->editingId) {
                    $transaction = CashExchange::findOrFail($this->editingId);
                    
                    // First reverse the previous transaction
                    $this->reverseTransaction($transaction);
                    
                    $filePath = $transaction->transaction_file;
                    if ($this->transaction_file) {
                        // Delete previous file if exists
                        if ($filePath) {
                            Storage::disk('public')->delete($filePath);
                        }
                        $filePath = $this->transaction_file->store('transaction-files', 'public');
                    }

                    // Update transaction
                    $transaction->update([
                        'type' => $this->transactionType,
                        'from_currency' => $this->currency,
                        'amount' => $amount,
                        'to_currency' => $this->to_currency,
                        'eq_amount' => $eqAmount,
                        'exchange_rate' => $this->exchange_rate,
                        'date' => $this->date,
                        'description' => $this->description,
                        'transaction_file' => $filePath,
                    ]);

                    // Apply new changes to safe
                    $this->applyTransaction($transaction);

                    session()->flash('message', 'تراکنش با موفقیت ویرایش شد.');

                } else {
                    // Create new transaction
                    $filePath = null;
                    if ($this->transaction_file) {
                        $filePath = $this->transaction_file->store('transaction-files', 'public');
                    }

                    $exchange = CashExchange::create([
                        'user_id' => $userId,
                        'admin_id' => $adminId !== $userId ? $adminId : null,
                        'type' => $this->transactionType,
                        'from_currency' => $this->currency,
                        'amount' => $amount,
                        'to_currency' => $this->to_currency,
                        'eq_amount' => $eqAmount,
                        'exchange_rate' => $this->exchange_rate,
                        'date' => $this->date,
                        'description' => $this->description,
                        'transaction_file' => $filePath,
                    ]);

                    // Update currency safe
                    $this->updateCurrencySafe($userId, $adminId, $amount, $eqAmount);

                    session()->flash('message', 'تراکنش با موفقیت ثبت شد و صندوق آپدیت شد.');
                }

                $this->resetForm();
            });

        } catch (\Exception $e) {
            session()->flash('message', 'خطا در ثبت تراکنش: ' . $e->getMessage());
        }
    }

    /**
     * Submit and print transaction
     */
    public function submitAndPrint()
    {
        $this->validate([
            'currency' => 'required|string',
            'to_currency' => 'required|string|different:currency',
            'amount' => 'required|numeric|min:0.01',
            'exchange_rate' => 'required|numeric|min:0.01',
            'eq_amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'required|string|min:3',
            'transaction_file' => 'nullable|file|max:10240',
        ]);

        // Check balance
        if (!$this->checkBalance()) {
            return;
        }

        try {
            $transaction = null;
            
            DB::transaction(function () use (&$transaction) {
                $filePath = null;
                if ($this->transaction_file) {
                    $filePath = $this->transaction_file->store('transaction-files', 'public');
                }

                $user = Auth::guard('tools')->user();
                $userId = $user->id;
                $adminId = $user->admin_id ?? $user->id;
                $amount = floatval($this->amount);
                $eqAmount = floatval($this->eq_amount);

                // Create transaction
                $transaction = CashExchange::create([
                    'user_id' => $userId,
                    'admin_id' => $adminId !== $userId ? $adminId : null,
                    'type' => $this->transactionType,
                    'from_currency' => $this->currency,
                    'amount' => $amount,
                    'to_currency' => $this->to_currency,
                    'eq_amount' => $eqAmount,
                    'exchange_rate' => $this->exchange_rate,
                    'date' => $this->date,
                    'description' => $this->description,
                    'transaction_file' => $filePath,
                ]);

                // Update currency safe
                $safe = CurrencySafe::where('user_id', $userId)->first();

                if (!$safe) {
                    $safe = new CurrencySafe();
                    $safe->user_id = $userId;
                    $safe->admin_id = $adminId !== $userId ? $adminId : null;

                    $adminSafe = CurrencySafe::where('user_id', $adminId)->first();
                    if ($adminSafe) {
                        foreach ($this->currencies as $currency) {
                            $safe->{$currency['code']} = $adminSafe->{$currency['code']} ?? 0;
                        }
                    } else {
                        foreach ($this->currencies as $currency) {
                            $safe->{$currency['code']} = 0;
                        }
                    }
                }

                if ($this->transactionType === 'خرید') {
                    $safe->{$this->currency} -= $amount;
                    $safe->{$this->to_currency} += $eqAmount;
                } else {
                    $safe->{$this->currency} -= $amount;
                    $safe->{$this->to_currency} += $eqAmount;
                }
                $safe->save();

                $this->resetForm();
            });

            // Generate PDF after successful submission
            if ($transaction) {
                return $this->generateTransactionPdf($transaction->id);
            }

        } catch (\Exception $e) {
            session()->flash('message', 'خطا در ثبت تراکنش: ' . $e->getMessage());
        }
    }

    // ==================== CURRENCY SAFE OPERATIONS ====================

    /**
     * Reverse a transaction from safe
     */
    private function reverseTransaction($transaction)
    {
        $safe = CurrencySafe::where('user_id', $transaction->user_id)->first();
        if ($safe) {
            if ($transaction->type === 'خرید') {
                // Reverse buy: subtract from bought currency, add to given currency
                $safe->{$transaction->to_currency} -= $transaction->eq_amount;
                $safe->{$transaction->from_currency} += $transaction->amount;
            } else {
                // Reverse sell: subtract from received currency, add to sold currency
                $safe->{$transaction->from_currency} -= $transaction->amount;
                $safe->{$transaction->to_currency} += $transaction->eq_amount;
            }
            $safe->save();
        }
    }

    /**
     * Apply transaction to safe
     */
    private function applyTransaction($transaction)
    {
        $safe = CurrencySafe::where('user_id', $transaction->user_id)->first();
        if ($safe) {
            if ($transaction->type === 'خرید') {
                $safe->{$transaction->from_currency} -= $transaction->amount;
                $safe->{$transaction->to_currency} += $transaction->eq_amount;
            } else {
                $safe->{$transaction->from_currency} -= $transaction->amount;
                $safe->{$transaction->to_currency} += $transaction->eq_amount;
            }
            $safe->save();
        }
    }

    /**
     * Update currency safe with new transaction
     */
    private function updateCurrencySafe($userId, $adminId, $amount, $eqAmount)
    {
        $safe = CurrencySafe::where('user_id', $userId)->first();

        if (!$safe) {
            $safe = new CurrencySafe();
            $safe->user_id = $userId;
            $safe->admin_id = $adminId !== $userId ? $adminId : null;

            $adminSafe = CurrencySafe::where('user_id', $adminId)->first();
            if ($adminSafe) {
                foreach ($this->currencies as $currency) {
                    $safe->{$currency['code']} = $adminSafe->{$currency['code']} ?? 0;
                }
            } else {
                foreach ($this->currencies as $currency) {
                    $safe->{$currency['code']} = 0;
                }
            }
        }

        if ($this->transactionType === 'خرید') {
            $safe->{$this->currency} -= $amount;
            $safe->{$this->to_currency} += $eqAmount;
        } else {
            $safe->{$this->currency} -= $amount;
            $safe->{$this->to_currency} += $eqAmount;
        }
        $safe->save();
    }

    // ==================== EDIT OPERATIONS ====================

    /**
     * Load transaction data for editing
     */
    public function editTransaction($id)
    {
        try {
            $transaction = CashExchange::findOrFail($id);
            
            // Check user access
            $user = Auth::guard('tools')->user();
            if ($transaction->user_id !== $user->id && $transaction->admin_id !== $user->id) {
                session()->flash('message', 'دسترسی به این تراکنش مجاز نیست.');
                return;
            }

            // Fill form with transaction data
            $this->editingId = $transaction->id;
            $this->isEditing = true;
            $this->transactionType = $transaction->type;
            $this->currency = $transaction->from_currency;
            $this->to_currency = $transaction->to_currency;
            $this->amount = number_format($transaction->amount, 2, '.', '');
            $this->eq_amount = number_format($transaction->eq_amount, 2, '.', '');
            $this->exchange_rate = number_format($transaction->exchange_rate, 2, '.', '');
            $this->date = $transaction->date;
            $this->description = $transaction->description;
            
            // Convert amounts to words
            $this->convertAmountToWords($this->amount, 'amountInWords');
            $this->convertAmountToWords($this->eq_amount, 'eqAmountInWords');
            $this->convertAmountToWords($this->exchange_rate, 'exchangeRateInWords');
            
            $this->transaction_file = null;

            session()->flash('info', 'حالت ویرایش فعال شد. داده‌های تراکنش در فرم لود شدند.');

        } catch (\Exception $e) {
            session()->flash('message', 'خطا در لود کردن تراکنش: ' . $e->getMessage());
        }
    }

    /**
     * Cancel edit operation
     */
    public function cancel()
    {
        $this->resetForm();
        session()->flash('info', 'ویرایش لغو شد.');
    }

    /**
     * Reset form fields
     */
    private function resetForm()
    {
        $this->reset([
            'amount',
            'eq_amount',
            'exchange_rate',
            'description',
            'transaction_file',
            'editingId',
            'isEditing',
            'amountInWords',
            'eqAmountInWords',
            'exchangeRateInWords'
        ]);
        $this->currency = 'usd';
        $this->to_currency = 'afn';
        $this->date = now()->toDateString();
        $this->transactionType = 'خرید';
    }

    // ==================== DELETE OPERATIONS ====================

    /**
     * Set transaction for deletion confirmation
     */
    public function deleteTransaction($id)
    {
        $this->confirmDeleteId = $id;
    }

    /**
     * Confirm and execute deletion
     */
    public function deleteConfirmed()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        try {
            DB::transaction(function () {
                $transaction = CashExchange::findOrFail($this->confirmDeleteId);
                
                // Return balance to safe
                $safe = CurrencySafe::where('user_id', $transaction->user_id)->first();
                if ($safe) {
                    if ($transaction->type === 'خرید') {
                        // Reverse buy: subtract from bought currency, add to given currency
                        $safe->{$transaction->to_currency} -= $transaction->eq_amount;
                        $safe->{$transaction->from_currency} += $transaction->amount;
                    } else {
                        // Reverse sell: subtract from received currency, add to sold currency
                        $safe->{$transaction->from_currency} -= $transaction->amount;
                        $safe->{$transaction->to_currency} += $transaction->eq_amount;
                    }
                    $safe->save();
                }

                // Delete file
                if ($transaction->transaction_file) {
                    Storage::disk('public')->delete($transaction->transaction_file);
                }

                $transaction->delete();
                
                session()->flash('message', 'تراکنش با موفقیت حذف شد و موجودی بازگردانده شد.');
                $this->confirmDeleteId = null;
            });

        } catch (\Exception $e) {
            session()->flash('message', 'خطا در حذف تراکنش: ' . $e->getMessage());
            $this->confirmDeleteId = null;
        }
    }

    
    /**
     * Cancel deletion operation
     */
    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
    }

    // ==================== PRINT OPERATIONS ====================

    /**
     * Generate transaction PDF
     */
    private function generateTransactionPdf($transactionId)
    {
        try {
            $transaction = CashExchange::findOrFail($transactionId);
            
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [85, 297],
                'directionality' => 'rtl',
                'margin_top' => 5,
                'margin_bottom' => 5,
                'margin_left' => 5,
                'margin_right' => 5,
                'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                    public_path('fonts'),
                ]),
                'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                    'Shabnam' => [
                        'R' => 'Shabnam-FD.ttf',
                    ],
                ],
                'default_font' => 'Shabnam',
            ]);

            $mpdf->SetAutoPageBreak(false);

            $html = view('pdf.Tools.cash-exchange', compact('transaction'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'تراکنش_صرافی_' . $transaction->id . '_' . $transaction->type . '.pdf';

            return response()->streamDownload(function () use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $fileName);

        } catch (\Exception $e) {
            session()->flash('message', 'خطا در ایجاد PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print existing transaction
     */
    public function printTransaction($id)
    {
        try {
            $transaction = CashExchange::findOrFail($id);
            
            // Check user access
            $user = Auth::guard('tools')->user();
            if ($transaction->user_id !== $user->id && $transaction->admin_id !== $user->id) {
                session()->flash('message', 'دسترسی به این تراکنش مجاز نیست.');
                return;
            }
            
            return $this->generateTransactionPdf($transaction->id);
            
        } catch (\Exception $e) {
            session()->flash('message', 'خطا در چاپ تراکنش: ' . $e->getMessage());
        }
    }
}