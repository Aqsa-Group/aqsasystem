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
        Schema::table('inventories', function (Blueprint $table) {
            $table->decimal('price', 20, 10)->change();
            $table->decimal('big_whole_price', 20, 10)->change();
            $table->decimal('retail_price', 20, 10)->change();
            $table->decimal('total_price', 20, 10)->change();
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->decimal('price', 20, 10)->change();
            $table->decimal('big_whole_price', 20, 10)->change();
            $table->decimal('retail_price', 20, 10)->change();
            $table->decimal('total_price', 20, 10)->change();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total_price', 20, 10)->change();
            $table->decimal('received_amount', 20, 10)->change();
            $table->decimal('remaining_amount', 20, 10)->change();
            $table->decimal('discount', 20, 10)->change();
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('profit', 20, 10)->change();
            $table->decimal('loss', 20, 10)->change();
            $table->decimal('total_price', 20, 10)->change();
            $table->decimal('price_per_unit', 20, 10)->change();
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->change();
            $table->decimal('big_whole_price', 12, 2)->change();
            $table->decimal('retail_price', 12, 2)->change();
            $table->decimal('total_price', 12, 2)->change();
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->change();
            $table->decimal('big_whole_price', 12, 2)->change();
            $table->decimal('retail_price', 12, 2)->change();
            $table->decimal('total_price', 12, 2)->change();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total_price', 12, 2)->change();
            $table->decimal('received_amount', 12, 2)->change();
            $table->decimal('remaining_amount', 12, 2)->change();
            $table->decimal('discount', 12, 2)->change();
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('profit', 12, 2)->change();
            $table->decimal('loss', 12, 2)->change();
            $table->decimal('total_price', 12, 2)->change();
            $table->decimal('price_per_unit', 12, 2)->change();
        });
    }
};
