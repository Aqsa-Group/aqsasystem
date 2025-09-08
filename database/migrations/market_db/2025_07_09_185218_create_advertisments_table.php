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
        Schema::create('advertisments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('booth_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('property');
            $table->string('for');
            $table->string('address')->nullable();
            $table->string('width')->nullable();
            $table->string('hight')->nullable();
            $table->integer('price')->nullable();
            $table->string('currency');
            $table->json('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisments');
    }
};
