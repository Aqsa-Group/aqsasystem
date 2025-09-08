<?php

namespace App\Filament\Import\Widgets;

use App\Models\Import\Safe;
use App\Models\Import\SaleItem;
use App\Models\Import\Withdraw;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;

class SafeOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $userId = Auth::id();
        $todayIncome = Safe::where('user_id', $userId)->sum('today');
        $totalBalance = Safe::where('user_id', $userId)->sum('total');

        $todayProfit = SaleItem::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('profit');

        $totalWithdraw = Withdraw::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('amount');

        return [

            Card::make('💰 موجودی صندوق', number_format($totalBalance) . ' افغانی')
                ->description('موجودی کل صندوق')
                ->descriptionIcon('bi-safe')
                ->color($totalBalance > 0 ? 'success' : 'danger'),

            Card::make('📆 فروشات امروز', number_format($todayIncome) . ' افغانی')
                ->description('مجموع مبالغ ثبت شده در امروز')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($todayIncome > 0 ? 'success' : 'danger'),

            Card::make('📈 فایده امروز', number_format($todayProfit) . ' افغانی')
                ->description('جمع فایده امروز از فروشات')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($todayProfit > 0 ? 'success' : 'danger'),

            Card::make('📉 مصارف امروز', number_format($totalWithdraw) . ' افغانی')
                ->description('مجموع مصارف ثبت شده امروز')
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
