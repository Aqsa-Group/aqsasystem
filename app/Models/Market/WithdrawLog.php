<?php
namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WithdrawLog extends Model
{
    protected $table = 'withdraw_logs';
    protected $connection= 'market';

    
    protected $fillable = [
        'expanses_type',
        'admin_id',
        'currency',
        'amount',
        'description',
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    
    public function getRecipientNameAttribute()
    {
        if ($this->customer) {
            return $this->customer->fullname;
        }
    
        if ($this->staff) {
            return $this->staff->fullname;
        }
    
        return '-';
    }
    


    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
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
