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
    public function createJournal()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $balance = static::where('customer_id', $this->customer_id)
            ->where('currency', $this->currency)
            ->where('account_type', $this->account_type)
            ->sum(DB::raw("
                CASE
                    WHEN type = 'رسید' THEN amount
                    WHEN type = 'برد' THEN -amount
                    ELSE 0
                END
            "));

        return Journals::create([
            'transaction_id' => $this->id,
            'customer_id' => $this->customer_id,
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'currency' => $this->currency,
            'type' => $this->type,
            'account_type' => $this->account_type,
            'amount' => $this->amount,
            'balance' => $balance,
            'description' => $this->description,
            'date' => $this->date,
        ]);
    }
public function updateJournal()
{
    $journal = Journals::where('transaction_id', $this->id)->first();

    if ($journal) {
        $balance = static::where('customer_id', $this->customer_id)
            ->where('currency', $this->currency)
            ->where('account_type', $this->account_type)
            ->where('id', '<>', $this->id) // حذف تراکنش فعلی
            ->sum(DB::raw("
                CASE
                    WHEN type = 'رسید' THEN amount
                    WHEN type = 'برد' THEN -amount
                    ELSE 0
                END
            "));

        // اضافه کردن مقدار تراکنش فعلی (ویرایش شده)
        $signedAmount = $this->type === 'رسید' ? $this->amount : -$this->amount;
        $balance += $signedAmount;

        // آپدیت Journal
        $journal->update([
            'amount'       => $this->amount,
            'balance'      => $balance,
            'currency'     => $this->currency,
            'type'         => $this->type,
            'account_type' => $this->account_type,
            'description'  => $this->description,
            'date'         => $this->date,
        ]);
    }
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
