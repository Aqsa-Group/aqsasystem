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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shop_id')->nullable()->constrained('shops')->onDelete('cascade');
            $table->foreignId('market_id')->constrained('markets')->onDelete('cascade');
            $table->foreignId('shopkeeper_id')->nullable()->constrained('shopkeepers')->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->string('signed_image')->nullable();
            $table->string('rent_money')->nullable();
            $table->string('warranty_document')->nullable();
            $table->string('id_card_image')->nullable();
            $table->string('id_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
