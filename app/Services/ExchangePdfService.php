<?php

namespace App\Services;

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class ExchangePdfService
{
    public static function make(string|array $format = 'A4'): Mpdf
    {
        $tempDir = storage_path('app/mpdf');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $fontDirs = (new ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];

        $mpdf = new Mpdf([
            'mode'           => 'utf-8',
            'format'         => $format,
            'directionality' => 'rtl',
            'margin_top'     => 10,
            'margin_bottom'  => 10,
            'margin_left'    => 10,
            'margin_right'   => 10,
            'fontDir'        => array_merge($fontDirs, [public_path('fonts/vazir/')]),
            'fontdata'       => $fontData + [
                'vazir' => [
                    'R'          => 'Vazir-Light.ttf',
                    'B'          => 'Vazir-Bold.ttf',
                    'useOTL'     => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font'   => 'vazir',
            'tempDir'        => $tempDir,
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->SetAutoPageBreak(true, 15);
        return $mpdf;
    }

    public static function render(iterable $exchanges, string $title = 'لیست تبادلات'): string
    {
        $count = is_array($exchanges) ? count($exchanges) : $exchanges->count();

        if ($count === 1) {
            $format = [210, 80];
        } elseif ($count <= 5) {
            $format = [210, 140];
        } else {
            $format = 'A4';
        }

        $mpdf = self::make($format);
        $mpdf->WriteHTML(
            view('pdf.exchanges', [
                'exchanges' => $exchanges,
                'title'     => $title,
            ])->render()
        );

        return $mpdf->Output('', 'S');
    }
}
