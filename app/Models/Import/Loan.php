<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;
use App\Models\Import\Customer;


class Loan extends Model
{

    protected $connection = 'import';
    protected $table = 'loans';

    protected $fillable = [
        'type',
        'customer_id',
        'amount',
        'user_id',

        'past_amount',
        'loan_recipt',
        'date',
        'reminded',
        'brand',
    ];



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }



    protected static function booted()
    {
        static::saved(function ($loan) {
            $loan->updateCustomerLoanSummary();
        });

        static::deleted(function ($loan) {
            $loan->updateCustomerLoanSummary();
        });
    }

    public function updateCustomerLoanSummary()
    {
        if (!$this->customer) {
            return;
        }

        $customer = $this->customer;

        $totalLoan = Loan::where('customer_id', $customer->id)
            ->where('type', 'بردگی')
            ->sum('amount');

        $totalReceipt = Loan::where('customer_id', $customer->id)
            ->where('type', 'رسید')
            ->sum('loan_recipt');

        $remaining = $totalLoan - $totalReceipt;

        $customer->update([
            'total_loan' => $totalLoan,
            'total_receipt' => $totalReceipt,
            'remaining_loan' => max($remaining, 0),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
