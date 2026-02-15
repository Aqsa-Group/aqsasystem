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
        'safe_deal_id',
        'customer_id'
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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }




}