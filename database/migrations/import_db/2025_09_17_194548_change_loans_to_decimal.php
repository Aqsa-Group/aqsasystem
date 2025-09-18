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
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('amount', 20, 10)->change();
            $table->decimal('past_amount', 20, 10)->change();
            $table->decimal('loan_recipt', 20, 10)->change();
            $table->decimal('reminded', 20, 10)->change();
        });

        
    }

    public function down(): void
    {
         Schema::table('loans', function (Blueprint $table) {
            $table->decimal('amount', 20, 10)->change();
            $table->decimal('past_amount', 20, 10)->change();
            $table->decimal('loan_recipt', 20, 10)->change();
            $table->decimal('reminded', 20, 10)->change();
        });

    }
};
