<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Customer extends Authenticatable
{
    protected $connection = 'sarafi';
    protected $table = 'customers';
    
    protected $fillable = [
        'fullname',
        'image',
        'id_card_image', 
        'city',
        'phone',
        'idcard_number',
        'account_number',
        'whatsapp_number',
        'password', 
        'type',
        'user_id',
        'admin_id',
        'created_by',
        'related_customer_id'
    ];

    protected $hidden = [
        'password',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


    // در مدل Customer
public function admins()
{
    return $this->belongsToMany(
        User::class,       // مدل مرتبط
        'customer_admin',  // جدول pivot
        'customer_id',     // کلید این مدل در pivot
        'admin_id'         // کلید مدل مرتبط در pivot
    );
}


   /**
     * بررسی آیا مشتری به ادمین خاصی لینک شده است
     */
    public function isLinkedToAdmins($adminId): bool
    {
        return $this->linkedAdmins()->where('admin_id', $adminId)->exists();
    }
    
    /**
     * Scope برای دریافت مشتریان لینک شده به یک ادمین
     */
    public function scopeLinkedToAdmin($query, $adminId)
    {
        return $query->whereHas('linkedAdmins', function ($q) use ($adminId) {
            $q->where('adminisLinkedToAdmins_id', $adminId);
        });
    }
    
    /**
     * Scope برای دریافت مشتریان ادمین (چه مال خودش باشد چه لینک شده)
     */
    public function scopeAccessibleByAdmin($query, $adminId)
    {
        return $query->where(function ($q) use ($adminId) {
            // مشتریانی که مستقیماً مال این ادمین هستند
            $q->where('admin_id', $adminId)
                // یا مشتریانی که به این ادمین لینک شده‌اند
                ->orWhereHasisLinkedToAdmins('isLinkedToAdmins', function ($subQ) use ($adminId) {
                    $subQ->where('admin_id', $adminId);
                });
        });
    }


   
    
    /**
     * دریافت همه ادمین‌هایی که به این مشتری دسترسی دارند
     */
    public function getAllAccessAdminsAttribute()
    {
        $adminIds = [$this->admin_id];
        
        if ($this->relationLoaded('linkedAdmins')) {
            $linkedAdminIds = $this->linkedAdmins->pluck('id')->toArray();
            $adminIds = array_merge($adminIds, $linkedAdminIds);
        } else {
            $linkedAdminIds = DB::table('customer_admin')
                ->where('customer_id', $this->id)
                ->pluck('admin_id')
                ->toArray();
            $adminIds = array_merge($adminIds, $linkedAdminIds);
        }
        
        return array_filter(array_unique($adminIds));
    }
    
        // Relationship with sent remittances
    public function sentRemittances()
    {
        return $this->hasMany(Remittances::class, 'customer_id');
    }

    // Relationship with received remittances
    public function receivedRemittances()
    {
        return $this->hasMany(Remittances::class, 'to_account');
    }

    // Get all remittances (both sent and received)
    public function allRemittances()
    {
        return Remittances::where('customer_id', $this->id)
            ->orWhere('to_account', $this->id)
            ->get();
    }

    
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

      public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    public function transactions()
{
    return $this->hasMany(\App\Models\Sarafi\Transaction::class, 'customer_id');
}







}