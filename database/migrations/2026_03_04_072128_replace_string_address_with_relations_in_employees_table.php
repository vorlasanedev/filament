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
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['village', 'district', 'province']);
            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('village_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn(['province_id', 'district_id', 'village_id']);
            $table->string('village')->nullable()->after('phone');
            $table->string('district')->nullable()->after('village');
            $table->string('province')->nullable()->after('district');
        });
    }
};
