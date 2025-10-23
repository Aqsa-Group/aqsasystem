<?php

namespace App\Models\Tools;
use App\Models\Tools\Staffs;

use Illuminate\Database\Eloquent\Model;

class Salarys extends Model
{
   protected $connection = 'tools';
   protected $table = 'salary';

   protected $guarded = [];


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
