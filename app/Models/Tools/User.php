<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $connection='tools';
    protected $table = 'users';
    protected $guard = 'tools';

    protected $fillable = [
        'name',
        'lastname',
        'company_name',
        'address',
        'phone',
        'username',
        'password',
        'role',
        'user_limition',
        'status',
        'admin_id',   
    ];


    

 

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status'   => 'boolean',
            'user_limition' => 'integer',
            'admin_id' => 'integer',
        ];
    }

   
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
