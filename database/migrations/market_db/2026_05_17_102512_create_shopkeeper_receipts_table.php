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
        Schema::create('shopkeeper_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('market_id')
                ->nullable()
                ->constrained('markets')
                ->nullOnDelete();

            $table->foreignId('shopkeeper_id')
                ->nullable()
                ->constrained('shopkeepers')
                ->nullOnDelete();

                $table->foreignId('shop_id')
                ->nullable()
                ->constrained('shops')
                ->nullOnDelete();

                  $table->foreignId('booth_id')
                ->nullable()
                ->constrained('booths')
                ->nullOnDelete();

            $table->integer('amount');

            $table->string('currency', 10);
            $table->string('expanses_type');


            $table->text('description')->nullable();

            $table->date('date');

            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopkeeper_receipts');
    }
};
