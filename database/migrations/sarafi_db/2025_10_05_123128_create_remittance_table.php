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
        
        Schema::create('remittance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('to_account');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('source_account', 50);
            $table->string('currency');
            $table->decimal('amount', 15, 4);
            $table->date('date');
            $table->time('clock');
            $table->string('tracking_code')->unique();
            $table->string('from_bank');
            $table->string('to_bank');
            $table->string('zone');
            $table->string('giver_name');
            $table->text('description');
            $table->string('remittance_image')->nullable();
            $table->boolean('state')->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('to_account')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remittance');
    }


};
