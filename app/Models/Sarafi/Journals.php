<?php

namespace App\Models\Sarafi;

use App\Livewire\Sarafi\ConversionInAccount;
use App\Livewire\Sarafi\Withdraw;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Sarafi\User;
use App\Models\Sarafi\Customer;

class Journals extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'journal';

    /* =======================
     | Mass Assignment
     ======================= */

    protected $fillable = [
        // روابط اصلی
        'customer_id',
        'user_id',
        'admin_id',

        // منابع Journal (یکی از این‌ها پر می‌شود)
        'transaction_id',
        'account_to_account_id',
        'conversion_in_account_id',
        'conversion_transfer_id',
        'buysell_id',
        'withdrawbank_id',
        'changerdeals_id',
        'withdraw_id',


        // اطلاعات مالی
        'currency',
        'type',          // رسید | برد
        'account_type',  // نقدی | بانکی
        'amount',
        'balance',

        // متفرقه
        'description',
        'date',
        'is_sell_table'
    ];

    /* =======================
     | Casts
     ======================= */

    protected $casts = [
        'amount'  => 'decimal:2',
        'balance' => 'decimal:2',
        'date'    => 'date',
    ];

    /* =======================
     | Relations
     ======================= */

    // مشتری
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // کاربر ثبت‌کننده
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ادمین
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /* ---------- منابع Journal ---------- */

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

    public function changerDeal()
    {
        return $this->belongsTo(ChangerDeal::class);
    }

      public function withdraw()
    {
        return $this->belongsTo(Withdraws::class);
    }


    /* =======================
     | Scopes
     ======================= */

    public function scopeCurrency($query, $currency)
    {
        if ($currency) {
            $query->where('currency', $currency);
        }
    }

    public function scopeAccountType($query, $type)
    {
        if ($type) {
            $query->where('account_type', $type);
        }
    }

    public function scopeTransactionType($query, $type)
    {
        if ($type) {
            $query->where('type', $type);
        }
    }

    public function scopeBetweenDates($query, $from, $to)
    {
        if ($from && $to) {
            $query->whereBetween('date', [$from, $to]);
        }
    }

    /* =======================
     | Accessors
     ======================= */

    protected $appends = ['currency_fa', 'source_type'];

    public function getCurrencyFaAttribute()
    {
        return config('currencies.' . $this->currency) ?? $this->currency;
    }

    /**
     * تشخیص منبع Journal (خیلی کاربردی برای UI)
     */
    public function getSourceTypeAttribute()
    {
        return match (true) {
            !is_null($this->transaction_id)            => 'transaction',
            !is_null($this->account_to_account_id)     => 'account_to_account',
            !is_null($this->conversion_in_account_id)  => 'conversion_in_account',
            !is_null($this->conversion_transfer_id)    => 'conversion_transfer',
            !is_null($this->buysell_id)                 => 'buy_sell',
            !is_null($this->withdrawbank_id)            => 'withdraw_bank',
            !is_null($this->changerdeals_id)            => 'changer_deal',
            default                                     => 'manual',
        };
    }
}
