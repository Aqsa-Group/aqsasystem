<?php

namespace App\Models\Import;


use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{

    protected $connection='import';
    protected $table='withdraws';

    protected $fillable = ['amount', 'description', 
    'type',
    'user_id',    
    'staff_id'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
