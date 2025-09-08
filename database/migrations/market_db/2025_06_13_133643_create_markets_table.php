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
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('location', 400);
            $table->integer('total_shop');
            $table->integer('floor');
            $table->string('booth');
            $table->integer('booth_number')->nullable();
            $table->string('stock');
            $table->string('parking');
            $table->string('market_owner');
            $table->timestamps();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
