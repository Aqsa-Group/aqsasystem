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
        Schema::create('withdraw', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->string('expanses_type');
            $table->string('amount');
            $table->string('currency');
            $table->date('date');
            $table->string('description');
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw');
    }
};
