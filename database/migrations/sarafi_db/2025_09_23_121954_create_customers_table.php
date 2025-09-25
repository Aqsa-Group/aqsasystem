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
            $table->string('fullname');
            $table->string('image')->nullable();
            $table->string('city');
            $table->string('phone');
            $table->string('idcard_number')->nullable()->unique();
            $table->string('account_number')->nullable()->unique();
            $table->string('whatsapp_number')->nullable();
            $table->string('type')->nullable();
            $table->string('id_card_image')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
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
