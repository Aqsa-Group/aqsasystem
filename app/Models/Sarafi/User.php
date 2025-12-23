<?php

namespace App\Models\Sarafi;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $connection = 'sarafi';
    protected $table = 'users';
    protected $guard = 'sarafi';

    protected $fillable = [
        'name',
        'lastname',
        'sarafi_name',
        'address',
        'phone',
        'username',
        'password',
        'role',
        'user_limition',
        'status',
        'admin_id',
        'zone',
        'address2',
        'address3',
        'phone2',
        'phone3',


    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'status' => 'boolean',
        'user_limition' => 'integer',
        'admin_id' => 'integer',
        'password' => 'hashed',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

public function customers()
{
    return $this->belongsToMany(
        Customer::class,
        'customer_admin',
        'admin_id',
        'customer_id'
    );
}

}
