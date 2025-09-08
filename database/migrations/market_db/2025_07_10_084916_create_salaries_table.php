<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('staff_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('loan_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('reduce_from')->nullable(); 
            $table->string('currency')->nullable(); 
            $table->integer('salary')->nullable();    
            $table->integer('paid')->nullable();        
            $table->integer('remained')->nullable();     
            $table->integer('loan')->nullable();
            $table->boolean('is_reduce')->nullable();
            $table->integer('reduce_loan')->nullable();
            $table->integer('new_loan')->nullable();
            $table->date('paid_date')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
