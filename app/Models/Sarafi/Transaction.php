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
        'external_transaction_id', 'safe_deal_id', 'safe_deals_revenue_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    // =======================
    // Properties for Delete Info
    // =======================
    protected $beforeDeleteInfo = [];

    // =======================
    // Relations
    // =======================
    public function customer() { return $this->belongsTo(Customer::class, 'customer_id'); }
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
    public function Safedeals() { return $this->belongsTo(SafeDeal::class, 'safe_deal_id'); }
    public function changerdeal() { return $this->belongsTo(ChangerDeal::class, 'changerdeal_id'); }
    public function withdrawbank() { return $this->belongsTo(WithdrawsBanks::class, 'withdrawbank_id'); }
    public function safedealsrevenue() { return $this->belongsTo(SafeDealsRevenue::class, 'safe_deals_revenue_id'); }
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

        static::deleting(function ($model) {
            // ذخیره اطلاعات مورد نیاز برای بازسازی بعد از حذف
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            $signedAmount = $model->type === 'رسید' ? $model->amount : -$model->amount;
            $shouldAffect = $model->shouldAffectSafeBalance();

            // اولین ژورنال بعد از این تراکنش
            $firstLaterJournal = Journals::where('admin_id', $adminId)
                ->where('currency', $model->currency)
                ->where('account_type', $model->account_type)
                ->where(function ($q) use ($model) {
                    $q->where('date', '>', $model->date)
                        ->orWhere(function ($q2) use ($model) {
                            $q2->where('date', '=', $model->date)
                                ->where('id', '>', $model->id);
                        });
                })
                ->orderBy('date')
                ->orderBy('id')
                ->first();

            // 🟢 لاگ‌گذاری کامل
            Log::info("🧾 [deleting] Transaction ID={$model->id}, Type={$model->type}, Amount={$model->amount}, Currency={$model->currency}, AccountType={$model->account_type}");
            Log::info("🧾 shouldAffectSafeBalance = " . ($shouldAffect ? 'true' : 'false'));
            Log::info("🧾 signed_amount = {$signedAmount}");
            if ($firstLaterJournal) {
                Log::info("🧾 First later journal: ID={$firstLaterJournal->id}, Date={$firstLaterJournal->date}, SafeBalance={$firstLaterJournal->safe_balance}");
            } else {
                Log::info("🧾 No later journal found for this currency/account_type.");
            }

            $model->beforeDeleteInfo = [
                'admin_id'          => $adminId,
                'currency'          => $model->currency,
                'account_type'      => $model->account_type,
                'customer_id'       => $model->customer_id,
                'signed_amount'     => $signedAmount,
                'should_affect'     => $shouldAffect,
                'date'             => $model->date,
                'id'               => $model->id,
                'first_later_id'   => $firstLaterJournal?->id,
                'first_later_date' => $firstLaterJournal?->date,
                'has_later'        => !is_null($firstLaterJournal),
            ];
        });

        static::deleted(function ($model) {
            DB::connection('sarafi')->transaction(function () use ($model) {
                $user = Auth::guard('sarafi')->user();
                $adminId = $user->admin_id ?? $user->id;

                // ثبت در زباله‌دان
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

                // حذف ژورنال مربوط به این تراکنش
                Journals::where('transaction_id', $model->id)->delete();
                Log::info("🗑️ Journal deleted for transaction ID={$model->id}");

                // اعمال اثر حذف روی ژورنال‌های بعدی
                if (!empty($model->beforeDeleteInfo) && $model->beforeDeleteInfo['has_later']) {
                    $model->adjustJournalsAfterDelete();
                } else {
                    Log::info("⏭️ No later journals to adjust for transaction {$model->id}");
                }

                // به‌روزرسانی موجودی صندوق/بانک
                $model->updateAccountBalance();
            });
        });
    }

    // =======================
    // Safe Balance Logic
    // =======================
    public function shouldAffectSafeBalance(): bool
    {
        // 🔴 لاگ ورودی
        Log::debug("shouldAffectSafeBalance called for TX {$this->id}", [
            'external_transaction_id' => $this->external_transaction_id,
            'account_type' => $this->account_type,
            'type' => $this->type,
            'customer_type' => optional($this->customer)->type,
            'conversion_transfer_id' => $this->conversion_transfer_id,
            'conversion_in_account_id' => $this->conversion_in_account_id,
            'account_to_id' => $this->account_to_id,
        ]);

        if ($this->external_transaction_id) {
            Log::info("❌ shouldAffectSafeBalance: false - external_transaction_id exists");
            return false;
        }

        if (!in_array($this->account_type, ['نقدی', 'بانکی'])) {
            Log::info("❌ shouldAffectSafeBalance: false - account_type is not نقدی/بانکی");
            return false;
        }

        if ($this->account_type === 'بانکی' && $this->type === 'برد' && $this->customer && $this->customer->type === 'sarafi_card') {
            Log::info("❌ shouldAffectSafeBalance: false - sarafi_card برد بانکی");
            return false;
        }

        if ($this->conversion_transfer_id || $this->conversion_in_account_id || $this->account_to_id) {
            Log::info("❌ shouldAffectSafeBalance: false - conversion or account transfer");
            return false;
        }

        Log::info("✅ shouldAffectSafeBalance: true");
        return true;
    }

    private function getRealAccountBalance(string $currency, string $accountType, int $adminId): float
    {
        if ($accountType === 'نقدی') {
            $balance = CurrencySafe::where('admin_id', $adminId)->value($currency) ?? 0;
            return (float)$balance;
        }

        if ($accountType === 'بانکی') {
            $balance = BankAccount::where('admin_id', $adminId)->value($currency) ?? 0;
            return (float)$balance;
        }

        return 0;
    }

    // =======================
    // Journal Management
    // =======================
    public function createJournal()
    {
        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            $balanceBefore = static::where('customer_id', $this->customer_id)
                ->where('currency', $this->currency)
                ->where('account_type', $this->account_type)
                ->where('admin_id', $adminId)
                ->where('id', '<>', $this->id)
                ->sum(DB::raw("CASE WHEN type='رسید' THEN amount WHEN type='برد' THEN -amount ELSE 0 END"));

            $signedAmount = $this->type === 'رسید' ? $this->amount : -$this->amount;
            $balanceAfter = $balanceBefore + $signedAmount;

            $lastJournal = Journals::where('admin_id', $adminId)
                ->where('currency', $this->currency)
                ->where('account_type', $this->account_type)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->first();

            $safeBalance = $lastJournal ? (float)$lastJournal->safe_balance : $this->getRealAccountBalance($this->currency, $this->account_type, $adminId);

            if ($this->shouldAffectSafeBalance()) {
                $safeBalance += $signedAmount;
            }

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

            Log::info("✅ Journal created for Transaction ID={$this->id}: balance={$balanceAfter}, safe_balance={$safeBalance}");
        } catch (\Exception $e) {
            Log::error("❌ Error creating journal for transaction {$this->id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateJournal()
    {
        $journal = Journals::where('transaction_id', $this->id)->first();
        if (!$journal) {
            Log::warning("⚠️ Journal not found for transaction {$this->id} during update");
            return;
        }

        $adminId = $journal->admin_id;

        if ($journal->currency !== $this->currency || $journal->account_type !== $this->account_type) {
            $oldTransaction = clone $this;
            $oldTransaction->id = $this->id;
            $oldTransaction->currency = $journal->currency;
            $oldTransaction->account_type = $journal->account_type;
            $oldTransaction->amount = $journal->amount;
            $oldTransaction->type = $journal->type;
            $oldTransaction->date = $journal->date;
            $oldTransaction->customer_id = $journal->customer_id;

            $oldTransaction->beforeDeleteInfo = $this->prepareDeleteInfo($oldTransaction);
            $oldTransaction->adjustJournalsAfterDelete();

            $journal->delete();
            $this->createJournal();
            return;
        }

        $oldSigned = $journal->type === 'رسید' ? $journal->amount : -$journal->amount;
        $newSigned = $this->type === 'رسید' ? $this->amount : -$this->amount;
        $diff = $newSigned - $oldSigned;

        if ($diff == 0) {
            $journal->update([
                'description' => $this->description,
                'date'        => $this->date,
            ]);
            return;
        }

        $journal->update([
            'amount'       => $this->amount,
            'type'         => $this->type,
            'description'  => $this->description,
            'date'         => $this->date,
            'balance'      => $journal->balance + $diff,
            'safe_balance' => $journal->safe_balance + $diff,
        ]);

        if ($this->shouldAffectSafeBalance()) {
            $affected = Journals::where('admin_id', $adminId)
                ->where('currency', $this->currency)
                ->where('account_type', $this->account_type)
                ->where(function ($q) {
                    $q->where('date', '>', $this->date)
                        ->orWhere(function ($q2) {
                            $q2->where('date', '=', $this->date)
                                ->where('id', '>', $this->id);
                        });
                })
                ->increment('safe_balance', $diff);
            Log::info("📈 [update] safe_balance adjusted for {$affected} later journals, diff={$diff}");
        }

        $affectedBalance = Journals::where('admin_id', $adminId)
            ->where('customer_id', $this->customer_id)
            ->where('currency', $this->currency)
            ->where('account_type', $this->account_type)
            ->where(function ($q) {
                $q->where('date', '>', $this->date)
                    ->orWhere(function ($q2) {
                        $q2->where('date', '=', $this->date)
                            ->where('id', '>', $this->id);
                    });
            })
            ->increment('balance', $diff);

        Log::info("📈 [update] customer balance adjusted for {$affectedBalance} later journals, diff={$diff}");
    }

    private function prepareDeleteInfo($transaction): array
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $signedAmount = $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;
        $shouldAffect = $transaction->shouldAffectSafeBalance();

        $firstLaterJournal = Journals::where('admin_id', $adminId)
            ->where('currency', $transaction->currency)
            ->where('account_type', $transaction->account_type)
            ->where(function ($q) use ($transaction) {
                $q->where('date', '>', $transaction->date)
                    ->orWhere(function ($q2) use ($transaction) {
                        $q2->where('date', '=', $transaction->date)
                            ->where('id', '>', $transaction->id);
                    });
            })
            ->orderBy('date')
            ->orderBy('id')
            ->first();

        return [
            'admin_id'      => $adminId,
            'currency'      => $transaction->currency,
            'account_type'  => $transaction->account_type,
            'customer_id'   => $transaction->customer_id,
            'signed_amount' => $signedAmount,
            'should_affect' => $shouldAffect,
            'date'          => $transaction->date,
            'id'            => $transaction->id,
            'has_later'     => !is_null($firstLaterJournal),
        ];
    }

    public function adjustJournalsAfterDelete()
    {
        $info = $this->beforeDeleteInfo;

        // مقدار تعدیل: عکس effect تراکنش حذف شده
        $adjustment = -$info['signed_amount'];
        Log::info("⚙️ adjustJournalsAfterDelete: adjustment = {$adjustment}");

        $affectedSafe = 0;
        if ($info['should_affect']) {
            $affectedSafe = Journals::where('admin_id', $info['admin_id'])
                ->where('currency', $info['currency'])
                ->where('account_type', $info['account_type'])
                ->where(function ($q) use ($info) {
                    $q->where('date', '>', $info['date'])
                        ->orWhere(function ($q2) use ($info) {
                            $q2->where('date', '=', $info['date'])
                                ->where('id', '>', $info['id']);
                        });
                })
                ->increment('safe_balance', $adjustment);
            Log::info("✅ safe_balance adjusted for {$affectedSafe} later journals");
        }

        $affectedBalance = Journals::where('admin_id', $info['admin_id'])
            ->where('customer_id', $info['customer_id'])
            ->where('currency', $info['currency'])
            ->where('account_type', $info['account_type'])
            ->where(function ($q) use ($info) {
                $q->where('date', '>', $info['date'])
                    ->orWhere(function ($q2) use ($info) {
                        $q2->where('date', '=', $info['date'])
                            ->where('id', '>', $info['id']);
                    });
            })
            ->increment('balance', $adjustment);

        Log::info("✅ customer balance adjusted for {$affectedBalance} later journals");
    }

    private function updateAccountBalance()
    {
        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            $lastJournal = Journals::where('admin_id', $adminId)
                ->where('currency', $this->currency)
                ->where('account_type', $this->account_type)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->first();

            $balance = $lastJournal ? (float)$lastJournal->safe_balance : 0;

            if ($this->account_type === 'نقدی') {
                DB::connection('sarafi')->table('currency_safe')
                    ->updateOrInsert(
                        ['admin_id' => $adminId],
                        [strtolower($this->currency) => $balance]
                    );
                Log::info("💰 Currency safe updated for {$this->currency}: {$balance}");
            } elseif ($this->account_type === 'بانکی') {
                DB::connection('sarafi')->table('bank_account')
                    ->updateOrInsert(
                        ['admin_id' => $adminId],
                        [strtolower($this->currency) => $balance]
                    );
                Log::info("💰 Bank account updated for {$this->currency}: {$balance}");
            }
        } catch (\Exception $e) {
            Log::error("❌ Error updating account balance: " . $e->getMessage());
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