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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('conversion_transfer_id')->nullable();
            $table->unsignedBigInteger('conversion_in_account_id')->nullable();
            $table->foreign('conversion_in_account_id')->references('id')->on('transferinaccount')->onDelete('cascade');
            $table->foreign('conversion_transfer_id')->references('id')->on('conversion_transfer')->onDelete('cascade');
            $table->unsignedBigInteger('account_to_id')->nullable();
            $table->foreign('account_to_id')->references('id')->on('account_to_account')->onDelete('cascade');
            $table->string('currency');
            $table->string('zone');
            $table->string('by');
            $table->decimal('amount', 15, 2);
            $table->string('type');
             $table->string('account_type');
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('transaction_file')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
