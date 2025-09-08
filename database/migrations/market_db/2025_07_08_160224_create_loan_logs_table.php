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
        Schema::create('loan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('person');
            $table->unsignedBigInteger('related_id'); // ID شخص (مشتری، کارمند، دوکاندار)
            $table->string('related_type'); // customer / staff / shopkeeper
            $table->string('currency');
            $table->integer('amount');
            $table->string('expanses_type');
            $table->text('description')->nullable();
            $table->timestamp('date');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_logs');
    }
};
