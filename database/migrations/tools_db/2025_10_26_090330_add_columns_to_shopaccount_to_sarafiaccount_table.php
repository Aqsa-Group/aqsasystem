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
        Schema::table('shopaccount_to_sarafiaccount', function (Blueprint $table) {
          $table->unsignedBigInteger('conversion_transfer_id')->nullable();
          $table->foreign('conversion_transfer_id')->references('id')->on('shop_conversion_transfer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sarafiaccount', function (Blueprint $table) {
            //
        });
    }
};
