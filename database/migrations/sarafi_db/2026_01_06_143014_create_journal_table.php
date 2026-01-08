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
        Schema::create('journal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('currency');
            $table->string('type');
            $table->string('account_type');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->decimal('balance', 15, 2);
            $table->text('description')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained('transactions')
                ->cascadeOnDelete();


            $table->foreignId('account_to_account_id')
                ->nullable()
                ->constrained('account_to_account')
                ->cascadeOnDelete();


            $table->foreignId('conversion_in_account_id')
                ->nullable()
                ->constrained('transferinaccount')
                ->cascadeOnDelete();



            $table->foreignId('conversion_transfer_id')
                ->nullable()
                ->constrained('conversion_transfer')
                ->cascadeOnDelete();




            $table->foreignId('buysell_id')
                ->nullable()
                ->constrained('cash_exchange')
                ->cascadeOnDelete();


            $table->foreignId('withdrawbank_id')
                ->nullable()
                ->constrained('withdrawbank')
                ->cascadeOnDelete();


            $table->foreignId('changerdeals_id')
                ->nullable()
                ->constrained('changerdeals')
                ->cascadeOnDelete();




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jouranl');
    }
};
