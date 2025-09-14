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
        Schema::create('sarafi', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->integer('phone')->nullable();   
            $table->decimal('AFN')->nullable();
            $table->decimal('USD')->nullable();
            $table->decimal('CNY')->nullable();
            $table->decimal('EUR')->nullable();
            $table->decimal('IRR')->nullable();
            $table->decimal('PKR')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarafi');
    }
};
