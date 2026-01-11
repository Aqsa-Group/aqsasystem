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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
               $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('SET NULL');

            $table->string('name');
            $table->string('fathername');
            $table->string('age');
            $table->string('gender');
            $table->string('phone');
            $table->string('address');
            $table->string('image')->nullable();
            $table->string('id_card')->nullable();
            $table->string('document')->nullable();
            $table->string('job');
            $table->decimal('salary_amount',20,3);
            $table->date('contract_start');
            $table->date('contract_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
