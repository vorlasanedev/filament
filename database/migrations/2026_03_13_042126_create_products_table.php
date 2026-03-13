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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->string('type')->default('storable'); // storable, consumable, service
            $table->string('unit')->default('pcs');
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('strategy')->default('MTS'); // MTS (Make to Stock), MTO (Make to Order)
            $table->integer('safety_stock')->default(0);
            $table->integer('lead_time')->default(0); // days
            $table->boolean('is_active')->default(true);
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
