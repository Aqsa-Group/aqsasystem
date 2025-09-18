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
        Schema::create('exchange', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('person')->nullable();
            $table->string('name_others')->nullable();
            $table->foreignId('sarafi_id')->nullable()->constrained('sarafi')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade');
            $table->foreignId('safe_id')->nullable()->constrained('safes')->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('staff')->onDelete('cascade');
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->decimal('amount')->nullable();
            $table->decimal('today_price')->nullable();
            $table->decimal('total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange');
    }
};
