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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('reservation_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('table_number');
        $table->decimal('total_price', 10, 2);
        $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
        $table->timestamps();
        
        $table->foreign('table_number')->references('table_number')->on('number_tables')->onDelete('restrict');
        
        $table->index('reservation_id');
        $table->index('user_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
