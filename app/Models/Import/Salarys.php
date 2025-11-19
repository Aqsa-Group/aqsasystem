<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;
use App\Models\Import\User;

class Salarys extends Model
{
    protected $connection = 'import';
    protected $table = 'salary';
    
    protected $fillable = [
        'user_id',
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
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

   

}