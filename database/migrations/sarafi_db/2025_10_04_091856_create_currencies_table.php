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
            Schema::create('currencies', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->decimal('usd', 20, 2)->default(0);
                $table->decimal('afn', 20, 2)->default(0);
                $table->decimal('eur', 20, 2)->default(0);
                $table->decimal('irr', 20, 2)->default(0);
                $table->decimal('aed', 20, 2)->default(0);
                $table->decimal('try', 20, 2)->default(0);
                $table->decimal('cny', 20, 2)->default(0);
                $table->decimal('pkr', 20, 2)->default(0);
                $table->decimal('gbp', 20, 2)->default(0);
                $table->decimal('jpy', 20, 2)->default(0);
                $table->decimal('sar', 20, 2)->default(0);
                $table->decimal('inr', 20, 2)->default(0);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
