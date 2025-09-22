<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (config('database.default') === 'mysql') {
            DB::statement('ALTER TABLE number_tables MODIFY table_capacity INTEGER');
        }
        elseif (config('database.default') === 'pgsql') {
            DB::statement('ALTER TABLE number_tables ALTER COLUMN table_capacity TYPE INTEGER USING table_capacity::integer');
        }
        else {
            Schema::table('number_tables', function (Blueprint $table) {
                $table->integer('new_table_capacity')->after('table_capacity');
            });
            
            DB::table('number_tables')->update([
                'new_table_capacity' => DB::raw('CAST(table_capacity AS INTEGER)')
            ]);
            
            Schema::table('number_tables', function (Blueprint $table) {
                $table->dropColumn('table_capacity');
                $table->renameColumn('new_table_capacity', 'table_capacity');
            });
        }
    }

    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            DB::statement('ALTER TABLE number_tables MODIFY table_capacity VARCHAR(255)');
        }
        elseif (config('database.default') === 'pgsql') {
            DB::statement('ALTER TABLE number_tables ALTER COLUMN table_capacity TYPE VARCHAR(255)');
        }
        else {
            Schema::table('number_tables', function (Blueprint $table) {
                $table->string('new_table_capacity')->after('table_capacity');
            });
            
            DB::table('number_tables')->update([
                'new_table_capacity' => DB::raw('CAST(table_capacity AS VARCHAR)')
            ]);
            
            Schema::table('number_tables', function (Blueprint $table) {
                $table->dropColumn('table_capacity');
                $table->renameColumn('new_table_capacity', 'table_capacity');
            });
        }
    }
};