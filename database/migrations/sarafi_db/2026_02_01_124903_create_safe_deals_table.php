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
        Schema::create('safe_deals', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->string('from_currency');
            $table->string('to_currency');
            $table->decimal('withdraw_amount', 20, 10);
            $table->decimal('currency_rate', 10, 4);
            $table->decimal('receive_amount', 20, 10);
            $table->date('date');
            $table->text('description');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safe_deals');
    }
};
