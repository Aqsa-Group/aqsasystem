<?php

namespace App\Models\Sarafi;

use App\Models\Sarafi\Journals;
use App\Models\Sarafi\Trash;
use App\Services\WhatsAppService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'transactions';

    protected $fillable = [
        'customer_id', 'user_id', 'admin_id', 'currency', 'amount', 'type',
        'account_type', 'zone', 'by', 'date', 'description', 'transaction_file',
        'conversion_transfer_id', 'conversion_in_account_id', 'account_to_id',
        'remittance_id', 'changerdeal_id', 'withdrawbank_id',
        'external_transaction_id', 'safe_deal_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    // =======================
    // Relations
    // =======================
    public function customer() { return $this->belongsTo(Customer::class, 'customer_id'); }
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
    public function Safedeals() { return $this->belongsTo(SafeDeal::class, 'safe_deal_id'); }
    public function changerdeal() { return $this->belongsTo(ChangerDeal::class, 'changerdeal_id'); }
    public function withdrawbank() { return $this->belongsTo(WithdrawsBanks::class, 'withdrawbank_id'); }
    public function accounttoid() { return $this->belongsTo(SendToAccount::class, 'account_to_id'); }
    public function conversionInAccount() { return $this->belongsTo(ConversionInAccounts::class, 'conversion_in_account_id'); }
    public function externalTransaction() { return $this->belongsTo(ExternalTransactions::class, 'external_transaction_id'); }
    public function currencyInfo() { return $this->belongsTo(Currency::class, 'currency', 'code'); }

    // =======================
    // Accessors
    // =======================
    public function getTypeNameAttribute() { return $this->type === 'رسید' ? 'دریافت' : 'برداشت'; }
    public function getFormattedAmountAttribute() { return number_format($this->amount, 2); }
    public function getCurrencyNameAttribute()
    {
        $map = [
            'afn'=>'افغانی','usd'=>'دالر','irr'=>'تومان','eur'=>'یورو',
            'pkr'=>'کلدار','aed'=>'درهم','try'=>'لیره','cny'=>'یوان',
            'gbp'=>'پوند','jpy'=>'ین','sar'=>'ریال سعودی','inr'=>'روپیه'
        ];
        return $map[$this->currency] ?? $this->currency;
    }

    // =======================
    // Model Events
    // =======================
    protected static function booted()
    {
        static::created(function ($model) {
            $model->createJournal();
            $model->sendWhatsApp();
        });

        static::updated(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            Trash::create([
                'document_type' => 'رسید / برد صندوق',
                'record_id' => $model->id,
                'action' => 'ویرایش',
                'document_discription' => $model->description,
                'old_data' => $model->getOriginal(),
                'new_data' => $model->getAttributes(),
                'registered_user' => $model->user_id,
                'user_id' => $user->id,
                'admin_id' => $adminId,
            ]);

            $model->updateJournal();
        });

        static::deleted(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            Trash::create([
                'document_type' => 'رسید / برد صندوق',
                'record_id' => $model->id,
                'action' => 'حذف',
                'document_discription' => $model->description,
                'old_data' => $model->getAttributes(),
                'registered_user' => $model->user_id,
                'user_id' => $user->id,
                'admin_id' => $adminId,
            ]);

            $model->deleteJournal();
        });
    }

    // =======================
    // Safe Balance Logic
    // =======================
    private function getRealAccountBalance(string $currency, string $accountType, int $adminId): float
    {
        if ($accountType === 'نقدی') {
            $balance = CurrencySafe::where('admin_id', $adminId)->value($currency) ?? 0;
            Log::info("🔵 getRealAccountBalance: currency={$currency}, accountType={$accountType}, balance={$balance}");
            return (float)$balance;
        }
        
        if ($accountType === 'بانکی') {
            $balance = BankAccount::where('admin_id', $adminId)->value($currency) ?? 0;
            Log::info("🔵 getRealAccountBalance: currency={$currency}, accountType={$accountType}, balance={$balance}");
            return (float)$balance;
        }
        
        return 0;
    }

    private function shouldAffectSafeBalance(): bool
    {
        $result = true;

        if ($this->external_transaction_id) {
            Log::info("shouldAffectSafeBalance: false - external_transaction_id exists");
            $result = false;
        }
        
        if (!in_array($this->account_type, ['نقدی', 'بانکی'])) {
            Log::info("shouldAffectSafeBalance: false - account_type is not نقدی/بانکی");
            $result = false;
        }
        
        if ($this->account_type === 'بانکی' && $this->type === 'برد' && $this->customer && $this->customer->type === 'sarafi_card') {
            Log::info("shouldAffectSafeBalance: false - sarafi_card برد بانکی");
            $result = false;
        }
        
        if ($this->conversion_transfer_id || $this->conversion_in_account_id || $this->account_to_id) {
            Log::info("shouldAffectSafeBalance: false - conversion or account transfer");
            $result = false;
        }

        Log::info("shouldAffectSafeBalance: {$result} برای TX {$this->id}");
        return $result;
    }

    // =======================
    // Journal Management
    // =======================
  public function createJournal()
{
    try {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // ----------------------------
        // محاسبه balance مشتری
        // ----------------------------
        $balanceBefore = static::where('customer_id', $this->customer_id)
            ->where('currency', $this->currency)
            ->where('account_type', $this->account_type)
            ->where('admin_id', $adminId)
            ->where('id', '<>', $this->id)
            ->sum(DB::raw("CASE WHEN type='رسید' THEN amount WHEN type='برد' THEN -amount ELSE 0 END"));

        $signedAmount = $this->type === 'رسید' ? $this->amount : -$this->amount;
        $balanceAfter = $balanceBefore + $signedAmount;

        // ----------------------------
        // محاسبه safe_balance از آخرین ژورنال
        // ----------------------------
        $lastSafeBalance = Journals::where('admin_id', $adminId)
            ->where('currency', $this->currency)
            ->where('account_type', $this->account_type)
            ->orderByDesc('id')
            ->value('safe_balance');

        if ($lastSafeBalance === null) {
            // اگر اولین تراکنش برای این ارز/اکانت است، از موجودی واقعی صندوق استفاده کن
            $safeBalance = $this->getRealAccountBalance($this->currency, $this->account_type, $adminId);
        } else {
            // اگر ژورنال قبلی وجود دارد، safe_balance را با signedAmount جمع کن
            $safeBalance = $lastSafeBalance;
            if ($this->shouldAffectSafeBalance()) {
                $safeBalance += $signedAmount;
            }
        }

        // ----------------------------
        // ایجاد ژورنال جدید
        // ----------------------------
        Journals::create([
            'transaction_id' => $this->id,
            'customer_id'    => $this->customer_id,
            'user_id'        => $user->id,
            'admin_id'       => $adminId,
            'currency'       => $this->currency,
            'type'           => $this->type,
            'account_type'   => $this->account_type,
            'amount'         => $this->amount,
            'balance'        => $balanceAfter,
            'safe_balance'   => $safeBalance,
            'description'    => $this->description,
            'date'           => $this->date,
        ]);

        Log::info("Journal created for Transaction ID={$this->id}: balance={$balanceAfter}, safe_balance={$safeBalance}");
    } catch (\Exception $e) {
        Log::error("Error creating journal for transaction {$this->id}: " . $e->getMessage());
        throw $e;
    }
}


    public function updateJournal()
    {
        Log::info('=== شروع updateJournal ===');
        Log::info("Transaction ID={$this->id}");
        
        try {
            $journal = Journals::where('transaction_id', $this->id)->first();
            if (!$journal) {
                Log::warning('ژورنال پیدا نشد!');
                return;
            }

            $adminId = $journal->admin_id;
            $oldCurrency = $journal->currency;
            $oldAccountType = $journal->account_type;

            $newCurrency = $this->currency;
            $newAccountType = $this->account_type;

            Log::info("Old: Currency={$oldCurrency}, AccountType={$oldAccountType}");
            Log::info("New: Currency={$newCurrency}, AccountType={$newAccountType}");

            // اگر ارز تغییر کرده
            if ($oldCurrency !== $newCurrency) {
                Log::info("ارز تغییر کرده: {$oldCurrency} → {$newCurrency}");
                
                $this->handleCurrencyChange($journal, $adminId, $oldCurrency, $oldAccountType, $newCurrency, $newAccountType);
                Log::info('=== پایان updateJournal با تغییر ارز ===');
                return;
            }

            // اگر نوع حساب تغییر کرده (ولی ارز ثابت است)
            if ($oldAccountType !== $newAccountType) {
                Log::info("نوع حساب تغییر کرده: {$oldAccountType} → {$newAccountType}");
                
                $this->handleAccountTypeChange($journal, $adminId, $oldCurrency, $oldAccountType, $newAccountType);
                Log::info('=== پایان updateJournal با تغییر نوع حساب ===');
                return;
            }

            // اگر فقط مقدار یا نوع تغییر کرده (ارز و نوع حساب ثابت است)
            Log::info("فقط مقدار یا نوع تغییر کرده");
            $this->handleAmountOrTypeChange($journal, $adminId);
            
        } catch (\Exception $e) {
            Log::error("Error in updateJournal for transaction {$this->id}: " . $e->getMessage());
            throw $e;
        }
        
        Log::info('=== پایان updateJournal ===');
    }

    private function handleCurrencyChange($journal, $adminId, $oldCurrency, $oldAccountType, $newCurrency, $newAccountType)
    {
        Log::info("=== شروع handleCurrencyChange ===");
        Log::info("از: {$oldCurrency} ({$oldAccountType}) به: {$newCurrency} ({$newAccountType})");
        
        // 1. محاسبه safe_balance جدید بر اساس صندوق واقعی ارز جدید
        $signedAmount = $this->type === 'رسید' ? $this->amount : -$this->amount;
        $realSafeBalance = $this->getRealAccountBalance($newCurrency, $newAccountType, $adminId);
        
        $newSafeBalance = $realSafeBalance;
        if ($this->shouldAffectSafeBalance()) {
            $newSafeBalance += $signedAmount;
        }
        
        Log::info("realSafeBalance برای {$newCurrency}: {$realSafeBalance}");
        Log::info("signedAmount: {$signedAmount}");
        Log::info("newSafeBalance محاسبه شده: {$newSafeBalance}");

        // 2. محاسبه diff برای تراکنش‌های بعدی
        $oldSignedAmount = $journal->type === 'رسید' ? $journal->amount : -$journal->amount;
        $newSignedAmount = $signedAmount;
        $diff = $newSignedAmount - $oldSignedAmount;
        
        Log::info("Diff برای تراکنش‌های بعدی: {$diff}");

        // 3. آپدیت ژورنال فعلی
        $journal->update([
            'currency'     => $newCurrency,
            'account_type' => $newAccountType,
            'amount'       => $this->amount,
            'type'         => $this->type,
            'description'  => $this->description,
            'date'         => $this->date,
            'safe_balance' => $newSafeBalance,
            'balance'      => $this->calculateCustomerBalance($this->customer_id, $newCurrency, $newAccountType, $adminId)
        ]);

        Log::info("ژورنال فعلی آپدیت شد: safe_balance={$newSafeBalance}");

        // 4. آپدیت تراکنش‌های بعدی ارز جدید
        $this->updateLaterJournalsForCurrency($adminId, $newCurrency, $newAccountType, $diff, $journal, true);

        Log::info("=== پایان handleCurrencyChange ===");
    }

    private function handleAccountTypeChange($journal, $adminId, $currency, $oldAccountType, $newAccountType)
    {
        Log::info("=== شروع handleAccountTypeChange ===");
        Log::info("از: {$oldAccountType} به: {$newAccountType} (ارز: {$currency})");
        
        // 1. محاسبه safe_balance جدید بر اساس صندوق واقعی نوع حساب جدید
        $signedAmount = $this->type === 'رسید' ? $this->amount : -$this->amount;
        $realSafeBalance = $this->getRealAccountBalance($currency, $newAccountType, $adminId);
        
        $newSafeBalance = $realSafeBalance;
        if ($this->shouldAffectSafeBalance()) {
            $newSafeBalance += $signedAmount;
        }
        
        Log::info("realSafeBalance برای {$newAccountType}: {$realSafeBalance}");
        Log::info("newSafeBalance محاسبه شده: {$newSafeBalance}");

        // 2. محاسبه diff
        $oldSignedAmount = $journal->type === 'رسید' ? $journal->amount : -$journal->amount;
        $newSignedAmount = $signedAmount;
        $diff = $newSignedAmount - $oldSignedAmount;
        
        Log::info("Diff: {$diff}");

        // 3. آپدیت ژورنال فعلی
        $journal->update([
            'account_type' => $newAccountType,
            'amount'       => $this->amount,
            'type'         => $this->type,
            'description'  => $this->description,
            'date'         => $this->date,
            'safe_balance' => $newSafeBalance,
            'balance'      => $this->calculateCustomerBalance($this->customer_id, $currency, $newAccountType, $adminId)
        ]);

        // 4. آپدیت تراکنش‌های بعدی
        $this->updateLaterJournalsForCurrency($adminId, $currency, $newAccountType, $diff, $journal, true);

        Log::info("=== پایان handleAccountTypeChange ===");
    }

    private function handleAmountOrTypeChange($journal, $adminId)
    {
        Log::info("=== شروع handleAmountOrTypeChange ===");
        
        // محاسبه diff
        $oldSignedAmount = $journal->type === 'رسید' ? $journal->amount : -$journal->amount;
        $newSignedAmount = $this->type === 'رسید' ? $this->amount : -$this->amount;
        $diff = $newSignedAmount - $oldSignedAmount;

        Log::info("Diff: {$diff} (OldSigned: {$oldSignedAmount}, NewSigned: {$newSignedAmount})");

        // 1. آپدیت ژورنال فعلی
        $journal->update([
            'amount'       => $this->amount,
            'type'         => $this->type,
            'description'  => $this->description,
            'date'         => $this->date,
            'balance'      => $journal->balance + $diff,
            'safe_balance' => $journal->safe_balance + $diff,
        ]);

        Log::info("ژورنال فعلی آپدیت شد: balance={$journal->balance}, safe_balance={$journal->safe_balance}");

        // 2. آپدیت تراکنش‌های بعدی
        $this->updateLaterJournalsForCurrency($adminId, $this->currency, $this->account_type, $diff, $journal, false);

        Log::info("=== پایان handleAmountOrTypeChange ===");
    }

    private function updateLaterJournalsForCurrency($adminId, $currency, $accountType, $diff, $baseJournal, $isCurrencyChange = false)
    {
        Log::info("آپدیت تراکنش‌های بعدی برای {$currency} ({$accountType})");
        
        // فقط تراکنش‌های بعدی
        $laterJournals = Journals::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->where(function ($query) use ($baseJournal) {
                $query->where('date', '>', $baseJournal->date)
                    ->orWhere(function ($q) use ($baseJournal) {
                        $q->where('date', '=', $baseJournal->date)
                            ->where('id', '>', $baseJournal->id);
                    });
            })
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        Log::info("تعداد تراکنش‌های بعدی: " . $laterJournals->count());

        if ($laterJournals->isEmpty()) {
            Log::info("تراکنش بعدی وجود ندارد");
            return;
        }

        foreach ($laterJournals as $laterJournal) {
            $oldBalance = $laterJournal->balance;
            $oldSafeBalance = $laterJournal->safe_balance;
            
            // آپدیت balance (فقط برای مشتری همان)
            $laterTransaction = Transaction::find($laterJournal->transaction_id);
            if ($laterTransaction && $laterTransaction->customer_id === $this->customer_id) {
                $laterJournal->update(['balance' => $oldBalance + $diff]);
                Log::info("آپدیت balance برای Journal ID={$laterJournal->id}: {$oldBalance} → " . ($oldBalance + $diff));
            }
            
            // آپدیت safe_balance (همیشه اگر diff وجود دارد)
            if ($diff != 0) {
                // اگر تغییر ارز است، safe_balance جدید را محاسبه کن
                if ($isCurrencyChange) {
                    // برای تغییر ارز، باید safe_balance را از نو محاسبه کنیم
                    $this->recalculateSafeBalanceForLaterJournals($adminId, $currency, $accountType);
                    return; // بعد از بازمحاسبه کامل، نیازی به ادامه نیست
                } else {
                    $laterJournal->update(['safe_balance' => $oldSafeBalance + $diff]);
                    Log::info("آپدیت safe_balance برای Journal ID={$laterJournal->id}: {$oldSafeBalance} → " . ($oldSafeBalance + $diff));
                }
            }
        }
    }

    private function recalculateSafeBalanceForLaterJournals($adminId, $currency, $accountType)
    {
        Log::info("بازمحاسبه safe_balance برای تراکنش‌های بعدی {$currency}");
        
        // همه تراکنش‌های این ارز (برای بازمحاسبه صحیح)
        $transactions = Transaction::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        // موجودی واقعی صندوق
        $realSafeBalance = $this->getRealAccountBalance($currency, $accountType, $adminId);
        Log::info("موجودی واقعی صندوق {$currency}: {$realSafeBalance}");

        $runningSafeBalance = $realSafeBalance;

        // ابتدا به عقب برگرد (از آخر به اول)
        foreach ($transactions->reverse() as $tx) {
            if ($tx->shouldAffectSafeBalance()) {
                $signedAmount = $tx->type === 'رسید' ? -$tx->amount : $tx->amount;
                $runningSafeBalance += $signedAmount;
                Log::info("برعکس TX {$tx->id}: {$signedAmount} -> runningSafeBalance = {$runningSafeBalance}");
            }
        }

        Log::info("صندوق {$currency} در ابتدای تاریخ: {$runningSafeBalance}");

        // حالا به جلو برو و آپدیت کن
        foreach ($transactions as $tx) {
            $journal = Journals::where('transaction_id', $tx->id)->first();
            if (!$journal) continue;

            if ($tx->shouldAffectSafeBalance()) {
                $signedAmount = $tx->type === 'رسید' ? $tx->amount : -$tx->amount;
                $runningSafeBalance += $signedAmount;
                Log::info("اثر TX {$tx->id}: {$signedAmount} -> runningSafeBalance = {$runningSafeBalance}");
            }

            $oldSafeBalance = $journal->safe_balance;
            $journal->update(['safe_balance' => $runningSafeBalance]);

            // همچنین balance مشتری را محاسبه کن
            $customerBalance = $this->calculateCustomerBalance($tx->customer_id, $currency, $accountType, $adminId, $tx->id);
            $journal->update(['balance' => $customerBalance]);

            Log::info("آپدیت Journal TX {$tx->id}: safe_balance={$runningSafeBalance} (قبلاً: {$oldSafeBalance}), balance={$customerBalance}");
        }
    }

    private function calculateCustomerBalance($customerId, $currency, $accountType, $adminId, $maxTransactionId = null)
    {
        $query = Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->where('admin_id', $adminId);

        if ($maxTransactionId) {
            $query->where('id', '<=', $maxTransactionId);
        } else {
            $query->where('id', '<=', $this->id);
        }

        return $query->sum(DB::raw("CASE WHEN type='رسید' THEN amount WHEN type='برد' THEN -amount ELSE 0 END"));
    }

    public function deleteJournal()
    {
        Log::info('=== شروع deleteJournal ===');
        Log::info("تراکنش حذف شده: ID={$this->id}, Customer={$this->customer_id}, Amount={$this->amount}");

        try {
            $journal = Journals::where('transaction_id', $this->id)->first();
            if (!$journal) {
                Log::warning('ژورنال پیدا نشد - احتمالاً قبلاً حذف شده است');
                $this->recalculateAfterDeletion();
                return;
            }

            $adminId = $journal->admin_id;
            $currency = $journal->currency;
            $accountType = $journal->account_type;

            Log::info("پارامترها: Admin={$adminId}, Currency={$currency}, AccountType={$accountType}");

            // 1. حذف ژورنال فعلی
            Log::info("حذف ژورنال ID={$journal->id}");
            $journal->delete();

            // 2. بازمحاسبه
            $this->recalculateAfterDeletion($adminId, $currency, $accountType);
            
        } catch (\Exception $e) {
            Log::error("Error in deleteJournal for transaction {$this->id}: " . $e->getMessage());
            throw $e;
        }
        
        Log::info('=== پایان deleteJournal ===');
    }

    private function recalculateAfterDeletion($adminId = null, $currency = null, $accountType = null)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $adminId ?? ($user->admin_id ?? $user->id);
        $currency = $currency ?? $this->currency;
        $accountType = $accountType ?? $this->account_type;

        Log::info("بازمحاسبه برای Admin={$adminId}, Currency={$currency}, AccountType={$accountType}");

        // 1. بازمحاسبه balance برای همه مشتریان
        $customers = Transaction::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->distinct('customer_id')
            ->pluck('customer_id');

        Log::info("تعداد مشتریان برای بازمحاسبه: " . $customers->count());

        foreach ($customers as $customerId) {
            $this->recalculateCustomerBalance($customerId, $adminId, $currency, $accountType);
        }

        // 2. بازمحاسبه safe_balance
        if ($this->shouldAffectSafeBalance()) {
            $this->recalculateSafeBalance($adminId, $currency, $accountType);
        }
    }

    private function recalculateCustomerBalance($customerId, $adminId, $currency, $accountType)
    {
        Log::info("بازمحاسبه balance برای مشتری {$customerId}");

        $transactions = Transaction::where('customer_id', $customerId)
            ->where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        Log::info("تعداد تراکنش‌های موجود برای مشتری {$customerId}: " . $transactions->count());

        $balance = 0;
        foreach ($transactions as $tx) {
            $signedAmount = $tx->type === 'رسید' ? $tx->amount : -$tx->amount;
            $balance += $signedAmount;

            $journal = Journals::where('transaction_id', $tx->id)->first();
            if ($journal) {
                $oldBalance = $journal->balance;
                $journal->update(['balance' => $balance]);
                Log::info("آپدیت Journal ID={$journal->id}: {$oldBalance} → {$balance}");
            }
        }
    }

    private function recalculateSafeBalance($adminId, $currency, $accountType)
    {
        Log::info("بازمحاسبه safe_balance برای {$currency}");

        $realSafeBalance = $this->getRealAccountBalance($currency, $accountType, $adminId);
        Log::info("موجودی واقعی صندوق: {$realSafeBalance}");

        $transactions = Transaction::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        Log::info("تعداد تراکنش‌ها: " . $transactions->count());

        $runningSafeBalance = $realSafeBalance;

        foreach ($transactions->reverse() as $tx) {
            if ($tx->shouldAffectSafeBalance()) {
                $signedAmount = $tx->type === 'رسید' ? -$tx->amount : $tx->amount;
                $runningSafeBalance += $signedAmount;
            }
        }

        Log::info("صندوق {$currency} در ابتدای تاریخ: {$runningSafeBalance}");

        foreach ($transactions as $tx) {
            $journal = Journals::where('transaction_id', $tx->id)->first();
            if (!$journal) continue;

            if ($tx->shouldAffectSafeBalance()) {
                $signedAmount = $tx->type === 'رسید' ? $tx->amount : -$tx->amount;
                $runningSafeBalance += $signedAmount;
            }

            $journal->update(['safe_balance' => $runningSafeBalance]);
            Log::info("Journal ID={$journal->id} برای TX ID={$tx->id}: safe_balance={$runningSafeBalance}");
        }
    }

    // =======================
    // WhatsApp
    // =======================
    public function sendWhatsApp()
    {
        try {
            $customer = $this->customer;
            if (!$customer || !$customer->whatsapp_number) {
                Log::info("WhatsApp not sent - no customer or phone number");
                return;
            }

            $phone = preg_replace('/[^0-9]/', '', $customer->whatsapp_number);
            
            if (strlen($phone) < 10) {
                Log::warning("Invalid phone number: {$phone}");
                return;
            }

            $balance = Journals::where('transaction_id', $this->id)->value('balance') ?? $this->amount;

            WhatsAppService::sendTransaction(
                $phone,
                [
                    'exchange_name' => $this->user->sarafi_name ?? '-',
                    'account_number' => $customer->fullname ?? '-',
                    'amount' => (string)($this->amount ?? '-'),
                    'currency' => $this->getCurrencyNameAttribute() ?? '-',
                    'transaction_type' => $this->getTypeNameAttribute() ?? '-',
                    'transaction_date' => $this->date ? $this->date->format('Y-m-d H:i') : '-',
                    'balance' => (string)$balance,
                    'exchange_contact' => (string)($this->user->phone ?? '-'),
                ]
            );
            
            Log::info("WhatsApp sent successfully for transaction {$this->id}");
        } catch (\Exception $e) {
            Log::error("Error sending WhatsApp for transaction {$this->id}: " . $e->getMessage());
        }
    }
}   