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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('can_be_sold')->default(true);
            $table->boolean('can_be_purchased')->default(true);
            $table->boolean('is_favorite')->default(false);
            $table->string('image')->nullable();
            $table->boolean('track_inventory')->default(true);
            $table->string('invoicing_policy')->default('ordered');
            $table->json('sales_taxes')->nullable();
            $table->json('purchase_taxes')->nullable();
            $table->string('barcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'can_be_sold',
                'can_be_purchased',
                'is_favorite',
                'image',
                'track_inventory',
                'invoicing_policy',
                'sales_taxes',
                'purchase_taxes',
                'barcode'
            ]);
        });
    }
};
