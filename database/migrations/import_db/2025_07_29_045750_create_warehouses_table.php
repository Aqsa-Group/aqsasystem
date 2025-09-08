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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('inventory_id')->nullable()
            ->constrained('inventories')
            ->onDelete('cascade');
            $table->string('barcode')->unique()->nullable();
            $table->string('name');
            $table->integer('quantity')->nullable();
            $table->integer('total_price');
            $table->integer('price');
            $table->integer('big_quantity')->nullable();
            $table->integer('big_unit_price')->nullable();
            $table->integer('all_exist_number');
            $table->string('unit');
            $table->string('brand');
            $table->integer('big_whole_price');
            $table->integer('retail_price');
            $table->string('product_image')->nullable();
            $table->string('import_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
