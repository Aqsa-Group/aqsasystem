<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            $table->date('attendance_date'); 
            $table->string('morning_time')->nullable(); 
            $table->string('evening_time')->nullable(); 
            $table->boolean('morning_present')->default(false);
            $table->boolean('evening_present')->default(false);
            $table->enum('leave_type', ['none', 'morning', 'evening', 'full_day'])->default('none');
            $table->boolean('is_paid')->default(true);
            $table->decimal('daily_salary', 15, 2)->default(0);
            $table->text('note')->nullable(); // توضیحات
            $table->timestamps();
            
            $table->unique(['staff_id', 'attendance_date']);
            $table->index('attendance_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendances');
    }
};