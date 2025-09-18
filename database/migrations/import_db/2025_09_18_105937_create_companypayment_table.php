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
        Schema::create('companypayment', function (Blueprint $table) {
           $table->id();
            $table->foreignId('company_id')->nullable()->constrained('company')->onDelete('cascade');
            $table->string('currency');
            $table->decimal('total_debt', 18, 2);
            $table->decimal('paid_amount', 18, 2);
            $table->decimal('remaining', 18, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companypayment');
    }
};
