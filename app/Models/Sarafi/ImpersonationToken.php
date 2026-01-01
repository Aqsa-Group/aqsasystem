<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class ImpersonationToken extends Model
{

    protected $connection = 'sarafi';
    protected $table = 'impersonation_tokens';
    protected $fillable = [
        'super_admin_id',
        'user_id',
        'token',
        'expires_at',
    ];

    protected $dates = ['expires_at'];
}
