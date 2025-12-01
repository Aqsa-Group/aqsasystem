<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profit_rate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->string('source_currency')->default('usd');

            $table->decimal('usd_buy_cash', 15, 2)->nullable();
            $table->decimal('usd_buy_bank', 15, 2)->nullable();
            $table->decimal('usd_sell_cash', 15, 2)->nullable();
            $table->decimal('usd_sell_bank', 15, 2)->nullable();

            $table->decimal('afn_buy_cash', 15, 2)->nullable();
            $table->decimal('afn_buy_bank', 15, 2)->nullable();
            $table->decimal('afn_sell_cash', 15, 2)->nullable();
            $table->decimal('afn_sell_bank', 15, 2)->nullable();

            $table->decimal('irr_buy_cash', 15, 2)->nullable();
            $table->decimal('irr_buy_bank', 15, 2)->nullable();
            $table->decimal('irr_sell_cash', 15, 2)->nullable();
            $table->decimal('irr_sell_bank', 15, 2)->nullable();

            $table->decimal('eur_buy_cash', 15, 2)->nullable();
            $table->decimal('eur_buy_bank', 15, 2)->nullable();
            $table->decimal('eur_sell_cash', 15, 2)->nullable();
            $table->decimal('eur_sell_bank', 15, 2)->nullable();

            $table->decimal('pkr_buy_cash', 15, 2)->nullable();
            $table->decimal('pkr_buy_bank', 15, 2)->nullable();
            $table->decimal('pkr_sell_cash', 15, 2)->nullable();
            $table->decimal('pkr_sell_bank', 15, 2)->nullable();

            $table->decimal('aed_buy_cash', 15, 2)->nullable();;
            $table->decimal('aed_buy_bank', 15, 2)->nullable();
            $table->decimal('aed_sell_cash', 15, 2)->nullable();
            $table->decimal('aed_sell_bank', 15, 2)->nullable();

            $table->decimal('cny_buy_cash', 15, 2)->nullable();
            $table->decimal('cny_buy_bank', 15, 2)->nullable();
            $table->decimal('cny_sell_cash', 15, 2)->nullable();
            $table->decimal('cny_sell_bank', 15, 2)->nullable();

            $table->decimal('try_buy_cash', 15, 2)->nullable();
            $table->decimal('try_buy_bank', 15, 2)->nullable();
            $table->decimal('try_sell_cash', 15, 2)->nullable();
            $table->decimal('try_sell_bank', 15, 2)->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('SET NULL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_rate');
    }
};
