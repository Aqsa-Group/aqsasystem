<?php

namespace App\Filament\Import\Widgets;

use App\Models\Import\Customer;
use App\Models\Import\Inventory;
use App\Models\Import\Safe;
use App\Models\Import\Sale;
use App\Models\Import\SaleItem;
use App\Models\Import\Sarafi;
use App\Models\Import\Warehouse;
use App\Models\Import\Withdraw;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;

class SafeOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $userId = Auth::id();
        $timezone = 'Asia/Kabul';

        // --- تاریخ امروز کابل ---
        $today = Carbon::now($timezone)->startOfDay();        // ساعت ۰۰:۰۰
        $tomorrow = Carbon::now($timezone)->addDay()->startOfDay(); // فردا ساعت ۰۰:۰۰

        // // --- موجودی فروش امروز از ستون today ---
        // // همیشه یک ردیف برای هر کاربر وجود دارد
        // $safeRow = Safe::firstOrCreate(
        //     ['user_id' => $userId], 
        //     ['today' => 0, 'total' => 0, 'AFN' => 0, 'USD' => 0, 'currency' => 0]
        // );

        $todayIncome = Sale::where('user_id', $userId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('total_price');

        // --- جمع فایده امروز (فقط امروز) ---
        $todayProfit = SaleItem::where('user_id', $userId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('profit');

        // --- جمع برداشت امروز (فقط امروز) ---
        $totalWithdraw = Withdraw::where('user_id', $userId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('amount');

        // --- موجودی‌ها ---
        $totalBalance = $safeRow->total;
        $totalBalanceAFN = $safeRow->AFN;
        $totalBalanceUSD = $safeRow->USD;
        $totalInventoryBalance  = Inventory::where('user_id', $userId)->sum('total_price');
        $totalWarehouseBalance  = Warehouse::where('user_id', $userId)->sum('total_price');
        $totalBoot  = $totalInventoryBalance + $totalWarehouseBalance + $totalBalance;

        // --- صرافی ---
        $AFN = Sarafi::sum('AFN');
        $USD = Sarafi::sum('USD');
        $CNY = Sarafi::sum('CNY');
        $EUR = Sarafi::sum('EUR');

        // --- قرضه‌ها ---
        $loan = Customer::where('user_id', $userId)->sum('total_loan');
        $totalReceipt = Customer::where('user_id', $userId)->sum('total_receipt');
        $totalLoan = $loan - $totalReceipt;

        // --- واحد پول ---
        $safeCurrency = $safeRow->currency ?? 0;
        $currencyLabel = $safeCurrency ? 'دالر' : 'افغانی';
        $decimals = $safeCurrency ? 2 : 0;

        return [


            Card::make('💰 موجودی صندوق', '')
                ->description(new HtmlString("
                    <div class='grid grid-cols-2 gap-x-4 text-2xl'>
                        <div class='text-black dark:text-white font-bold'>افغانی</div>
                        <div class='text-right text-black dark:text-white font-bold'>". number_format($totalBalanceAFN, 0) ."</div>

                        <div class='text-black dark:text-white font-bold'>دالر</div>
                        <div class='text-right text-black dark:text-white font-bold'>". number_format($totalBalanceUSD, 2) ."</div>
                    </div>
                "))
                ->color(($totalBalanceAFN + $totalBalanceUSD) > 0 ? 'success' : 'danger'),

            Card::make('💰 موجودی صندوق صرافی', '')
                ->description(new HtmlString("
                    <div class='grid grid-cols-2 gap-x-4 text-2xl'>
                        <div class='text-black dark:text-white font-bold'>افغانی</div>
                        <div class='text-right text-black dark:text-white font-bold'>". number_format($AFN, 0) ."</div>

                        <div class='text-black dark:text-white font-bold'>دالر</div>
                        <div class='text-right text-black dark:text-white font-bold'>". number_format($USD, 2) ."</div>

                        <div class='text-black dark:text-white font-bold'>ین چین</div>
                        <div class='text-right text-black dark:text-white font-bold'>". number_format($CNY, 2) ."</div>

                        <div class='text-black dark:text-white font-bold'>یورو</div>
                        <div class='text-right text-black dark:text-white font-bold'>". number_format($EUR, 2) ."</div>
                    </div>
                "))
                ->color(($AFN + $USD + $CNY + $EUR) > 0 ? 'success' : 'danger'),

            Card::make('📆 فروشات امروز', number_format($todayIncome, $decimals) . " $currencyLabel")
                 ->description('مجموع مبالغ ثبت شده در امروز')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($todayIncome > 0 ? 'info' : 'danger'),  

            Card::make('📈 فایده امروز', number_format($todayProfit, $decimals) . " $currencyLabel")
                ->description('جمع فایده امروز')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($todayProfit > 0 ? 'success' : 'warning'),

            Card::make('📉 مصارف امروز', number_format($totalWithdraw, $decimals) . " $currencyLabel")
                ->description('جمع برداشت امروز')
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),


            Card::make('🏪 موجودی سرمایه گدام', number_format($totalInventoryBalance, $decimals) . " $currencyLabel")
                ->description('موجودی کل گدام')
                ->descriptionIcon('heroicon-o-archive-box')
                ->url(route('filament.import.resources.warehouses.index'))
                ->color($totalInventoryBalance > 0 ? 'primary' : 'danger'),

            Card::make('🏬 موجودی سرمایه دوکان', number_format($totalWarehouseBalance, $decimals) . " $currencyLabel")
                ->description('موجودی کل دوکان')
                ->descriptionIcon('heroicon-o-building-storefront')
                ->url(route('filament.import.resources.warehouses.index'))
                ->color($totalWarehouseBalance > 0 ? 'secondary' : 'danger'),

            Card::make('💎 سرمایه فعلی', number_format($totalBoot, $decimals) . " $currencyLabel")
                ->description('موجودی کل فعلی')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Card::make('📜 مجموعه قرضه‌ها', number_format($totalLoan, $decimals) . " $currencyLabel")
                ->description('مجموعه قرضه‌ها')
                ->descriptionIcon('heroicon-o-document-text')
                ->url(route('filament.import.resources.loans.index'))
                ->color($totalLoan > 0 ? 'warning' : 'success'),
        ];
    }
}
