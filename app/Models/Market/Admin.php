<?php

namespace App\Models\Market;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Market\User;
class Admin extends Authenticatable
{
    use Notifiable;
    protected $connection = 'market';
    protected $table = 'admin';

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'admin_id');
    }
}