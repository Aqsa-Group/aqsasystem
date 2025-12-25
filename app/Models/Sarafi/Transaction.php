<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sarafi\Trash;
use Illuminate\Support\Facades\Auth;

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
        'withdrawbank_id'

    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

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
        return $this->belongsTo(SendToAccount::class, ' ');
    }



    public function conversionInAccount()
    {
        return $this->belongsTo(ConversionInAccounts::class, 'conversion_in_account_id');
    }


    public function currencyInfo()
    {
        return $this->belongsTo(Currency::class, 'currency', 'code');
    }

    public function scopeConversionTransactions($query)
    {
        return $query->whereNotNull('conversion_transfer_id');
    }

    public function scopeRegularTransactions($query)
    {
        return $query->whereNull('conversion_transfer_id');
    }

    // دسترسی‌دهنده برای نام نوع تراکنش
    public function getTypeNameAttribute()
    {
        return $this->type === 'رسید' ? 'دریافت' : 'برداشت';
    }

    // دسترسی‌دهنده برای مبلغ فرمت شده
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    // دسترسی‌دهنده برای نام ارز
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


    protected static function booted()
    {

        static::updating(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;
            Trash::create([
                'document_type' => 'رسید /برد صندوق',
                'record_id' => $model->id,
                'action' => 'ویرایش',
                'document_discription'=>  $model->description,
                'old_data' => $model->getOriginal(),
                'new_data' => $model->getAttributes(),
                'registered_user'=> $model->user_id,
                'user_id'  => $user->id,
                'admin_id' => $adminId,
            ]);
        });

        static::deleting(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;
            Trash::create([
                'document_type' => 'رسید /برد صندوق',
                'record_id' => $model->id,
                'action' => 'حذف',
                'document_discription'=>  $model->description,
                'old_data' => $model->getAttributes(),
                'registered_user'=> $model->user_id,
                'user_id'     => $user->id,
                'admin_id'         => $adminId,
            ]);
        });
    }
}
