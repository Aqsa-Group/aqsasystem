<?php

namespace App\Models\Restaurant;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;


class User extends Authenticatable
{
    protected $connection = 'restaurant';
    protected $table = 'users';
    protected $guard = 'restaurant';


    protected $fillable = [
        'name',
        'lastname',
        'restaurant_name',
        'address',
        'phone',
        'username',
        'password',
        'role',
        'user_limition',
        'status',
        'admin_id',
        'user_image'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'status' => 'boolean',
        'user_limition' => 'integer',
        'admin_id' => 'integer',
        'password' => 'hashed',


    ];
}