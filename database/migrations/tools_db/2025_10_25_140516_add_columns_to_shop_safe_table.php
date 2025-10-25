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
        Schema::table('shop_safe', function (Blueprint $table) {
              $table->decimal('pkr', 20, 2)->default(0)->after('irr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_safe', function (Blueprint $table) {
            //
        });
    }
};
