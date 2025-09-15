<?php

namespace App\Filament\Import\Widgets;

use App\Models\Import\Customer;
use App\Models\Import\Inventory;
use App\Models\Import\Safe;
use App\Models\Import\SaleItem;
use App\Models\Import\Warehouse;
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
        $totalInventortBalance  = Inventory::where('user_id', $userId)->sum('total_price');
        $totalWarehouseBalance  =Warehouse::where('user_id', $userId)->sum('total_price');
        $totalBoot  =$totalInventortBalance + $totalWarehouseBalance + $totalBalance;
        $loan = Customer::where('user_id', $userId)->sum('total_loan');
        $totalrecipt = Customer::where('user_id', $userId)->sum('total_receipt');
        $totalloan= $loan - $totalrecipt;
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
                        // ->url(route('filament.import.pages.sales-reports'))
                        ->color($todayIncome > 0 ? 'info' : 'danger'),

                    Card::make('📈 فایده امروز', number_format($todayProfit) . ' افغانی')
                        ->description('جمع فایده امروز از فروشات')
                        ->descriptionIcon('heroicon-o-chart-bar')
                        ->color($todayProfit > 0 ? 'success' : 'warning'),

                    Card::make('📉 مصارف امروز', number_format($totalWithdraw) . ' افغانی')
                        ->description('مجموع مصارف ثبت شده امروز')
                        // ->url(route('filament.import.pages.sales-reports'))
                        ->descriptionIcon('heroicon-o-arrow-trending-down')
                        ->color('danger'),

                    Card::make('🏪 موجودی سرمایه گدام', number_format($totalInventortBalance) . ' افغانی')
                        ->description('موجودی کل گدام')
                        ->descriptionIcon('heroicon-o-archive-box')
                        ->url(route('filament.import.resources.warehouses.index'))
                        ->color($totalInventortBalance > 0 ? 'primary' : 'danger'),

                    Card::make('🏬 موجودی سرمایه دوکان', number_format($totalWarehouseBalance) . ' افغانی')
                        ->description('موجودی کل دوکان')
                        ->descriptionIcon('heroicon-o-building-storefront')
                        ->url(route('filament.import.resources.warehouses.index'))
                        ->color($totalWarehouseBalance > 0 ? 'secondary' : 'danger'),

                    Card::make('💎 سرمایه فعلی', number_format($totalBoot) . ' افغانی')
                        ->description('موجودی کل فعلی')
                        ->descriptionIcon('heroicon-o-currency-dollar')
                        ->color('success'),

                    Card::make('📜 مجموعه قرضه ها', number_format($totalloan) . ' افغانی')
                        ->description('مجموعه قرضه‌ها')
                        ->descriptionIcon('heroicon-o-document-text')
                        ->url(route('filament.import.resources.loans.index'))
                        ->color($totalloan > 0 ? 'warning' : 'success'),


                            

        ];
        
    }
}
