<?php
namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Market\Customer;
use App\Models\Market\Staff;



class WithdrawLog extends Model
{
    protected $connection = 'market';
    protected $table = 'withdraw_logs';

    
   protected $fillable = [
    'expanses_type',
    'admin_id',
    'currency',
    'amount',
    'description',
    'customer_id',
    'staff_id',
    'exchange_id',
    'created_at',
    'updated_at',
];
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
