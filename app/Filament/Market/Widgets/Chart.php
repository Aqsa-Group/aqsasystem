<?php

namespace App\Filament\Market\Widgets;

use Filament\Widgets\ChartWidget;

class Chart extends ChartWidget
{
    protected static ?string $heading = 'عواید';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $labels = [
            'حمل', 'ثور', 'جوزا', 'سرطان',
            'اسد', 'سنبله', 'میزان', 'عقرب',
            'قوس', 'جدی', 'دلو', 'حوت',
        ];
        

        $data = [100, 120, 150, 130, 110, 160, 140, 135, 125, 115, 105, 95];

        $backgroundColors = [
            'rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)', 'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)',
            'rgba(199, 199, 199, 0.5)', 'rgba(83, 102, 255, 0.5)',
            'rgba(255, 99, 255, 0.5)', 'rgba(99, 255, 132, 0.5)',
            'rgba(255, 255, 99, 0.5)', 'rgba(99, 255, 255, 0.5)',
        ];

        $borderColors = [
            'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)',
            'rgba(199, 199, 199, 1)', 'rgba(83, 102, 255, 1)',
            'rgba(255, 99, 255, 1)', 'rgba(99, 255, 132, 1)',
            'rgba(255, 255, 99, 1)', 'rgba(99, 255, 255, 1)',
        ];
        return [
            'labels' => array_reverse($labels),
            'datasets' => [
                [
                    'label' => 'عواید ماهانه',
                    'data' => array_reverse($data),
                    'backgroundColor' => array_reverse($backgroundColors),
                    'borderColor' => array_reverse($borderColors),
                    'fill' => false,
                    'borderWidth' => 2,
                    'pointBackgroundColor' => array_reverse($backgroundColors),
                    'pointBorderColor' => array_reverse($borderColors),
                ],
            ],
        ];

      
    }
}
