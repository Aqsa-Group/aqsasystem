<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_number')->nullable();
            $table->enum('sale_type', ['retail', 'wholesale']);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->string('buyer_name')->nullable();
            $table->decimal('received_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->boolean('is_return')->default(false);
            $table->text('return_reason')->nullable();
            $table->unsignedBigInteger('original_sale_id')->nullable();
            $table->foreign('original_sale_id')->references('id')->on('sales')->onDelete('set null');
            $table->decimal('final_profit', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};