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
        Schema::create('safes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accounting_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('af');
            $table->integer('us');
            $table->integer('er');
            $table->integer('ir');
            $table->integer('power');
            $table->integer('water');
            $table->integer('rent');
            $table->integer('tax');
            $table->integer('safai');
            $table->integer('parking');
            $table->integer('customer');
            $table->integer('outside');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safes');
    }
};
