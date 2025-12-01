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
        Schema::create('revenue', function (Blueprint $table) {
            $table->id();
            $table->string('currency');
            $table->decimal('profit',18,4);
            $table->decimal('lost',18,4);
            $table->string('from');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('conversion_in_account_id')->nullable();
            $table->unsignedBigInteger('conversion_transfer_in_account_id')->nullable();
            $table->unsignedBigInteger('safe_exchange_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('conversion_in_account_id')->references('id')->on('transferinaccount')->nullOnDelete();
            $table->foreign('conversion_transfer_in_account_id')->references('id')->on('conversion_transfer')->nullOnDelete();
            $table->foreign('safe_exchange_id')->references('id')->on('cash_exchange')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue');
    }
};
