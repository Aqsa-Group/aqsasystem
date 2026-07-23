<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Salary extends Model
{

    protected $connection= 'market';
    protected $table= 'salaries';
    
    protected $fillable = [
        'market_id',
        'staff_id',
        'loan_id',
        'salary',
        'paid',
        'reduce_from',
        'remained',
        'loan',
        'currency',
        'is_reduce',
        'reduce_loan',
        'new_loan',
        'paid_date',
        'admin_id',
        'description'
    ];

    protected $casts = [
        'is_reduce' => 'boolean',
        'paid_date' => 'date',
    ];

    
    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }




protected static function booted()
{
    static::creating(function ($salary) {
        if (Auth::check()) {
            $user = Auth::user();
            $salary->admin_id = $user->role === 'admin'
                ? $user->id
                : $user->admin_id;
        }
    });

    // بعد از ویرایش معاش
    static::updated(function ($salary) {
        DB::connection('market')
            ->table('accountings')
            ->where('salary_id', $salary->id)
            ->update([
                'expanses_type' => $salary->reduce_from,
                'currency'      => $salary->currency,
                'paid'          => -abs($salary->paid),
                'market_id'     => $salary->market_id,
                'admin_id'      => $salary->admin_id,
                'updated_at'    => now(),
            ]);
    });

    // بعد از حذف معاش
    static::deleted(function ($salary) {
        DB::connection('market')
            ->table('accountings')
            ->where('salary_id', $salary->id)
            ->delete();
    });
}


}
