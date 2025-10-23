<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tools\User;

class Staffs extends Model
{
    protected $connection = 'tools';
    protected $table = 'staff';

    protected $fillable = [
        'image',
        'id_card_image',
        'name',
        'lastname',
        'address',
        'phone',
        'job',
        'salary',
        'user_id',
        'admin_id',
        'created_by',
    ];

 



    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


        public function salary()
    {
        return $this->hasMany(Salarys::class,);
    }

 
}
