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
        Schema::table('users', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index('first_name');
            $table->index('last_name');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->index('name');
            $table->index('type');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('name');
            $table->index('type');
            $table->index('is_active');
        });

        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->index('type');
            $table->index('status');
            $table->index('scheduled_date');
        });

        Schema::table('stock_moves', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('villages', function (Blueprint $table) {
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['first_name']);
            $table->dropIndex(['last_name']);
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['type']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['type']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['status']);
            $table->dropIndex(['scheduled_date']);
        });

        Schema::table('stock_moves', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('villages', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};
