<?php

namespace App\Models\Sarafi;

use App\Models\Sarafi\SafeDeal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
