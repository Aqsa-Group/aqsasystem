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
        Schema::create('shopkeepers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('fullname');
            $table->string('father_name');
            $table->string('grand_father');
            $table->string('username');
            $table->string('password');
            $table->string('address');
            $table->integer('phone');
            $table->string('shop_activity');
            $table->string('national_id');
            $table->integer('contract_number');
            $table->date('contract_start');
            $table->date('contract_end');
            $table->string('contract_duration');
            $table->string('warranty_document');
            $table->string('id_image');
            $table->string('shopkeeper_image');
            $table->string('property_type')->nullable();
            $table->foreignId('market_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopkeepers');
    }
};
