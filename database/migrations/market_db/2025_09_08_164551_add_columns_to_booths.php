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
        Schema::table('booths', function (Blueprint $table) {
            $table->string('north')->nullable();
            $table->string('east')->nullable();
            $table->string('south')->nullable();
            $table->string('west')->nullable();
            $table->string('side')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booths', function (Blueprint $table) {
            //
        });
    }
};
