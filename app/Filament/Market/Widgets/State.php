<?php

namespace App\Filament\Market\Widgets;

use App\Models\Market\Booth;
use App\Models\Market\Shop;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class State extends BaseWidget
{
    protected function getColumns(): int
    {
        return 4;
    }

    public function getCards(): array
    {
        $today = Carbon::today();
        $user = Auth::user();

        // کوئری روی جدول accountings با کانکشن market
        $query = DB::connection('market')->table('accountings')
            ->select('currency', DB::raw('SUM(paid) as total'))
            ->where('type', 'withdraw')
            ->whereDate('created_at', $today);

        if ($user->role !== 'superadmin') {
            $query->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
        }

        $withdraws = $query->groupBy('currency')->pluck('total', 'currency');

        $afn = abs($withdraws['AFN'] ?? 0);
        $usd = abs($withdraws['USD'] ?? 0);
        $eur = abs($withdraws['EUR'] ?? 0);
        $irr = abs($withdraws['IRR'] ?? 0);

        $totalWithdrawToday = $afn + $usd + $eur + $irr;

        return [
           
            Card::make('مصارف امروز', '')
                ->description(new HtmlString(
                    "<div class='grid grid-cols-2 md:grid-cols-2 space-x-3 gap-x-2'>
                        <div>افغانی</div><div class='text-right'>" . number_format($afn) . "</div>
                        <div>دالر</div><div class='text-right'>" . number_format($usd) . "</div>
                        <div>یورو</div><div class='text-right'>" . number_format($eur) . "</div>
                        <div>تومان</div><div class='text-right'>" . number_format($irr) . "</div>
                    </div>"
                ))
                ->icon('heroicon-o-currency-dollar')
                ->color('white')
                ->extraAttributes([
                    'class' => 'h-full flex flex-col justify-between bg-gradient-to-r from-green-600 to-emerald-700 text-white dark:from-green-700 dark:to-emerald-800 rounded-xl shadow-lg text-sm leading-6'
                ]),
            
        

            Card::make('تعداد دوکان‌ها', number_format(
                Shop::query()
                    ->when($user->role !== 'superadmin', fn($q) => $q->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id))
                    ->count()
            ))
                ->icon('heroicon-o-building-library')
                ->extraAttributes([
                    'class' => 'h-full flex flex-col justify-between bg-gradient-to-r from-purple-400 to-purple-700 text-white dark:from-purple-600 dark:to-purple-900 rounded-xl shadow-lg'
                ]),

            Card::make('تعداد دوکان‌های سرقفلی', number_format(
                Shop::query()
                    ->where('sarqofli','بلی')
                    ->when($user->role !== 'superadmin', fn($q) => $q->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id))
                    ->count()
            ))
                ->icon('heroicon-o-user')
                ->extraAttributes([
                    'class' => 'h-full flex flex-col justify-between bg-gradient-to-r from-pink-600 to-rose-700 text-white dark:from-pink-700 dark:to-rose-800 rounded-xl shadow-lg'
                ]),

            Card::make('دوکان‌های کرایه شده', number_format(
                Shop::query()
                    ->where('type', 'کرایه')
                    // ->whereNull('shopkeeper_id')
                    ->when($user->role !== 'superadmin', fn($q) => $q->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id))
                    ->count()
            ))
                ->icon('heroicon-o-exclamation-circle')
                ->extraAttributes([
                    'class' => 'h-full flex flex-col justify-between bg-gradient-to-r from-yellow-400 to-yellow-600 text-black dark:from-yellow-500 dark:to-yellow-700 dark:text-white rounded-xl shadow-lg'
                ]),

            Card::make('دوکان‌های گروی شده', number_format(
                Shop::query()
                    ->where('rent','بلی')
                    ->whereNull('shopkeeper_id')
                    ->when($user->role !== 'superadmin', fn($q) => $q->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id))
                    ->count()
            ))
                ->icon('heroicon-o-exclamation-circle')
                ->extraAttributes([
                    'class' => 'h-full flex flex-col justify-between bg-gradient-to-r from-blue-500 to-blue-700 text-white dark:from-blue-600 dark:to-blue-800 rounded-xl shadow-lg'
                ]),

            Card::make('دوکان‌های خالی', number_format(
                Shop::query()
                    ->whereNull('shopkeeper_id')
                    ->when($user->role !== 'superadmin', fn($q) => $q->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id))
                    ->count()
            ))
                ->icon('heroicon-o-exclamation-circle')
                ->extraAttributes([
                    'class' => 'h-full flex flex-col justify-between bg-gradient-to-r from-red-500 to-red-700 text-white dark:from-red-600 dark:to-red-800 rounded-xl shadow-lg'
                ]),

            Card::make('تعداد غرفه ها', number_format(
                Booth::query()
                    ->when($user->role !== 'superadmin', fn($q) => $q->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id))
                    ->count()
            ))
                ->icon('heroicon-o-user')
                ->extraAttributes([
                    'class' => 'h-full flex flex-col justify-between bg-gradient-to-r from-indigo-600 to-indigo-800 text-white dark:from-indigo-700 dark:to-indigo-900 rounded-xl shadow-lg'
                ]),

            Card::make('تعداد غرفه ها خالی', number_format(
                Booth::query()
                    ->whereNull('shopkeeper_id')
                    ->when($user->role !== 'superadmin', fn($q) => $q->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id))
                    ->count()
            ))
                ->icon('heroicon-o-user')
                ->extraAttributes([
                    'class' => 'h-full flex flex-col justify-between bg-gradient-to-r from-teal-500 to-teal-700 text-white dark:from-teal-600 dark:to-teal-800 rounded-xl shadow-lg'
                ]),
        ];
    }
}
