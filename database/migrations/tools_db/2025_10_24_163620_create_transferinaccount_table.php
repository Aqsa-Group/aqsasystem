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
        Schema::create('transferinaccount', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->unsignedBigInteger('customer_id');
            $table->string('from_currency');
            $table->decimal('buy_amount', 20, 10);
            $table->decimal('currency_rate', 20, 2);
            $table->string('to_currency');
            $table->decimal('sell_amount', 20, 10);
            $table->string('by_sender')->nullable();
            $table->string('by_receiver')->nullable();
            $table->string('description')->nullable();
            $table->date('transaction_date');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('transferinaccount');
    }
};
