<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('market')->create('buys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->string('property');
            $table->decimal('price', 15, 2);
            $table->string('currency', 10);
            $table->string('reduce_from')->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('hight', 8, 2)->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('market_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('market')->dropIfExists('buys');
    }

    
};

