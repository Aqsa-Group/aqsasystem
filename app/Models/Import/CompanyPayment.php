<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPayment extends Model
{
    use HasFactory;

    protected $connection = 'import';
    protected $table = 'companypayment';

    protected $fillable = [
        'company_id',
        'currency',
        'total_debt',
        'paid_amount',
        'remaining',
    ];

  
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
