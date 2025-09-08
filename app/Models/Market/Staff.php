<?php

namespace App\Models\Market;


use App\Models\Market\Market;
use App\Models\Market\StaffExpanses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Staff extends Model
{
    protected $connection= 'market';
    protected $table= 'staff';
    
    protected $fillable = [
      'market_id',
      'admin_id',
      'fullname',
      'father_name',
      'phone',
      'address',
      'job',
      'salary',
      'id_number',
      'warranty_image',
      'contract_start',
      'contract_end',
      'id_card_image',
      'profile_image'
    ];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }


    public function salary()
    {
        return $this->hasMany(Salary::class);
    }

    public function withdrawlog()
    {
        return $this->belongsTo(WithdrawLog::class);
    }

    public function loan()
    {
        return $this->hasMany(Loan::class);
    }

   
    public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}



public function staff()
{
    return $this->belongsTo(\App\Models\Market\Staff::class);
}





protected static function booted()
{
    static::creating(function ($document) {
        if (Auth::check()) {
            $user = Auth::user();
            $document->admin_id = $user->role === 'admin' ? $user->id : $user->admin_id;
        }
    });
}

}
