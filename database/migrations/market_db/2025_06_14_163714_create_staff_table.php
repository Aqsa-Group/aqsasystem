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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('fullname');
            $table->string('father_name');
            $table->integer('phone');
            $table->string('address');
            $table->string('job');
            $table->integer('salary');
            $table->string('id_number');
            $table->string('id_card_image');
            $table->string('profile_image');
            $table->string('warranty_image');
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
        Schema::dropIfExists('staff');
    }
};
