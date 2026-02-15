<?php

namespace App\Models\Sarafi;

use App\Models\Sarafi\Journals;
use App\Models\Sarafi\Trash;
use App\Services\WhatsAppService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'transactions';

    protected $fillable = [
        'customer_id',
        'user_id',
        'admin_id',
        'currency',
        'amount',
        'type',
        'account_type',
        'zone',
        'by',
        'date',
        'description',
        'transaction_file',
        'conversion_transfer_id',
        'conversion_in_account_id',
        'account_to_id',
        'remittance_id',
        'changerdeal_id',
        'withdrawbank_id',
        'external_transaction_id',
        'safe_deal_id',
        'safe_deals_revenue_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date'   => 'date',
    ];

    /**
     * پرچم موقت برای حفظ موجودی صندوق در هنگام ویرایش
     * @var bool
     */
    protected $preserveSafe = false;

    // =======================
    // Relations
    // =======================
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function Safedeals()
    {
        return $this->belongsTo(SafeDeal::class, 'safe_deal_id');
    }
    public function changerdeal()
    {
        return $this->belongsTo(ChangerDeal::class, 'changerdeal_id');
    }
    public function withdrawbank()
    {
        return $this->belongsTo(WithdrawsBanks::class, 'withdrawbank_id');
    }
    public function safedealsrevenue()
    {
        return $this->belongsTo(SafeDealsRevenue::class, 'safe_deals_revenue_id');
    }
    public function accounttoid()
    {
        return $this->belongsTo(SendToAccount::class, 'account_to_id');
    }
    public function conversionInAccount()
    {
        return $this->belongsTo(ConversionInAccounts::class, 'conversion_in_account_id');
    }
    public function externalTransaction()
    {
        return $this->belongsTo(ExternalTransactions::class, 'external_transaction_id');
    }
    public function currencyInfo()
    {
        return $this->belongsTo(Currency::class, 'currency', 'code');
    }
    public function journal()
    {
        return $this->hasOne(Journals::class, 'transaction_id', 'id');
    }
    // =======================
    // Accessors
    // =======================
    public function getTypeNameAttribute()
    {
        return $this->type === 'رسید' ? 'دریافت' : 'برداشت';
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getCurrencyNameAttribute()
    {
        $map = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'irr' => 'تومان',
            'eur' => 'یورو',
            'pkr' => 'کلدار',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'gbp' => 'پوند',
            'jpy' => 'ین',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه'
        ];
        return $map[$this->currency] ?? $this->currency;
    }

    /**
     * مقدار امضاء شده (مثبت برای رسید، منفی برای برد)
     */
    public function getSignedAmountAttribute(): float
    {
        return $this->type === 'رسید' ? (float)$this->amount : -(float)$this->amount;
    }

    // =======================
    // Core Business Logic
    // =======================

    /**
     * آیا این تراکنش باید روی موجودی صندوق/بانک (safe_balance) تأثیر بگذارد؟
     */
    public function shouldAffectSafeBalance(): bool
    {
        Log::debug("shouldAffectSafeBalance for TX {$this->id}", [
            'external_transaction_id' => $this->external_transaction_id,
            'account_type'           => $this->account_type,
            'type'                   => $this->type,
            'customer_type'          => optional($this->customer)->type,
            'conversion_transfer_id' => $this->conversion_transfer_id,
            'conversion_in_account_id' => $this->conversion_in_account_id,
            'account_to_id'          => $this->account_to_id,
            'safe_deal_id'           => $this->safe_deal_id,
            'changerdeal_id'         => $this->changerdeal_id,
            'withdrawbank_id'        => $this->withdrawbank_id,
            'safe_deals_revenue_id'  => $this->safe_deals_revenue_id,
        ]);

        // External transactions never affect safe balance
        if ($this->external_transaction_id) {
            Log::info("❌ shouldAffectSafeBalance: false (external transaction)");
            return false;
        }

        // Only cash or bank accounts can affect safe balance
        if (!in_array($this->account_type, ['نقدی', 'بانکی'])) {
            Log::info("❌ shouldAffectSafeBalance: false (account_type not نقدی/بانکی)");
            return false;
        }

        // Special case: sarafi_card bank withdrawals do not affect safe balance
        if (
            $this->account_type === 'بانکی' && $this->type === 'برد'
            && $this->customer && $this->customer->type === 'sarafi_card'
        ) {
            Log::info("❌ shouldAffectSafeBalance: false (sarafi_card bank withdrawal)");
            return false;
        }

        // Transactions linked to conversions or account transfers do not affect safe balance
        if ($this->conversion_transfer_id || $this->conversion_in_account_id || $this->account_to_id) {
            Log::info("❌ shouldAffectSafeBalance: false (conversion or account transfer)");
            return false;
        }

        // Transactions linked to deals do not affect safe balance individually
        if ($this->safe_deal_id || $this->changerdeal_id || $this->withdrawbank_id || $this->safe_deals_revenue_id) {
            Log::info("❌ shouldAffectSafeBalance: false (part of a deal)");
            return false;
        }

        Log::info("✅ shouldAffectSafeBalance: true");
        return true;
    }

    /**
     * دریافت آخرین safe_balance واقعی از جدول‌های صندوق/بانک (برای مقدار اولیه)
     * این متد فقط در زمان ایجاد اولین ژورنال استفاده می‌شود و توسط Livewire مدیریت می‌شود
     */
    private function getRealAccountBalance(string $currency, string $accountType, int $adminId): float
    {
        if ($accountType === 'نقدی') {
            return (float)(CurrencySafe::where('admin_id', $adminId)->value($currency) ?? 0);
        }
        if ($accountType === 'بانکی') {
            return (float)(BankAccount::where('admin_id', $adminId)->value($currency) ?? 0);
        }
        return 0;
    }

    // =======================
    // مدیریت ژورنال‌ها
    // =======================

    /**
     * ایجاد ژورنال برای تراکنش جدید
     */
    public function createJournal()
    {
        try {
            $adminId = $this->admin_id;

            // محاسبه cumulative balance مشتری قبل از این تراکنش
            $balanceBefore = (float) Journals::where('customer_id', $this->customer_id)
                ->where('currency', $this->currency)
                ->where('account_type', $this->account_type)
                ->where('admin_id', $adminId)
                ->where('id', '<>', $this->id)
                ->sum(DB::raw("CASE WHEN type='رسید' THEN amount WHEN type='برد' THEN -amount ELSE 0 END"));

            $signed = $this->signed_amount;
            $balanceAfter = $balanceBefore + $signed;

            // آخرین ژورنال برای این ارز/حساب/ادمین
            $lastJournal = Journals::where('admin_id', $adminId)
                ->where('currency', $this->currency)
                ->where('account_type', $this->account_type)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->first();

            $safeBalance = $lastJournal ? (float)$lastJournal->safe_balance
                : $this->getRealAccountBalance($this->currency, $this->account_type, $adminId);

            if ($this->shouldAffectSafeBalance()) {
                $safeBalance += $signed;
            }

            $journal = Journals::create([
                'transaction_id' => $this->id,
                'customer_id'    => $this->customer_id,
                'user_id'        => $this->user_id,
                'admin_id'       => $adminId,
                'currency'       => $this->currency,
                'account_type'   => $this->account_type,
                'type'           => $this->type,
                'amount'         => $this->amount,
                'balance'        => $balanceAfter,
                'safe_balance'   => $safeBalance,
                'description'    => $this->description,
                'date'           => $this->date
            ]);

            Log::info("✅ Journal created [ID:{$journal->id}] for Transaction {$this->id}", [
                'balance'      => $balanceAfter,
                'safe_balance' => $safeBalance,
            ]);
        } catch (\Exception $e) {
            Log::error("❌ createJournal failed for transaction {$this->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * بازسازی یک تراکنش قدیمی از روی ژورنال
     */
    private function reconstructOldTransaction(Journals $journal): Transaction
    {
        $oldTx = clone $this;
        $oldTx->id            = $this->id;
        $oldTx->customer_id   = $journal->customer_id;
        $oldTx->currency      = $journal->currency;
        $oldTx->account_type  = $journal->account_type;
        $oldTx->amount        = $journal->amount;
        $oldTx->type          = $journal->type;
        $oldTx->date          = $journal->date;
        $oldTx->admin_id      = $journal->admin_id;
        $oldTx->user_id       = $journal->user_id;
        $oldTx->external_transaction_id = $this->getOriginal('external_transaction_id');
        $oldTx->conversion_transfer_id  = $this->getOriginal('conversion_transfer_id');
        $oldTx->conversion_in_account_id = $this->getOriginal('conversion_in_account_id');
        $oldTx->account_to_id           = $this->getOriginal('account_to_id');
        if ($journal->customer_id) {
            $oldTx->customer = Customer::find($journal->customer_id);
        }
        $oldTx->raw_date = $journal->getRawOriginal('date');
        return $oldTx;
    }




    public function updateJournal()
    {
        $this->disableSafeBalance = true;
        $oldJournal = Journals::where('transaction_id', $this->id)->first();
        if (!$oldJournal) {
            $this->createJournal();
            return;
        }

        $oldTx = $this->reconstructOldTransaction($oldJournal);
        $currencyChanged = ($this->currency !== $oldTx->currency);
        $this->preserveSafe = $currencyChanged; // برای استفاده در رویداد deleting

        DB::connection('sarafi')->transaction(function () use ($oldJournal, $oldTx) {
            if ($this->mustRecreateJournal($oldTx)) {
                Log::info("♻️ Journal recreation for TX {$this->id}: fields changed.");

                // 1️⃣ حذف ژورنال قدیمی
                $oldJournalId = $oldJournal->id;
                $oldJournal->delete();
                Log::info("🗑️ Old journal deleted for TX {$this->id}.");

                $this->adjustJournalsAfterDelete(
                    $oldTx,
                    $oldJournalId,
                    $oldTx->shouldAffectSafeBalance(),
                    recalcSafe: $oldTx->shouldAffectSafeBalance(),
                    recalcCustomer: true
                );

                // 3️⃣ ایجاد ژورنال جدید
                $this->createJournal();

                // 4️⃣ بازسازی زنجیره ارز جدید (اعمال اثر تراکنش جدید)
                $this->adjustJournalsForNewTransaction(
                    recalcSafe: true,   // true چون می‌خواهیم safe_balance ژورنال‌ها درست باشد
                    recalcCustomer: true
                );
            } else {
                // تغییر فقط مبلغ/نوع (بدون تغییر فیلدهای کلیدی)
                $this->applyDiffUpdate($oldJournal, $oldTx);
            }
        });

        Log::info("✅ Journal updated for transaction {$this->id}");
    }
    /**
     * بررسی نیاز به بازآفرینی کامل ژورنال
     */
    private function mustRecreateJournal(Transaction $oldTx): bool
    {
        if ($this->date->toDateString() !== $oldTx->date->toDateString()) {
            Log::debug("mustRecreateJournal: date changed");
            return true;
        }
        if ($this->customer_id != $oldTx->customer_id) {
            Log::debug("mustRecreateJournal: customer changed");
            return true;
        }
        if ($this->currency !== $oldTx->currency || $this->account_type !== $oldTx->account_type) {
            Log::debug("mustRecreateJournal: currency/account_type changed");
            return true;
        }
        if ($this->shouldAffectSafeBalance() !== $oldTx->shouldAffectSafeBalance()) {
            Log::debug("mustRecreateJournal: affectSafeBalance flag changed");
            return true;
        }
        return false;
    }

    /**
     * اعمال تغییرات کوچک (فقط مبلغ/نوع) با محاسبه diff
     */
    private function applyDiffUpdate($oldJournal, $oldTx)
    {
        $oldSigned = $oldTx->signed_amount;
        $newSigned = $this->signed_amount;
        $diff = $newSigned - $oldSigned;

        if ($diff == 0) {
            $oldJournal->update([
                'description' => $this->description,
                'date'        => $this->getRawOriginal('date'),
            ]);
            Log::info("ℹ️ Journal diff=0, only metadata updated for TX {$this->id}");
            return;
        }

        // به‌روزرسانی ژورنال خود تراکنش
        $oldJournal->update([
            'amount'       => $this->amount,
            'type'         => $this->type,
            'description'  => $this->description,
            'date'         => $this->getRawOriginal('date'),
            'balance'      => $oldJournal->balance + $diff,
            'safe_balance' => $oldJournal->safe_balance + $diff,
        ]);

        Log::info("📊 Journal self updated: diff={$diff}");

        // اعمال diff روی safe_balance ژورنال‌های بعدی (با محاسبه ترتیبی)
        if ($this->shouldAffectSafeBalance()) {
            $this->recalcSafeBalanceFromPoint(
                $oldJournal->admin_id,
                $this->currency,
                $this->account_type,
                $oldJournal->date,
                $oldJournal->id
            );
        }

        // اعمال diff روی cumulative balance مشتری (با محاسبه ترتیبی)
        $this->recalcCustomerBalanceFromPoint(
            $oldJournal->admin_id,
            $this->customer_id,
            $this->currency,
            $this->account_type,
            $oldJournal->date,
            $oldJournal->id
        );

        // ❌ عدم به‌روزرسانی جداول صندوق/بانک – Livewire مسئول این کار است
    }

    // =======================
    // بازسازی ترتیبی (مجزا برای صندوق و مشتری)
    // =======================

    /**
     * بازسازی safe_balance همه ژورنال‌های بعد از یک نقطه مشخص (بر اساس تاریخ و journal.id)
     */
    private function recalcSafeBalanceFromPoint(
        int $adminId,
        string $currency,
        string $accountType,
        string $startDate,
        int $startJournalId
    ): void {
        Log::info("🔄 recalcSafeBalanceFromPoint: admin={$adminId}, currency={$currency}, account={$accountType}, startDate={$startDate}, startJournalId={$startJournalId}");

        $previousJournal = Journals::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->where(function ($q) use ($startDate, $startJournalId) {
                $q->where('date', '<', $startDate)
                    ->orWhere(function ($q2) use ($startDate, $startJournalId) {
                        $q2->where('date', '=', $startDate)
                            ->where('id', '<', $startJournalId);
                    });
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->first();

        $runningBalance = $previousJournal ? (float)$previousJournal->safe_balance : 0;
        Log::info("Starting safe_balance: {$runningBalance} (from journal ID=" . ($previousJournal->id ?? 'none') . ")");

        $journals = Journals::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->where(function ($q) use ($startDate, $startJournalId) {
                $q->where('date', '>', $startDate)
                    ->orWhere(function ($q2) use ($startDate, $startJournalId) {
                        $q2->where('date', '=', $startDate)
                            ->where('id', '>=', $startJournalId);
                    });
            })
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        Log::info("Found {$journals->count()} journals to recalculate safe_balance");

        foreach ($journals as $journal) {
            $oldBalance = $journal->safe_balance;

            if ($journal->type === 'رسید') {
                $runningBalance += $journal->amount;
            } else {
                $runningBalance -= $journal->amount;
            }

            if ((float)$oldBalance !== (float)$runningBalance) {
                $journal->update(['safe_balance' => $runningBalance]);
                Log::info("✅ Journal ID={$journal->id}: safe_balance {$oldBalance} → {$runningBalance}");
            } else {
                Log::info("ℹ️ Journal ID={$journal->id}: no change ({$runningBalance})");
            }
        }
    }

    /**
     * بازسازی cumulative balance مشتری برای همه ژورنال‌های بعد از یک نقطه
     */
    private function recalcCustomerBalanceFromPoint(
        int $adminId,
        int $customerId,
        string $currency,
        string $accountType,
        string $startDate,
        int $startJournalId
    ): void {
        Log::info("🔄 recalcCustomerBalanceFromPoint: customer={$customerId}, currency={$currency}, account={$accountType}");

        $previousJournal = Journals::where('admin_id', $adminId)
            ->where('customer_id', $customerId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->where(function ($q) use ($startDate, $startJournalId) {
                $q->where('date', '<', $startDate)
                    ->orWhere(function ($q2) use ($startDate, $startJournalId) {
                        $q2->where('date', '=', $startDate)
                            ->where('id', '<', $startJournalId);
                    });
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->first();

        $runningBalance = $previousJournal ? (float)$previousJournal->balance : 0;

        $journals = Journals::where('admin_id', $adminId)
            ->where('customer_id', $customerId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->where(function ($q) use ($startDate, $startJournalId) {
                $q->where('date', '>', $startDate)
                    ->orWhere(function ($q2) use ($startDate, $startJournalId) {
                        $q2->where('date', '=', $startDate)
                            ->where('id', '>=', $startJournalId);
                    });
            })
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        foreach ($journals as $journal) {
            $oldBalance = $journal->balance;

            if ($journal->type === 'رسید') {
                $runningBalance += $journal->amount;
            } else {
                $runningBalance -= $journal->amount;
            }

            if ((float)$oldBalance !== (float)$runningBalance) {
                $journal->update(['balance' => $runningBalance]);
                Log::info("✅ Journal ID={$journal->id} balance: {$oldBalance} → {$runningBalance}");
            }
        }
    }

    // =======================
    // حذف تراکنش
    // =======================

    /**
     * حذف اثر یک تراکنش از ژورنال‌های بعدی (بازسازی ترتیبی)
     *
     * @param Transaction $deletedTx
     * @param int $deletedJournalId
     * @param bool $shouldAffectSafe
     * @param bool $recalcSafe  آیا safe_balance بازسازی شود؟
     * @param bool $recalcCustomer آیا balance مشتری بازسازی شود؟
     */
    public function adjustJournalsAfterDelete(
        Transaction $deletedTx,
        int $deletedJournalId,
        bool $shouldAffectSafe,
        bool $recalcSafe = true,
        bool $recalcCustomer = true
    ): void {
        $adminId    = $deletedTx->admin_id;
        $signed     = $deletedTx->signed_amount;
        $rawDate    = $deletedTx->getRawOriginal('date');

        Log::info("⚙️ adjustJournalsAfterDelete for TX {$deletedTx->id}", [
            'signed'        => $signed,
            'should_affect' => $shouldAffectSafe,
            'raw_date'      => $rawDate,
            'deleted_journal_id' => $deletedJournalId,
            'recalcSafe'    => $recalcSafe,
            'recalcCustomer' => $recalcCustomer,
        ]);

        if ($shouldAffectSafe && $recalcSafe) {
            $this->recalcSafeBalanceFromPoint(
                $adminId,
                $deletedTx->currency,
                $deletedTx->account_type,
                $rawDate,
                $deletedJournalId
            );
        }

        if ($recalcCustomer) {
            $this->recalcCustomerBalanceFromPoint(
                $adminId,
                $deletedTx->customer_id,
                $deletedTx->currency,
                $deletedTx->account_type,
                $rawDate,
                $deletedJournalId
            );
        }
    }

    /**
     * اعمال اثر یک تراکنش جدید روی ژورنال‌های بعدی (بازسازی ترتیبی)
     *
     * @param bool $recalcSafe
     * @param bool $recalcCustomer
     */
    public function adjustJournalsForNewTransaction(bool $recalcSafe = true, bool $recalcCustomer = true): void
    {
        $newJournal = Journals::where('transaction_id', $this->id)->first();
        if (!$newJournal) {
            Log::error("❌ adjustJournalsForNewTransaction: Journal not found for TX {$this->id}");
            return;
        }

        $adminId  = $this->admin_id;
        $rawDate  = $this->getRawOriginal('date');
        $newJournalId = $newJournal->id;

        Log::info("⚙️ adjustJournalsForNewTransaction for TX {$this->id}", [
            'recalcSafe'    => $recalcSafe,
            'recalcCustomer' => $recalcCustomer,
        ]);

        if ($this->shouldAffectSafeBalance() && $recalcSafe) {
            $this->recalcSafeBalanceFromPoint(
                $adminId,
                $this->currency,
                $this->account_type,
                $rawDate,
                $newJournalId
            );
        }

        if ($recalcCustomer) {
            $this->recalcCustomerBalanceFromPoint(
                $adminId,
                $this->customer_id,
                $this->currency,
                $this->account_type,
                $rawDate,
                $newJournalId
            );
        }
    }

    // =======================
    // Model Events
    // =======================

    protected static function booted()
    {
        static::created(function ($model) {
            DB::connection('sarafi')->transaction(function () use ($model) {
                $model->createJournal();
                $model->sendWhatsApp();
                // به‌روزرسانی صندوق‌ها توسط Livewire انجام می‌شود
            });
        });

        static::updated(function ($model) {
            DB::connection('sarafi')->transaction(function () use ($model) {
                $model->logToTrash('ویرایش');
                $model->updateJournal();
                // ❌ عدم به‌روزرسانی جداول صندوق/بانک
            });
        });

        static::deleting(function ($model) {
            $model->load('customer');
            $journal = Journals::where('transaction_id', $model->id)->first();

            $model->tempDeleteInfo = [
                'admin_id'      => $model->admin_id,
                'customer_id'   => $model->customer_id,
                'currency'      => $model->currency,
                'account_type'  => $model->account_type,
                'date'          => $model->date,
                'raw_date'      => $model->getRawOriginal('date'),
                'id'            => $model->id,
                'journal_id'    => $journal?->id,
                'signed_amount' => $model->signed_amount,
                'should_affect' => $model->shouldAffectSafeBalance(),
                'preserve_safe' => $model->preserveSafe,
            ];
            Log::info("🗑️ [deleting] Transaction {$model->id} info stored.", $model->tempDeleteInfo);
        });

        static::deleted(function ($model) {
            $model->disableSafeBalance = true;
            DB::connection('sarafi')->transaction(function () use ($model) {
                $model->logToTrash('حذف');

                Journals::where('transaction_id', $model->id)->delete();
                Log::info("🗑️ Journal deleted for transaction {$model->id}");

                $info = $model->tempDeleteInfo ?? null;
                if ($info && $info['should_affect'] && $info['journal_id']) {
                    $hasLater = Journals::where('admin_id', $info['admin_id'])
                        ->where('currency', $info['currency'])
                        ->where('account_type', $info['account_type'])
                        ->where(function ($q) use ($info) {
                            $q->where('date', '>', $info['raw_date'])
                                ->orWhere(function ($q2) use ($info) {
                                    $q2->where('date', '=', $info['raw_date'])
                                        ->where('id', '>', $info['journal_id']);
                                });
                        })
                        ->exists();

                    if ($hasLater) {
                        $model->adjustJournalsAfterDelete(
                            $model,
                            $info['journal_id'],
                            $info['should_affect'],
                            recalcSafe: $info['should_affect'],
                            recalcCustomer: true
                        );
                    } else {
                        Log::info("⏭️ No later journals to adjust for TX {$model->id}");
                    }
                }

                // ❌ عدم به‌روزرسانی جداول صندوق/بانک – Livewire مسئول این کار است
            });
        });
    }

    /**
     * ثبت عملیات در جدول Trash
     */
    private function logToTrash(string $action)
    {
        $userId = 0;
        $adminId = 0;

        if (app()->bound('auth')) {
            $user = \Illuminate\Support\Facades\Auth::guard('sarafi')->user();
            if ($user) {
                $userId = $user->id;
                $adminId = $user->admin_id ?? $userId;
            }
        }

        if (!$userId) {
            $userId = $this->user_id ?? 0;
        }
        if (!$adminId) {
            $adminId = $this->admin_id ?? $userId;
        }

        Trash::create([
            'document_type'        => 'رسید / برد صندوق',
            'record_id'           => $this->id,
            'action'              => $action,
            'document_discription' => $this->description,
            'old_data'           => $action === 'ویرایش' ? $this->getOriginal() : null,
            'new_data'           => $action === 'ویرایش' ? $this->getAttributes() : ($action === 'حذف' ? $this->getAttributes() : null),
            'registered_user'    => $this->user_id,
            'user_id'            => $userId,
            'admin_id'           => $adminId,
        ]);
    }

    // =======================
    // WhatsApp
    // =======================

    public function sendWhatsApp()
    {
        try {
            $customer = $this->customer;
            if (!$customer || !$customer->whatsapp_number) {
                Log::info("WhatsApp not sent – no customer/phone");
                return;
            }

            $phone = preg_replace('/[^0-9]/', '', $customer->whatsapp_number);
            if (strlen($phone) < 10) {
                Log::warning("Invalid WhatsApp number: {$phone}");
                return;
            }

            $balance = Journals::where('transaction_id', $this->id)->value('balance') ?? $this->amount;

            WhatsAppService::sendTransaction(
                $phone,
                [
                    'exchange_name'      => $this->user->sarafi_name ?? '-',
                    'account_number'     => $customer->fullname ?? '-',
                    'amount'            => (string)($this->amount ?? '-'),
                    'currency'          => $this->getCurrencyNameAttribute() ?? '-',
                    'transaction_type'  => $this->getTypeNameAttribute() ?? '-',
                    'transaction_date'  => $this->date ? $this->date->format('Y-m-d H:i') : '-',
                    'balance'           => (string)$balance,
                    'exchange_contact'  => (string)($this->user->phone ?? '-'),
                ]
            );

            Log::info("📱 WhatsApp sent for transaction {$this->id}");
        } catch (\Exception $e) {
            Log::error("WhatsApp error for TX {$this->id}: " . $e->getMessage());
        }
    }
}
