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
        Schema::create('exchange_rate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->string('source_currency')->default('usd');

            $table->decimal('usd_buy', 15, 2)->nullable();
            $table->decimal('usd_sell', 15, 2)->nullable();
            $table->decimal('afn_buy', 20, 2)->default(0);
            $table->decimal('afn_sell', 20, 2)->default(0);

            $table->decimal('irr_buy', 20, 2)->default(0);
            $table->decimal('irr_sell', 20, 2)->default(0);

            $table->decimal('eur_buy', 20, 2)->default(0);
            $table->decimal('eur_sell', 20, 2)->default(0);

            $table->decimal('pkr_buy', 20, 2)->default(0);
            $table->decimal('pkr_sell', 20, 2)->default(0);

            $table->decimal('aed_buy', 20, 2)->default(0);
            $table->decimal('aed_sell', 20, 2)->default(0);

            $table->decimal('cny_buy', 20, 2)->default(0);
            $table->decimal('cny_sell', 20, 2)->default(0);

            $table->decimal('try_buy', 20, 2)->default(0);
            $table->decimal('try_sell', 20, 2)->default(0);

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
        Schema::dropIfExists('exchange_rate');
    }
};
