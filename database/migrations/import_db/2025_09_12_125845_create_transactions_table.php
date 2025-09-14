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
            $table->foreignId('sarafi_id')->nullable()->constrained('sarafi')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade');
            $table->foreignId('safe_id')->nullable()->constrained('safes')->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('staff')->onDelete('cascade');
            $table->string('type');
            // $table->string('sarafi');
            $table->string('person');
            $table->string('name_others')->nullable();
            $table->string('currency');
            // $table->string('to_currency')->nullable();
            // $table->decimal('price')->nullable();
            $table->string('transaction_number')->nullable()->unique();
            $table->decimal('amount');
            $table->date('date')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
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
