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
         Schema::create('account_to_account', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('بدون تفاوت');
            $table->unsignedBigInteger('form_customer');
            $table->string('currency');
            $table->decimal('withdrawal_amount', 20, 10);
            $table->decimal('tax_amount', 20, 10)->nullable();
            $table->decimal('received_amount', 20, 10)->nullable();
            $table->unsignedBigInteger('to_customer');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->string('by_sender')->nullable();
            $table->string('by_receiver')->nullable();
            $table->string('zone_sender')->nullable();
            $table->string('zone_receiver')->nullable();
            $table->text('description_sender')->nullable();
            $table->text('description_receiver')->nullable();
            $table->date('transaction_date');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('status')->default('completed');
            $table->string('tracking_code')->nullable();
            
            // کلیدهای خارجی
            $table->foreign('form_customer')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('to_customer')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('SET NULL');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_to_account');
    }
};
