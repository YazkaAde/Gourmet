<?php
// [file name]: 2025_10_09_141500_update_payment_method_enum_in_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('cash', 'bank_transfer', 'e_wallet', 'qris') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('cash', 'bank_transfer', 'debit_card', 'qris') NOT NULL");
    }
};