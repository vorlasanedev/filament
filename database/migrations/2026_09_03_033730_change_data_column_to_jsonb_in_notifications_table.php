<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE notifications ALTER COLUMN data TYPE jsonb USING CASE WHEN data IS NULL OR trim(data) = '' THEN '{}'::jsonb ELSE data::jsonb END;");
        } else {
            Schema::table('notifications', function (Blueprint $table) {
                $table->json('data')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE notifications ALTER COLUMN data TYPE text USING data::text;');
        } else {
            Schema::table('notifications', function (Blueprint $table) {
                $table->text('data')->change();
            });
        }
    }
};
