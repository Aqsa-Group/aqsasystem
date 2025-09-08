<?php

namespace App\Models\Import;


use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $connection = 'import';
    protected $table = 'staff';

    protected $fillable = [
        'name',
        'father_name',
        'grand-father',
        'user_id',
        'phone',
        'address',
        'salary'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
