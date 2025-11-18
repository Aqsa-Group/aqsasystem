<?php

namespace App\Models\Gym;


use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $connection = 'gyms';
    protected $table = 'users';
    protected $guard = 'gyms';

    protected $fillable = [
        'name',
        'lastname',
        'gym_name',
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

    public function subUsers()
    {
        return $this->hasMany(User::class, 'admin_id');
    }

    public static function getAdminAndSubUserIds($adminId)
    {
        return self::where('id', $adminId)
            ->orWhere('admin_id', $adminId)
            ->pluck('id');
    }
}
