<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class WithdrawsBanks extends Model
{
     use HasFactory;
   
    protected $connection = 'sarafi';
    protected $table = 'withdrawbank';
    
    protected $fillable = [
        'customer_id',
        'to_account',
        'user_id',
        'admin_id',
        'source_account',
        'currency',
        'amount',
        'date',
        'clock',
        'tracking_code',
        'from_bank',
        'to_bank',
        'zone',
        'giver_name',
        'description',
        'remittance_image',
        'state'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Customer::class, 'to_account');
    }

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
                'document_type' =>'برد بانکی',
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
                'document_type' =>'برد بانکی',
                'record_id' => $model->id,
                'action' => 'حذف',
                'document_discription'=>  $model->description,
                'old_data' => $model->getAttributes(),
                'registered_user'=> $model->user_id,
                'user_id'     => $user->id,
                'admin_id'    => $adminId,
            ]);
        });
    }
}

