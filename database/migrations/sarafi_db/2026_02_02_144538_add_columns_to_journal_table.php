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
        Schema::table('journal', function (Blueprint $table) {
              $table->foreignId('safe_deal_id')
                ->nullable()
                ->constrained('safe_deals_revenue')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal', function (Blueprint $table) {
            //
        });
    }
};
