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
        Schema::table('shops', function (Blueprint $table) {
            
             $table->string('sarqofli')->nullable(); 
             $table->string('sarqofli_time')->nullable(); 
             $table->integer('sarqofli_price')->nullable();
             $table->string('sarqofli_fa_price')->nullable();
             $table->integer('sarqofli_half_price')->nullable();
             $table->string('rent')->nullable();
             $table->string('rent_time')->nullable(); 
             $table->integer('rent_price')->nullable();
             $table->string('rent_fa_price')->nullable();
             $table->integer('rent_half_price')->nullable();
             $table->date('contract_start')->nullable();
             $table->date('contract_end')->nullable();
             $table->string('contract_duration')->nullable();
             $table->string('collect')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            //
        });
    }
};
