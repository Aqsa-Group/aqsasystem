

public function print($transactionId)
    {
        $transaction = Transaction::with(['customer', 'user'])->findOrFail($transactionId);

     
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [72.1, 297],
            'directionality' => 'rtl',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
            'fontDir' => array_merge(
                (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                [public_path('fonts/vazir/')]
            ),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'vazir' => [
                    'R' => 'Vazir-Light.ttf',
                    'B' => 'Vazir-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'vazir',
            'tempDir' => storage_path('app/mpdf'),
        ]);

        $mpdf->SetAutoPageBreak(false);

        $mpdf->WriteHTML(view('pdf.Sarafi.transaction', [
            'transaction' => $transaction,
        ])->render());

        $mpdf->AddPage();
  
        $fileName = 'transaction_' . $transaction->id . '.pdf';
        $path = storage_path('app/public/' . $fileName);

        $mpdf->Output($path, 'F');

        $this->dispatch('print-pdf', url: asset('storage/' . $fileName));
    }
