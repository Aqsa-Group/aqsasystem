<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tools\User;

class Salarys extends Model
{
    protected $connection = 'tools';
    protected $table = 'salary';
    
    protected $fillable = [
        'admin_id',
        'userـid',
        'staff_id',
        'currency',
        'amount',
        'description',
        'date',

    ];


     
    protected $casts = [
        'date' => 'date',
        'amount' => 'integer',
    ];

    public function staff()
    {
        return $this->belongsTo(Staffs::class, 'staff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}