<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;
use App\Models\Import\Loan;
use App\Models\Import\Sale;

class Customer extends Model
{
    protected $connection='import';
    protected $table='customers';
    
    protected $fillable = [
     'name',
     'user_id',
     'father_name',
     'grand-father',
     'phone',
     'address',
     'customer_image',
     'customer_id_card',
       'total_loan', 'total_receipt', 'remaining_loan'


    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
    
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    
    public function user()
{
    return $this->belongsTo(User::class);
}

 
    
}
