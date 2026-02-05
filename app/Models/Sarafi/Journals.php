<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

use App\Models\Sarafi\User;
use App\Models\Sarafi\Customer;
use App\Livewire\Sarafi\ConversionInAccount;
use App\Livewire\Sarafi\ExternalTransaction;
use App\Livewire\Sarafi\Withdraw;

class Journals extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'journal';

    /* =======================
     | Mass Assignment
     ======================= */

    protected $fillable = [
        'customer_id',
        'user_id',
        'admin_id',

        'transaction_id',
        'account_to_account_id',
        'conversion_in_account_id',
        'conversion_transfer_id',
        'buysell_id',
        'withdrawbank_id',
        'changerdeals_id',
        'withdraw_id',
        'external_transaction_id',
        'withdraw_external_safe_id',

        'currency',
        'type',          // رسید | برد
        'account_type',  // نقدی | بانکی
        'amount',
        'balance',

        'description',
        'date',
        'is_sell_table',
        'safe_balance',
        'safe_deal_id',
    ];

    /* =======================
     | Casts
     ======================= */

    protected $casts = [
        'amount'       => 'decimal:2',
        'balance'      => 'decimal:2',
        'safe_balance' => 'decimal:2',
        'date'         => 'date',
    ];

    /* =======================
     | Relations
     ======================= */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function accountToAccount()
    {
        return $this->belongsTo(SendToAccount::class);
    }

    public function conversionInAccount()
    {
        return $this->belongsTo(ConversionInAccount::class);
    }

    public function conversionTransfer()
    {
        return $this->belongsTo(ConversionTransfers::class);
    }

    public function buySell()
    {
        return $this->belongsTo(CashExchange::class, 'buysell_id');
    }

    public function withdrawBank()
    {
        return $this->belongsTo(WithdrawsBanks::class);
    }

    public function externaltransaction()
    {
        return $this->belongsTo(ExternalTransaction::class, 'external_transaction_id');
    }

    public function changerDeal()
    {
        return $this->belongsTo(ChangerDeal::class);
    }

    public function withdraw()
    {
        return $this->belongsTo(Withdraws::class);
    }

    public function safe_deal()
    {
        return $this->belongsTo(SafeDealsRevenue::class, 'safe_deal_id');
    }

    /* =======================
     | Scopes
     ======================= */

    public function scopeCurrency($q, $currency)
    {
        if ($currency) $q->where('currency', $currency);
    }

    public function scopeAccountType($q, $type)
    {
        if ($type) $q->where('account_type', $type);
    }

    /* =======================
     | Accessors
     ======================= */

    protected $appends = ['currency_fa', 'source_type'];

    public function getCurrencyFaAttribute()
    {
        return config('currencies.' . $this->currency) ?? $this->currency;
    }

    public function getSourceTypeAttribute()
    {
        return match (true) {
            $this->transaction_id            => 'transaction',
            $this->account_to_account_id     => 'account_to_account',
            $this->conversion_in_account_id  => 'conversion_in_account',
            $this->conversion_transfer_id    => 'conversion_transfer',
            $this->buysell_id                => 'buy_sell',
            $this->withdrawbank_id           => 'withdraw_bank',
            $this->changerdeals_id           => 'changer_deal',
            default                          => 'manual',
        };
    }

    /* =======================
     | Core Logic (IMPORTANT)
     ======================= */

    /**
     * بازسازی chain بعد از حذف یا ویرایش
     */
   public static function recalculateChain(self $deletedJournal)
{
    DB::transaction(function () use ($deletedJournal) {

        // 1️⃣ آخرین رکورد سالم قبل از حذف
        $previous = self::where('customer_id', $deletedJournal->customer_id)
            ->where('currency', $deletedJournal->currency)
            ->where('account_type', $deletedJournal->account_type)
            ->where('id', '<', $deletedJournal->id)
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->first();

        $balance = $previous?->balance ?? 0;
        $safe    = $previous?->safe_balance ?? 0;

        // 2️⃣ همه رکوردهای بعد از حذف
        $journals = self::where('customer_id', $deletedJournal->customer_id)
            ->where('currency', $deletedJournal->currency)
            ->where('account_type', $deletedJournal->account_type)
            ->where('id', '>', $deletedJournal->id)
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        foreach ($journals as $journal) {

            if ($journal->type === 'رسید') {
                $balance += $journal->amount;
                $safe    += $journal->amount;
            } else { // برد
                $balance -= $journal->amount;
                $safe    -= $journal->amount;
            }

            $journal->updateQuietly([
                'balance'      => $balance,
                'safe_balance' => $safe,
            ]);
        }
    });
}


    /* =======================
     | Model Events
     ======================= */

    protected static function booted()
    {
        // بعد از حذف
        static::deleted(function ($journal) {
            self::recalculateChain($journal);
        });

        // بعد از ویرایش amount / type
        static::updated(function ($journal) {
            if ($journal->wasChanged(['amount', 'type'])) {
                self::recalculateChain($journal);
            }
        });
    }
}
