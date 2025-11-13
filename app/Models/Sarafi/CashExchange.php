<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CashExchange extends Model
{
    protected $connection = 'sarafi';
    protected $table = 'cash_exchange';

    protected $guarded = [];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }



     protected static function booted()
    {

        static::updating(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;
            Trash::create([
                'document_type' => 'تبدیل ارز در صندوق',
                'record_id' => $model->id,
                'action' => 'ویرایش',
                'document_discription'=>  $model->description,
                'old_data' => $model->getOriginal(),
                'new_data' => $model->getAttributes(),
                'registered_user'=> $model->user_id,
                'user_id'  => $user->id,
                'admin_id' => $adminId,
            ]);
        });

        static::deleting(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;
            Trash::create([
                'document_type' => 'تبدیل ارز در صندوق',
                'record_id' => $model->id,
                'action' => 'حذف',
                'document_discription'=>  $model->description,
                'old_data' => $model->getAttributes(),
                'registered_user'=> $model->user_id,
                'user_id'     => $user->id,
                'admin_id'         => $adminId,
            ]);
        });
    }


    

}


