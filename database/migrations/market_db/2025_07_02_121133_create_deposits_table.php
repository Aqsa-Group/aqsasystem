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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('accounting_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('booth_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('market_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('shopkeeper_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('type')->nullable();             
            $table->string('expanses_type')->nullable();  
            $table->string('meter_serial')->nullable();    
            $table->integer('past_degree')->nullable();     
            $table->integer('current_degree')->nullable();

            $table->integer('price')->nullable();     
            $table->string('currency')->nullable();          
            $table->integer('paid')->nullable();        
            $table->integer('remained')->nullable();     
            $table->date('paid_date')->nullable();           

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
