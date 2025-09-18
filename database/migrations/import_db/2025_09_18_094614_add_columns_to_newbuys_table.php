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
        Schema::table('newbuys', function (Blueprint $table) {
          $table->foreignId('company_id')->nullable()->constrained('company')->onDelete('cascade');
          $table->decimal('amount', 20 ,10);
          $table->string('currency');
          $table->decimal('paid',20,10);
          $table->decimal('remaining',20,10);
        });        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newbuys', function (Blueprint $table) {
            //
        });
    }
};
