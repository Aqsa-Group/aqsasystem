<?php

namespace App\Models\Sarafi;

use App\Models\Sarafi\SafeDeal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sarafi\Journals;
use App\Models\Sarafi\CurrencySafe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SafeDealsRevenue extends Model
{
    use HasFactory;

    protected $table = 'safe_deals_revenue';
    protected $connection = 'sarafi';


    protected $fillable = [
        'user_id',
        'admin_id',
        'safe_deals_id',
        'currency',
        'amount',
        'type',
        'account_type',
        'date',
        'description',
        'safe_deal_id'
    ];

    // روابط
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


    public function safeDeal()
    {
        return $this->belongsTo(SafeDeal::class, 'safe_deals_id');
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }




    protected static function booted()
    {
        static::created(function ($model) {
            self::syncJournals($model);
        });

        static::updating(function ($model) {
            Journals::where('revenue_id', $model->id)->delete();
        });

        static::updated(function ($model) {
            self::syncJournals($model);
        });

        static::deleting(function ($model) {
            Journals::where('revenue_id', $model->id)->delete();
        });
    }


    private static function syncJournals(self $model): void
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $model->admin_id ?? $user->admin_id ?? $user->id;

        DB::transaction(function () use ($model, $user, $adminId) {

            // موجودی صندوق قبل
            $safeBefore = (float) CurrencySafe::where('admin_id', $adminId)
                ->value($model->currency);

            // محاسبه مانده بعد
            if ($model->type === 'برد') {
                $safeAfter = $safeBefore - $model->amount;
            } else { // رسید
                $safeAfter = $safeBefore + $model->amount;
            }

            // ثبت ژورنال
            Journals::create([
                'customer_id'   => null,
                'user_id'       => $model->user_id,
                'admin_id'      => $adminId,
                'currency'      => $model->currency,
                'type'          => $model->type,
                'account_type'  => $model->account_type, 
                'amount'        => $model->amount,
                'balance'       => $safeAfter,
                'safe_balance'  => $safeAfter,
                'description'   => $model->description,
                'date'          => $model->date,
                'is_revenue'    => 1,
                'revenue_id'    => $model->id,
            ]);

        });
    }
}
