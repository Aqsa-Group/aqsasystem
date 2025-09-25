<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;

class LoansPdfExport
{
    protected $customer_name;
    protected $type;
    protected $currency;
    protected $date;
    protected $loans;

    public function __construct($customer_name, $type, $currency, $date, $loans)
    {
        $this->customer_name = $customer_name;
        $this->type = $type;
        $this->currency = $currency;
        $this->date = $date;
        $this->loans = $loans;
    }

    public function generatePdf()
    {
        $html = view('exports.loans-pdf', [
            'loans' => $this->loans,
            'customer_name' => $this->customer_name,
            'type' => $this->type,
            'currency' => $this->currency,
            'date' => $this->date,
        ])->render();

        return Pdf::loadHTML($html)
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'defaultFont' => 'DejaVu Sans', // پشتیبانی کامل UTF-8 فارسی
                  ])
                  ->stream('loans-report.pdf');
    }
}
