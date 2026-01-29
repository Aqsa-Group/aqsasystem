<?php

namespace App\Models\Sarafi;

use App\Models\Sarafi\Journals;
use App\Models\Sarafi\Trash;
use App\Services\WhatsAppService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;
use App\Models\Sarafi\CurrencySafe;


class Transaction extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'transactions';

    /* =======================
       Mass Assignment
    ======================= */
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

    ];

    /* =======================
       Casts
    ======================= */
    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    /* =======================
       Relations
    ======================= */
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

    public function conversionTransfer()
    {
        return $this->belongsTo(ConversionTransfers::class, 'conversion_transfer_id');
    }

    public function changerdeal()
    {
        return $this->belongsTo(ChangerDeal::class, 'changerdeal_id');
    }

    public function withdrawbank()
    {
        return $this->belongsTo(WithdrawsBanks::class, 'withdrawbank_id');
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

    /* =======================
       Scopes
    ======================= */
    public function scopeConversionTransactions($query)
    {
        return $query->whereNotNull('conversion_transfer_id');
    }

    public function scopeRegularTransactions($query)
    {
        return $query->whereNull('conversion_transfer_id');
    }

    /* =======================
       Accessors
    ======================= */
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
        $currencyMap = [
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
            'inr' => 'روپیه',
        ];

        return $currencyMap[$this->currency] ?? $this->currency;
    }

    /* =======================
       Model Events (Observer)
    ======================= */
    protected static function booted()
    {
        // ---------- ایجاد Journal ----------
        static::created(function ($model) {
            $model->createJournal();
            $model->sendWhatsApp();
        });

        // ---------- بروزرسانی Journal و ثبت Trash ----------
        static::updating(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            // ثبت تغییرات در Trash
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

            // آپدیت Journal مربوطه
            $model->updateJournal();
        });

        // ---------- حذف Journal و ثبت Trash ----------
        static::deleting(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            // ثبت حذف در Trash
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

            // حذف Journal مربوطه
            $model->deleteJournal();
        });
    }

    /* =======================
       Journal Management
    ======================= */

   private function getRealAccountBalance(string $currency, string $accountType, int $adminId): float
{
    if ($accountType === 'نقدی') {
        return CurrencySafe::where('admin_id', $adminId)->value($currency) ?? 0;
    }

    if ($accountType === 'بانکی') {
        return BankAccount::where('admin_id', $adminId)->value($currency) ?? 0;
    }

    return 0;
}


    private function shouldAffectSafeBalance(): bool
{
    // ❌ معاملات تبدیل ارز (external_transaction)
    if ($this->external_transaction_id) {
        return false;
    }

    // فقط نقدی و بانکی
    if (!in_array($this->account_type, ['نقدی', 'بانکی'], true)) {
        return false;
    }

    // برد بانکی + کارت صرافی
    if (
        $this->account_type === 'بانکی'
        && $this->type === 'برد'
        && $this->customer
        && $this->customer->category === 'sarafi_card'
    ) {
        return false;
    }

    // انتقالات داخلی
    if (
        $this->conversion_transfer_id ||
        $this->conversion_in_account_id ||
        $this->account_to_id
    ) {
        return false;
    }

    return true;
}


public function createJournal()
{
    $user    = Auth::guard('sarafi')->user();
    $adminId = $user->admin_id ?? $user->id;

    /* ========= balance مشتری ========= */
    $balanceBefore = static::where('customer_id', $this->customer_id)
        ->where('currency', $this->currency)
        ->where('account_type', $this->account_type)
        ->where('admin_id', $adminId)
        ->where('id', '<>', $this->id)
        ->sum(DB::raw("
            CASE
                WHEN type = 'رسید' THEN amount
                WHEN type = 'برد' THEN -amount
                ELSE 0
            END
        "));

    $signedAmount = $this->type === 'رسید'
        ? $this->amount
        : -$this->amount;

    $balanceAfter = $balanceBefore + $signedAmount;

    /* ========= safe_balance واقعی ========= */
    $safeBalance = $this->getRealAccountBalance(
        $this->currency,
        $this->account_type,
        $adminId
    );

    // فقط معاملات واقعی صندوق
    if ($this->shouldAffectSafeBalance()) {
        $safeBalance += $signedAmount;
    }

    return Journals::create([
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
}

  public function updateJournal()
{
    $journal = Journals::where('transaction_id', $this->id)->first();
    if (!$journal) return;

    $balanceBefore = static::where('customer_id', $this->customer_id)
        ->where('currency', $this->currency)
        ->where('account_type', $this->account_type)
        ->where('admin_id', $journal->admin_id)
        ->where('id', '<>', $journal->transaction_id)
        ->sum(DB::raw("
            CASE
                WHEN type = 'رسید' THEN amount
                WHEN type = 'برد' THEN -amount
                ELSE 0
            END
        "));

    $signedAmount = $this->type === 'رسید'
        ? $this->amount
        : -$this->amount;

    $balanceAfter = $balanceBefore + $signedAmount;

    $safeBalance = $this->getRealAccountBalance(
        $this->currency,
        $this->account_type,
        $journal->admin_id
    );

    if ($this->shouldAffectSafeBalance()) {
        $safeBalance += $signedAmount;
    }

    $journal->update([
        'amount'       => $this->amount,
        'balance'      => $balanceAfter,
        'safe_balance' => $safeBalance,
        'currency'     => $this->currency,
        'type'         => $this->type,
        'account_type' => $this->account_type,
        'description'  => $this->description,
        'date'         => $this->date,
    ]);
}



    public function deleteJournal()
    {
        Journals::where('transaction_id', $this->id)->delete();
    }

    /* =======================
       WhatsApp Notification
    ======================= */
    public function sendWhatsApp()
    {
        $customer = $this->customer;

        if (!$customer || !$customer->whatsapp_number) {
            return;
        }

        $phone = preg_replace('/[^0-9]/', '', $customer->whatsapp_number);

        WhatsAppService::sendTransaction(
            $phone,
            [
                'exchange_name' => $this->user->sarafi_name ?? '-',
                'account_number' => $customer->fullname ?? '-',
                'amount' => (string) ($this->amount ?? '-'),
                'currency' => $this->currency ?? '-',
                'transaction_type' => $this->type ?? '-',
                'transaction_date' => $this->date ? $this->date->format('Y-m-d H:i') : '-',
                'balance' => (string) ($this->amount ?? '-'),
                'exchange_contact' => (string) ($this->user->phone ?? '-'),
            ]
        );
    }
}
