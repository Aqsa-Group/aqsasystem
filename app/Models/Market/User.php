<?php

namespace App\Models\Market;


use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Filament\Models\Contracts\HasName;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    // public function getFilamentName(): string
    // {
    //     return $this->username ?? 'بدون‌نام'; // یا full_name یا هر فیلدی که داری
    // }
 
    protected $connection = 'market';
    protected $table = 'users';

    use Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
        'phone',
        'related_market',
        'admin_id',
        'market_limit',
        'market_name'

    ];

     public function canAccessPanel(Panel $panel): bool
     {
        return in_array($this->role,['superadmin', 'admin', 'Financial Manager' , 'Cashier' , 'Customer Service']);
     }


    protected $hidden = [
        'password',
    ];

    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (Auth::check() && Auth::user()->role === 'admin') {
                $user->admin_id = Auth::id();
            }
        });
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function userLogs()
    {
        return $this->hasMany(UserLog::class, 'user_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'admin_id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, 'admin_id');
    }

    
    public function depositLogs()
    {
        return $this->hasMany(DepositLog::class);
    }

    public function booth()
    {
        return $this->hasMany(Booth::class);
    }
    
    public function sell()
    {
        return $this->hasMany(Sell::class);
    }

    public function buys()
    {
        return $this->hasMany(Buy::class);
    }


   

    public function market()
    {
        return $this->belongsTo(Market::class, 'related_market');
    }
}
