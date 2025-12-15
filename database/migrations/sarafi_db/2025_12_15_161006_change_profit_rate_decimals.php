<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profit_rate', function (Blueprint $table) {

            $columns = [
                'usd_buy_cash','usd_buy_bank','usd_sell_cash','usd_sell_bank',
                'afn_buy_cash','afn_buy_bank','afn_sell_cash','afn_sell_bank',
                'irr_buy_cash','irr_buy_bank','irr_sell_cash','irr_sell_bank',
                'eur_buy_cash','eur_buy_bank','eur_sell_cash','eur_sell_bank',
                'pkr_buy_cash','pkr_buy_bank','pkr_sell_cash','pkr_sell_bank',
                'aed_buy_cash','aed_buy_bank','aed_sell_cash','aed_sell_bank',
                'cny_buy_cash','cny_buy_bank','cny_sell_cash','cny_sell_bank',
                'try_buy_cash','try_buy_bank','try_sell_cash','try_sell_bank',
            ];

            foreach ($columns as $column) {
                $table->decimal($column, 15, 8)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('profit_rate', function (Blueprint $table) {

            $columns = [
                'usd_buy_cash','usd_buy_bank','usd_sell_cash','usd_sell_bank',
                'afn_buy_cash','afn_buy_bank','afn_sell_cash','afn_sell_bank',
                'irr_buy_cash','irr_buy_bank','irr_sell_cash','irr_sell_bank',
                'eur_buy_cash','eur_buy_bank','eur_sell_cash','eur_sell_bank',
                'pkr_buy_cash','pkr_buy_bank','pkr_sell_cash','pkr_sell_bank',
                'aed_buy_cash','aed_buy_bank','aed_sell_cash','aed_sell_bank',
                'cny_buy_cash','cny_buy_bank','cny_sell_cash','cny_sell_bank',
                'try_buy_cash','try_buy_bank','try_sell_cash','try_sell_bank',
            ];

            foreach ($columns as $column) {
                $table->decimal($column, 15, 2)->nullable()->change();
            }
        });
    }
};
