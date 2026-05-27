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
        $tables = [
            'products',
            'warehouses',
            'locations',
            'product_categories',
            'stock_moves',
            'stock_transfers',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'products',
            'warehouses',
            'locations',
            'product_categories',
            'stock_moves',
            'stock_transfers',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropForeign(['user_id']);
                $t->dropColumn('user_id');
            });
        }
    }
};
