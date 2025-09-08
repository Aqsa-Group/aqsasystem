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
        Schema::create('deposit_logs', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('deposit_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('expanses_type')->nullable(); // نوع هزینه
            $table->foreignId('market_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('shopkeeper_id')->nullable()->constrained()->onDelete('set null');
        
            $table->integer('old_paid')->nullable();
            $table->integer('old_remained')->nullable();
            $table->integer('new_paid')->nullable();
            $table->integer('new_remained')->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_logs');
    }
};
