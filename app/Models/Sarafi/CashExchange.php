<?php

namespace App\Models\Sarafi;

use App\Models\Sarafi\Journals;
use App\Models\Sarafi\Trash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashExchange extends Model
{
    protected $connection = 'sarafi';
    protected $table = 'cash_exchange';
    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($m) {
            Log::info("=== CashExchange CREATED ID={$m->id} ===");
            $m->createJournals();
        });

        static::updated(function ($m) {
            Log::info("=== CashExchange UPDATED ID={$m->id} ===");
            $m->logTrash('ویرایش');
            $m->handleUpdate();
        });

        static::deleting(function ($m) {
            // ذخیره اطلاعات قبل از حذف
            $m->beforeDeleteInfo = $m->getDeleteInfo();
        });

        static::deleted(function ($m) {
            Log::info("=== CashExchange DELETED ID={$m->id} ===");
            $m->logTrash('حذف');
            $m->rebuildAfterDelete();
        });
    }

    /* ================= اطلاعات قبل از حذف ================= */

    private $beforeDeleteInfo = [];

    private function getDeleteInfo(): array
    {
        $info = [];

        // پیدا کردن همه ژورنال‌های مربوط به این تراکنش
        $journals = Journals::where('buysell_id', $this->id)->get();

        if ($journals->isEmpty()) {
            return $info;
        }

        $adminId = $journals->first()->admin_id;
        $info['admin_id'] = $adminId;

        // برای هر ارز، اولین ژورنال بعدی را پیدا کن
        foreach ($journals->groupBy('currency') as $currency => $currencyJournals) {
            // قدیمی‌ترین ژورنال این ارز
            $firstJournal = $currencyJournals->sortBy(['date', 'id'])->first();

            // اولین ژورنال بعد از این ژورنال‌ها
            $firstAfter = Journals::where('admin_id', $adminId)
                ->where('currency', $currency)
                ->where('account_type', 'نقدی')
                ->where(function ($q) use ($firstJournal) {
                    $q->where('date', '>', $firstJournal->date)
                        ->orWhere(function ($q2) use ($firstJournal) {
                            $q2->where('date', '=', $firstJournal->date)
                                ->where('id', '>', $firstJournal->id);
                        });
                })
                ->orderBy('date')
                ->orderBy('id')
                ->first();

            $info['currencies'][$currency] = [
                'first_journal_date' => $firstJournal->date,
                'first_journal_id' => $firstJournal->id,
                'first_after_date' => $firstAfter ? $firstAfter->date : null,
                'first_after_id' => $firstAfter ? $firstAfter->id : null,
                'has_later_journals' => !is_null($firstAfter)
            ];
        }

        return $info;
    }

    /* ================= TRASH ================= */

    private function logTrash(string $action): void
    {
        try {
            $user = Auth::guard('sarafi')->user();

            Trash::create([
                'document_type' => 'تبدیل ارز نقدی',
                'record_id' => $this->id,
                'action' => $action,
                'document_discription' => $this->getDescription(),
                'registered_user' => $this->user_id ?? $user->id,
                'old_data' => $action === 'ویرایش' ? $this->getOriginal() : null,
                'new_data' => $action === 'ویرایش' ? $this->getAttributes() : null,
                'user_id' => $user->id,
                'admin_id' => $user->admin_id ?? $user->id,
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    /* ================= CREATE ================= */

    private function createJournals(): void
    {
        DB::transaction(function () {
            Log::info("Creating journals for CashExchange ID={$this->id}");
            $this->createJournal('برد', $this->from_currency, $this->amount);
            $this->createJournal('رسید', $this->to_currency, $this->eq_amount);
        });
    }

    private function createJournal(string $type, string $currency, float $amount): void
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        Log::info("Creating journal: type={$type}, currency={$currency}, amount={$amount}");

        // پیدا کردن آخرین ژورنال این ارز (آخرین موجودی)
        $lastJournal = Journals::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', 'نقدی')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->first();

        $before = $lastJournal ? (float) $lastJournal->safe_balance : 0;

        Log::info("Last journal before: " . ($lastJournal ? "ID={$lastJournal->id}, safe_balance={$lastJournal->safe_balance}" : "None"));

        $after = $type === 'رسید'
            ? $before + $amount
            : $before - $amount;

        $journal = Journals::create([
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'currency' => $currency,
            'type' => $type,
            'account_type' => 'نقدی',
            'amount' => $amount,
            'balance'=>0,
            'safe_balance' => $after,
            'date' => $this->date,
            'buysell_id' => $this->id,
            'is_sell_table' => 1,
        ]);

        Log::info("Created journal ID={$journal->id}: before={$before}, after={$after}");
    }

    /* ================= UPDATE LOGIC ================= */

    private function handleUpdate(): void
    {
        DB::transaction(function () {
            Log::info("=== HANDLE UPDATE START ===");

            $original = $this->getOriginal();
            Log::info("Original: amount={$original['amount']}, eq_amount={$original['eq_amount']}");
            Log::info("New: amount={$this->amount}, eq_amount={$this->eq_amount}");

            $amountDiff = $this->amount - $original['amount'];
            $eqAmountDiff = $this->eq_amount - $original['eq_amount'];

            Log::info("Differences: amountDiff={$amountDiff}, eqAmountDiff={$eqAmountDiff}");

            if ($amountDiff != 0) {
                $this->updateCurrencyJournals(
                    $this->from_currency,
                    'برد',
                    $original['amount'],
                    $this->amount,
                    $amountDiff
                );
            }

            if ($eqAmountDiff != 0) {
                $this->updateCurrencyJournals(
                    $this->to_currency,
                    'رسید',
                    $original['eq_amount'],
                    $this->eq_amount,
                    $eqAmountDiff
                );
            }

            // در انتها، آپدیت صندوق
            $this->updateCurrencySafe();

            Log::info("=== HANDLE UPDATE COMPLETE ===");
        });
    }

    private function updateCurrencyJournals(
        string $currency,
        string $type,
        float $oldAmount,
        float $newAmount,
        float $diff
    ): void {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        Log::info("=== UPDATE CURRENCY JOURNALS START ===");
        Log::info("Currency: {$currency}, Type: {$type}, Old: {$oldAmount}, New: {$newAmount}, Diff: {$diff}");

        // پیدا کردن ژورنال این معامله
        $journal = Journals::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('buysell_id', $this->id)
            ->where('type', $type)
            ->first();

        if (!$journal) {
            Log::warning("Journal not found for buysell_id={$this->id}, currency={$currency}, type={$type}");
            return;
        }

        Log::info("Found journal ID={$journal->id}, current safe_balance={$journal->safe_balance}");

        // آپدیت مقدار ژورنال
        $journal->update([
            'amount' => $newAmount,
            'safe_balance' => $journal->safe_balance + ($type === 'رسید' ? $diff : -$diff)
        ]);

        Log::info("Updated journal: amount={$newAmount}, safe_balance={$journal->safe_balance}");

        // بازسازی همه ژورنال‌های بعدی
        $this->recalculateAfterJournal($adminId, $currency, $journal->date, $journal->id);
    }

    /* ================= DELETE LOGIC ================= */

    private function rebuildAfterDelete(): void
    {
        DB::transaction(function () {
            Log::info("=== REBUILD AFTER DELETE START ===");
            Log::info("CashExchange ID={$this->id}");

            // استفاده از اطلاعات ذخیره شده قبل از حذف
            if (empty($this->beforeDeleteInfo)) {
                Log::error("No beforeDeleteInfo available!");
                // روش جایگزین: پیدا کردن ژورنال‌های مربوطه
                $this->rebuildUsingAlternativeMethod();
                return;
            }

            $adminId = $this->beforeDeleteInfo['admin_id'] ?? null;
            if (!$adminId) {
                Log::error("No admin_id in beforeDeleteInfo!");
                $this->rebuildUsingAlternativeMethod();
                return;
            }

            Log::info("Admin ID from beforeDeleteInfo: {$adminId}");

            // حذف ژورنال‌های این معامله
            $deletedCount = Journals::where('buysell_id', $this->id)->delete();
            Log::info("Deleted {$deletedCount} journals for buysell_id={$this->id}");

            // برای هر ارز بازسازی کن
            foreach (($this->beforeDeleteInfo['currencies'] ?? []) as $currency => $currencyInfo) {
                Log::info("=== Recalculating for currency: {$currency} ===");
                $this->recalculateCurrencyAfterDelete($adminId, $currency, $currencyInfo);
            }

            // در انتها، آپدیت صندوق
            $this->updateCurrencySafe();

            Log::info("=== REBUILD AFTER DELETE COMPLETE ===");
        });
    }

    /**
     * روش جایگزین برای زمانی که beforeDeleteInfo در دسترس نیست
     */
    private function rebuildUsingAlternativeMethod(): void
    {
        Log::info("=== REBUILD USING ALTERNATIVE METHOD START ===");

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // حذف ژورنال‌های این معامله
        $deletedCount = Journals::where('buysell_id', $this->id)->delete();
        Log::info("Deleted {$deletedCount} journals for buysell_id={$this->id}");

        // پیدا کردن همه ارزهایی که ممکن است تحت تأثیر قرار گرفته باشند
        $currencies = ['afn', 'usd']; // ارزهای موجود در این معامله

        foreach ($currencies as $currency) {
            Log::info("=== Recalculating for currency: {$currency} ===");
            $this->recalculateFromPreviousRow($adminId, $currency);
        }

        // در انتها، آپدیت صندوق
        $this->updateCurrencySafe();

        Log::info("=== REBUILD USING ALTERNATIVE METHOD COMPLETE ===");
    }

    /**
     * بازسازی یک ارز خاص بعد از حذف
     */
    private function recalculateCurrencyAfterDelete(int $adminId, string $currency, array $currencyInfo): void
    {
        Log::info("=== RECALCULATE CURRENCY AFTER DELETE START ===");
        Log::info("Currency: {$currency}");
        Log::info("Currency Info: " . json_encode($currencyInfo));

        // اگر ژورنال بعدی داریم، از آنجا شروع کنیم
        if ($currencyInfo['has_later_journals']) {
            $startDate = $currencyInfo['first_after_date'];
            $startId = $currencyInfo['first_after_id'];

            Log::info("Starting from first journal after deletion: date={$startDate}, id={$startId}");

            // پیدا کردن آخرین ژورنال قبل از startDate/startId
            $previousJournal = Journals::where('admin_id', $adminId)
                ->where('currency', $currency)
                ->where('account_type', 'نقدی')
                ->where(function ($q) use ($startDate, $startId) {
                    $q->where('date', '<', $startDate)
                        ->orWhere(function ($q2) use ($startDate, $startId) {
                            $q2->where('date', '=', $startDate)
                                ->where('id', '<', $startId);
                        });
                })
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->first();

            if ($previousJournal) {
                $runningBalance = (float) $previousJournal->safe_balance;
                Log::info("Previous journal: ID={$previousJournal->id}, balance={$runningBalance}");
            } else {
                // هیچ ژورنال قبلی نداریم، از صفر شروع می‌کنیم
                $runningBalance = 0;
                Log::info("No previous journal, starting from 0");
            }

            // همه ژورنال‌های از start به بعد
            $journalsToRecalculate = Journals::where('admin_id', $adminId)
                ->where('currency', $currency)
                ->where('account_type', 'نقدی')
                ->where(function ($q) use ($startDate, $startId) {
                    $q->where('date', '>', $startDate)
                        ->orWhere(function ($q2) use ($startDate, $startId) {
                            $q2->where('date', '=', $startDate)
                                ->where('id', '>=', $startId);
                        });
                })
                ->orderBy('date')
                ->orderBy('id')
                ->get();

            Log::info("Found {$journalsToRecalculate->count()} journals to recalculate");

            $counter = 0;
            foreach ($journalsToRecalculate as $journal) {
                $counter++;
                $oldBalance = $journal->safe_balance;

                // محاسبه موجودی جدید
                if ($journal->type === 'رسید') {
                    $runningBalance += $journal->amount;
                } else {
                    $runningBalance -= $journal->amount;
                }

                // بروزرسانی اگر تغییر کرده باشد
                if ((float) $oldBalance !== (float) $runningBalance) {
                    Log::info("Journal #{$counter} ID={$journal->id}: {$oldBalance} -> {$runningBalance} " .
                        "(Type: {$journal->type}, Amount: {$journal->amount})");
                    $journal->update(['safe_balance' => $runningBalance]);
                } else {
                    Log::info("Journal #{$counter} ID={$journal->id}: No change needed ({$runningBalance})");
                }
            }
        } else {
            // هیچ ژورنال بعدی نداریم
            Log::info("No journals after deletion for currency {$currency}");
        }

        Log::info("=== RECALCULATE CURRENCY AFTER DELETE COMPLETE ===");
    }

    /**
     * ⭐ بازسازی از safe_balance ردیف قبلی
     */
    private function recalculateFromPreviousRow(int $adminId, string $currency): void
    {
        Log::info("=== RECALCULATE FROM PREVIOUS ROW START ===");
        Log::info("Params: adminId={$adminId}, currency={$currency}");

        // همه ژورنال‌های این ارز به ترتیب
        $journals = Journals::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', 'نقدی')
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        Log::info("Total journals for {$currency}: {$journals->count()}");

        if ($journals->isEmpty()) {
            Log::info("No journals found for {$currency}");
            return;
        }

        // پیدا کردن اولین ژورنال
        $firstJournal = $journals->first();
        Log::info("First journal: ID={$firstJournal->id}, date={$firstJournal->date}");

        // پیدا کردن آخرین ژورنال قبل از اولین ژورنال
        $previous = Journals::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', 'نقدی')
            ->where(function ($q) use ($firstJournal) {
                $q->where('date', '<', $firstJournal->date)
                    ->orWhere(function ($q2) use ($firstJournal) {
                        $q2->where('date', '=', $firstJournal->date)
                            ->where('id', '<', $firstJournal->id);
                    });
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->first();

        $runningBalance = $previous ? (float) $previous->safe_balance : 0;

        Log::info("Starting balance: {$runningBalance} " . ($previous ? "(from journal ID={$previous->id})" : "(from zero)"));

        $counter = 0;

        // محاسبه مجدد همه ژورنال‌ها
        foreach ($journals as $journal) {
            $counter++;
            $oldBalance = $journal->safe_balance;

            // محاسبه موجودی جدید
            if ($journal->type === 'رسید') {
                $runningBalance += $journal->amount;
            } else {
                $runningBalance -= $journal->amount;
            }

            // بررسی و بروزرسانی اگر نیاز باشد
            if ((float) $oldBalance !== (float) $runningBalance) {
                Log::info("Journal #{$counter} ID={$journal->id}: {$oldBalance} -> {$runningBalance} (Type: {$journal->type}, Amount: {$journal->amount})");
                $journal->update(['safe_balance' => $runningBalance]);
            } else {
                Log::info("Journal #{$counter} ID={$journal->id}: No change needed ({$runningBalance})");
            }
        }

        Log::info("Final running balance: {$runningBalance}");
        Log::info("=== RECALCULATE FROM PREVIOUS ROW COMPLETE ===");
    }

    /* ================= بازسازی بعد از ویرایش ================= */

    private function recalculateAfterJournal(int $adminId, string $currency, string $date, int $id): void
    {
        Log::info("=== RECALCULATE AFTER JOURNAL START ===");
        Log::info("Params: adminId={$adminId}, currency={$currency}, date={$date}, id={$id}");

        // همه ژورنال‌های بعد از این ژورنال
        $journalsToRecalculate = Journals::where('admin_id', $adminId)
            ->where('currency', $currency)
            ->where('account_type', 'نقدی')
            ->where(function ($q) use ($date, $id) {
                $q->where('date', '>', $date)
                    ->orWhere(function ($q2) use ($date, $id) {
                        $q2->where('date', '=', $date)
                            ->where('id', '>', $id);
                    });
            })
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        if ($journalsToRecalculate->isEmpty()) {
            Log::info("No journals to recalculate after journal ID={$id}");
            return;
        }

        Log::info("Found {$journalsToRecalculate->count()} journals to recalculate");

        // ژورنال فعلی
        $currentJournal = Journals::find($id);
        $runningBalance = $currentJournal ? (float) $currentJournal->safe_balance : 0;

        if (!$currentJournal) {
            Log::warning("Current journal ID={$id} not found!");
            return;
        }

        Log::info("Starting from journal ID={$id} with balance={$runningBalance}");

        $counter = 0;
        foreach ($journalsToRecalculate as $journal) {
            $counter++;
            $oldBalance = $journal->safe_balance;

            // محاسبه موجودی جدید
            if ($journal->type === 'رسید') {
                $runningBalance += $journal->amount;
            } else {
                $runningBalance -= $journal->amount;
            }

            // بروزرسانی اگر تغییر کرده باشد
            if ((float) $oldBalance !== (float) $runningBalance) {
                Log::info("Journal #{$counter} ID={$journal->id}: {$oldBalance} -> {$runningBalance} " .
                    "(Type: {$journal->type}, Amount: {$journal->amount})");
                $journal->update(['safe_balance' => $runningBalance]);
            } else {
                Log::info("Journal #{$counter} ID={$journal->id}: No change needed ({$runningBalance})");
            }
        }

        Log::info("=== RECALCULATE AFTER JOURNAL COMPLETE ===");
    }

    /* ================= آپدیت صندوق ================= */

    /**
     * آپدیت جدول currency_safe بر اساس آخرین ژورنال هر ارز
     */
    private function updateCurrencySafe(): void
    {
        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            Log::info("=== UPDATE CURRENCY SAFE START ===");

            // پیدا کردن تمام ارزهای موجود
            $currencies = Journals::where('admin_id', $adminId)
                ->where('account_type', 'نقدی')
                ->distinct()
                ->pluck('currency');

            $safeData = [];

            foreach ($currencies as $currency) {
                // آخرین ژورنال این ارز
                $lastJournal = Journals::where('admin_id', $adminId)
                    ->where('currency', $currency)
                    ->where('account_type', 'نقدی')
                    ->orderByDesc('date')
                    ->orderByDesc('id')
                    ->first();

                if ($lastJournal) {
                    $safeData[strtolower($currency)] = $lastJournal->safe_balance;
                    Log::info("Currency {$currency}: last journal ID={$lastJournal->id}, safe_balance={$lastJournal->safe_balance}");
                } else {
                    $safeData[strtolower($currency)] = 0;
                    Log::info("Currency {$currency}: no journals found, setting to 0");
                }
            }

            // آپدیت یا ایجاد رکورد در currency_safe
            DB::connection('sarafi')->table('currency_safe')->updateOrInsert(
                ['admin_id' => $adminId],
                array_merge(['admin_id' => $adminId, 'user_id' => $user->id], $safeData)
            );

            Log::info("Currency safe updated successfully");
            Log::info("=== UPDATE CURRENCY SAFE COMPLETE ===");
        } catch (\Throwable $e) {
            Log::error("Error updating currency safe: " . $e->getMessage());
        }
    }

    /* ================= DESCRIPTION HELPERS ================= */

    private function getWithdrawDescription(): string
    {
        return 'تبدیل ارز نقدی: '
            . $this->currencyFa($this->from_currency)
            . ' به '
            . $this->currencyFa($this->to_currency)
            . ' | مبلغ: '
            . number_format($this->amount, 2)
            . ' | نرخ: '
            . number_format($this->exchange_rate, 6);
    }

    private function getReceiveDescription(): string
    {
        return 'دریافت ارز نقدی: '
            . $this->currencyFa($this->to_currency)
            . ' از '
            . $this->currencyFa($this->from_currency)
            . ' | مبلغ: '
            . number_format($this->eq_amount, 2)
            . ' | نرخ: '
            . number_format($this->exchange_rate, 6);
    }

    private function getDescription(): string
    {
        return $this->getWithdrawDescription();
    }

    /* ================= HELPERS ================= */

    private function currencyFa(string $code): string
    {
        return match (strtolower($code)) {
            'usd' => 'دالر',
            'afn' => 'افغانی',
            'irr' => 'ریال ایران',
            'eur' => 'یورو',
            'aed' => 'درهم امارات',
            'try' => 'لیر ترکیه',
            'cny' => 'یوان چین',
            'pkr' => 'روپیه پاکستان',
            'gbp' => 'پوند انگلیس',
            'jpy' => 'ین جاپان',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه هند',
            default => strtoupper($code),
        };
    }

    public function journal()
{
    return $this->hasOne(Journals::class, 'buysell_id', 'id');
}
}
