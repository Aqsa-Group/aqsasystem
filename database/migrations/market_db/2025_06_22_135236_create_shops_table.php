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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained()->onDelete('cascade');
            $table->foreignId('shopkeeper_id')->nullable()->constrained('shopkeepers')->onDelete('set null');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_sell')->default(false);
            $table->integer('number');
            $table->string('floor');
            $table->string('size');
            $table->string('type');
            $table->integer('price')->nullable();
            $table->string('fa_price')->nullable();
            $table->integer('half_price')->nullable();
            $table->string('north')->nullable();
            $table->string('east')->nullable();
            $table->string('south')->nullable();
            $table->string('west')->nullable();
            $table->string('side')->nullable();
            $table->string('metar_serial')->unique();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
