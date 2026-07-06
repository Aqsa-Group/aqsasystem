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
        Schema::table('accountings', function (Blueprint $table) {
            $table->foreignId('exchange_id')
                ->nullable()
                ->constrained('exchanges')
                ->nullOnDelete();
                
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accountings', function (Blueprint $table) {
            //
        });
    }
};
