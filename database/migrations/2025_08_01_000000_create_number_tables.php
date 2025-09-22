<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('number_tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number')->unique();
            $table->integer('table_capacity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('number_tables');
    }
};
