<?php

namespace App\Filament\Import\Widgets;

use App\Models\Import\SaleItem;
use App\Models\Import\Safe;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class MonthlyProfitChart extends ChartWidget
{
    protected static ?string $heading = '📈 فایده ماهانه';

    protected function getData(): array
    {
        $userId = Auth::id();

        // دریافت ارز جاری از safe
        $safe = Safe::where('user_id', $userId)->latest()->first();
        $currencyLabel = ($safe && $safe->currency) ? 'USD' : 'AFN';

        $saleItems = SaleItem::where('user_id', $userId)->get();
        $profitsByMonth = [];

        foreach ($saleItems as $item) {
            $jalaliDate = Jalalian::fromDateTime($item->created_at);
            $month = $jalaliDate->getMonth();

            if (!isset($profitsByMonth[$month])) {
                $profitsByMonth[$month] = 0;
            }
            $profitsByMonth[$month] += $item->profit;
        }

        $allMonths = [
            1 => 'حمل',
            2 => 'ثور',
            3 => 'جوزا',
            4 => 'سرطان',
            5 => 'اسد',
            6 => 'سنبله',
            7 => 'میزان',
            8 => 'عقرب',
            9 => 'قوس',
            10 => 'جدی',
            11 => 'دلو',
            12 => 'حوت',
        ];

        $labels = [];
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = $allMonths[$month];
            $data[] = $profitsByMonth[$month] ?? 0;
        }

        $labels = array_reverse($labels);
        $data = array_reverse($data);

        $maxValue = max($data) ?: 1000;
        $maxValue = ceil($maxValue * 1.1);

        return [
            'datasets' => [
                [
                    'label' => "فایده ($currencyLabel)",
                    'data' => $data,
                    'backgroundColor' => function ($context) {
                        $colors = [
                            'rgba(16, 185, 129, 0.4)',
                            'rgba(16, 185, 129, 0.6)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(16, 185, 129, 1)',
                        ];
                        $value = $context->dataset->data[$context->dataIndex] ?? 0;
                        return $value > 0 ? $colors[min(floor($value / 1000), 3)] : 'rgba(16, 185, 129, 0.3)';
                    },
                    'borderRadius' => 8,
                    'barPercentage' => 0.7,
                    'borderColor' => '#059669',
                    'borderWidth' => 2,
                    'hoverBackgroundColor' => '#047857',
                    'hoverBorderColor' => '#065f46',
                ],
            ],
            'labels' => $labels,
            'options' => [
                'scales' => [
                    'x' => [
                        'reverse' => true,
                        'grid' => ['display' => false],
                        'ticks' => [
                            'color' => '#065f46',
                            'font' => ['family' => "'Vazirmatn', Tahoma, sans-serif", 'size' => 14],
                        ],
                    ],
                    'y' => [
                        'beginAtZero' => true,
                        'suggestedMax' => $maxValue,
                        'grid' => ['color' => 'rgba(0,0,0,0.05)', 'borderDash' => [5, 5]],
                        'ticks' => [
                            'color' => '#065f46',
                            'font' => ['family' => "'Vazirmatn', Tahoma, sans-serif", 'size' => 14],
                            'callback' => "function(value) { return value.toLocaleString() + ' $currencyLabel'; }",
                        ],
                    ],
                ],
                'plugins' => [
                    'legend' => [
                        'labels' => [
                            'color' => '#065f46',
                            'font' => ['family' => "'Vazirmatn', Tahoma, sans-serif", 'weight' => 'bold', 'size' => 16],
                        ],
                    ],
                    'tooltip' => [
                        'enabled' => true,
                        'backgroundColor' => '#059669',
                        'titleFont' => ['family' => "'Vazirmatn', Tahoma, sans-serif", 'weight' => 'bold', 'size' => 16],
                        'bodyFont' => ['family' => "'Vazirmatn', Tahoma, sans-serif", 'size' => 14],
                        'callbacks' => [
                            'label' => "function(context) { return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' $currencyLabel'; }",
                        ],
                    ],
                ],
                'animation' => ['duration' => 1000, 'easing' => 'easeOutQuart'],
                'responsive' => true,
                'maintainAspectRatio' => false,
            ],
        ];
    }

    public function getType(): string
    {
        return 'bar';
    }
}
