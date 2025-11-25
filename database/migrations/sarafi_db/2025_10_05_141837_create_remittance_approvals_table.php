<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remittance_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remittance_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('to_account');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('source_account', 50);
            $table->string('currency');
            $table->decimal('amount');
            $table->date('date');
            $table->time('clock');
            $table->string('tracking_code')->unique();
            $table->string('from_bank');
            $table->string('to_bank');
            $table->string('zone');
            $table->string('giver_name');
            $table->text('description');
            $table->string('remittance_image')->nullable();
            $table->boolean('approved')->default(0); 
            $table->unsignedBigInteger('approved_by')->nullable(); 
            $table->timestamp('approved_at')->nullable(); 
            $table->text('approval_notes')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('remittance_id')->references('id')->on('remittance')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('to_account')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remittance_approvals');
    }
};