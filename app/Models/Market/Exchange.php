<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $table = 'exchanges';
    protected $connection = 'market';
    
    protected $fillable = [
        'from_type',
        'to_type',
        'amount',
        'currency',
        'description',
    ];

    protected $casts = [
        'amount' => 'integer', 
    ];

    public function accountings()
    {
        return $this->hasMany(Accounting::class, 'exchange_id');
    }

    public function withdrawLogs()
    {
        return $this->hasMany(WithdrawLog::class, 'exchange_id');
    }

    protected static function booted()
    {
        static::deleting(function ($exchange) {
            $exchange->accountings()->delete();
            
            $exchange->withdrawLogs()->delete();
        });
    }
}