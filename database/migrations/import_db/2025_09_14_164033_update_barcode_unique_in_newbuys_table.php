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
        Schema::table('newbuys', function (Blueprint $table) {
            // حذف ایندکس unique قدیمی
            $table->dropUnique('newbuys_barcode_unique');

            // ایجاد unique ترکیبی
            $table->unique(['user_id', 'barcode']);
        });
    }

    public function down(): void
    {
        Schema::table('newbuys', function (Blueprint $table) {
            // حذف unique ترکیبی
            $table->dropUnique(['user_id', 'barcode']);

            // برگرداندن unique فقط روی barcode
            $table->unique('barcode');
        });
    }
};
