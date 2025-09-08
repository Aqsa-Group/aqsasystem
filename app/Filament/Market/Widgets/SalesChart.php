<?php

namespace App\Filament\Market\Widgets;

use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'مصارف';

    // این متغیر را می‌توانی از بیرون مقدار دهی کنی یا از تم/کاربر بگیری
    public bool $isDarkMode = false;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $labels = array_reverse([
            'حمل', 'ثور', 'جوزا', 'سرطان',
            'اسد', 'سنبله', 'میزان', 'عقرب',
            'قوس', 'جدی', 'دلو', 'حوت',
        ]);

        $data = array_reverse([120, 150, 100, 80, 90, 110, 140, 130, 100, 95, 85, 70]);
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
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'مصارف ماهانه',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

  protected function getOptions(): array
{
    $textColor = $this->isDarkMode ? '#f9fafb' : '#1f2937'; 
    $legendColor = $this->isDarkMode ? '#f9fafb' : '#1f2937';
    $gridColor = $this->isDarkMode ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

    return [
        'indexAxis' => 'x',
        'elements' => [
            'bar' => [
                'borderRadius' => 6,
                'barThickness' => 24,
            ],
        ],
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'top',
                'labels' => [
                    'font' => ['family' => 'IRANSans', 'size' => 14],
                    'color' => $legendColor,
                ],
            ],
            'tooltip' => [
                'rtl' => true,
                'titleAlign' => 'right',
                'bodyAlign' => 'right',
                'titleFont' => ['family' => 'IRANSans', 'size' => 14],
                'bodyFont' => ['family' => 'IRANSans', 'size' => 13],
                'backgroundColor' => $this->isDarkMode ? '#1e293b' : '#f3f4f6',
                'titleColor' => $textColor,
                'bodyColor' => $textColor,
            ],
        ],
        'scales' => [
            'x' => [
                'ticks' => [
                    'font' => ['family' => 'IRANSans', 'size' => 13],
                    'color' => $textColor,
                    'reverse' => true,
                ],
                'grid' => [
                    'color' => $gridColor,
                ],
            ],
            'y' => [
                'ticks' => [
                    'font' => ['family' => 'IRANSans', 'size' => 13],
                    'color' => $textColor,
                    'beginAtZero' => true,
                ],
                'grid' => [
                    'color' => $gridColor,
                ],
            ],
        ],
    ];
}

}
