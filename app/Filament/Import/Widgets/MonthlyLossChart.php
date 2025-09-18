<?php

namespace App\Filament\Import\Widgets;

use App\Models\Import\SaleItem;
use App\Models\Import\Safe;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class MonthlyLossChart extends ChartWidget
{
    protected static ?string $heading = '📉 ضرر ماهانه';

    protected function getData(): array
    {
        $userId = Auth::id();

        $safe = Safe::where('user_id', $userId)->latest()->first();
        $currencyLabel = ($safe && $safe->currency) ? 'USD' : 'AFN';

        $saleItems = SaleItem::where('user_id', $userId)->get();
        $lossesByMonth = [];

        foreach ($saleItems as $item) {
            $jalaliDate = Jalalian::fromDateTime($item->created_at);
            $month = $jalaliDate->getMonth();

            if (!isset($lossesByMonth[$month])) {
                $lossesByMonth[$month] = 0;
            }
            $lossesByMonth[$month] += $item->loss;
        }

        $allMonths = [
            1 => 'حمل', 2 => 'ثور', 3 => 'جوزا', 4 => 'سرطان',
            5 => 'اسد', 6 => 'سنبله', 7 => 'میزان', 8 => 'عقرب',
            9 => 'قوس', 10 => 'جدی', 11 => 'دلو', 12 => 'حوت',
        ];

        $labels = [];
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = $allMonths[$month];
            $data[] = $lossesByMonth[$month] ?? 0;
        }

        $labels = array_reverse($labels);
        $data = array_reverse($data);

        $maxValue = max($data) ?: 1000;
        $maxValue = ceil($maxValue * 1.1);

        return [
            'datasets' => [
                [
                    'label' => "ضرر ($currencyLabel)",
                    'data' => $data,
                    'borderColor' => '#dc2626',
                    'backgroundColor' => 'rgba(220, 38, 38, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 4,
                    'pointRadius' => 7,
                    'pointBackgroundColor' => '#b91c1c',
                    'pointBorderColor' => '#7f1d1d',
                    'pointHoverRadius' => 9,
                    'pointHoverBackgroundColor' => '#dc2626',
                    'pointHoverBorderColor' => '#7f1d1d',
                ],
            ],
            'labels' => $labels,
            'options' => [
                'scales' => [
                    'x' => [
                        'reverse' => true,
                        'grid' => ['display' => false],
                        'ticks' => [
                            'color' => '#991b1b',
                            'font' => ['family' => "'Vazirmatn', Tahoma, sans-serif", 'size' => 14],
                        ],
                    ],
                    'y' => [
                        'beginAtZero' => true,
                        'suggestedMax' => $maxValue,
                        'grid' => ['color' => 'rgba(220, 38, 38, 0.1)', 'borderDash' => [5, 5]],
                        'ticks' => [
                            'color' => '#991b1b',
                            'font' => ['family' => "'Vazirmatn', Tahoma, sans-serif", 'size' => 14],
                            'callback' => "function(value) { return value.toLocaleString() + ' $currencyLabel'; }",
                        ],
                    ],
                ],
                'plugins' => [
                    'legend' => [
                        'labels' => [
                            'color' => '#7f1d1d',
                            'font' => ['family' => "'Vazirmatn', Tahoma, sans-serif", 'weight' => 'bold', 'size' => 16],
                        ],
                    ],
                    'tooltip' => [
                        'enabled' => true,
                        'backgroundColor' => '#b91c1c',
                        'titleFont' => ['family' => "'Vazirmatn', Tahoma, sans-serif", 'weight' => 'bold', 'size' => 16],
                        'bodyFont' => ['family' => "'Vazirmatn', Tahoma, sans-serif", 'size' => 14],
                        'callbacks' => [
                            'label' => "function(context) { return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' $currencyLabel'; }",
                        ],
                    ],
                ],
                'animation' => ['duration' => 1200, 'easing' => 'easeOutQuart'],
                'responsive' => true,
                'maintainAspectRatio' => false,
            ],
        ];
    }

    public function getType(): string
    {
        return 'line';
    }
}
