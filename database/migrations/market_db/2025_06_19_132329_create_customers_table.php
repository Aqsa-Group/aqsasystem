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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('fullname');
            $table->string('father_name');
            $table->string('grand_father');
            $table->integer('phone');
            $table->string('address');
            $table->unsignedBigInteger('balance_afn')->default(0);
            $table->unsignedBigInteger('balance_usd')->default(0);
            $table->unsignedBigInteger('balance_eur')->default(0);
            $table->unsignedBigInteger('balance_irr')->default(0);
            $table->string('job');
            $table->string('id_number');
            $table->string('id_card_image');
            $table->string('profile_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
