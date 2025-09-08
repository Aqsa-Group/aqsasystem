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
        Schema::table('inventories', function (Blueprint $table) {
            // حذف ایندکس یکتا قبلی روی barcode
            $table->dropUnique('inventories_barcode_unique');

            // اضافه کردن ایندکس یکتا روی ترکیب barcode + user_id
            $table->unique(['barcode', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            // حذف ایندکس ترکیبی
            $table->dropUnique(['barcode', 'user_id']);

            // برگرداندن ایندکس یکتا روی barcode فقط
            $table->unique('barcode');
        });
    }
};
