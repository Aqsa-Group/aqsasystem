<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawals extends Model
{
    use HasFactory;

    protected $table = 'withdrawal';
    protected $connection='tools';


    protected $fillable = [
        'user_id',
        'admin_id',
        'type',
        'currency',
        'amount',
        'description',
        'date'
    ];

    protected $casts = [
        'amount' => 'integer',
        'date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

       public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}