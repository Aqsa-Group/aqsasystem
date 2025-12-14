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
        Schema::create('changerdeals', function (Blueprint $table) {
            $table->id();
            $table->integer('remittance_number');
            $table->unsignedBigInteger('from_customer')->nullable();
            $table->unsignedBigInteger('to_customer')->nullable();
            $table->unsignedBigInteger('from_sarafi')->nullable();
            $table->unsignedBigInteger('to_sarafi')->nullable();
            $table->string('currency');
            $table->string('zone');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('account_type');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('SET NULL');

            $table->foreign('from_customer')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('to_customer')->references('id')->on('customers')->onDelete('cascade');


            $table->foreign('from_sarafi')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('to_sarafi')->references('id')->on('users')->onDelete('SET NULL');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changerdeals');
    }
};
