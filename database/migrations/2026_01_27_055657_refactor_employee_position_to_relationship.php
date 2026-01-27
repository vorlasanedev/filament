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
        // 1. Add position_id column
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete();
        });

        // 2. Migrate Data
        $employees = \Illuminate\Support\Facades\DB::table('employees')->get();
        foreach ($employees as $employee) {
            if (empty($employee->position)) {
                continue;
            }

            // Employee position is stored as JSON (e.g., {"en": "Developer"}) or possibly raw string if not migrated yet (but we assume it is)
            // We want to dedup based on the JSON content.
            // Using DB::table for Positions
            $positionRecord = \Illuminate\Support\Facades\DB::table('positions')
                ->where('name', $employee->position)
                ->first();

            if (!$positionRecord) {
                $id = \Illuminate\Support\Facades\DB::table('positions')->insertGetId([
                    'name' => $employee->position,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $positionId = $id;
            } else {
                $positionId = $positionRecord->id;
            }

            \Illuminate\Support\Facades\DB::table('employees')
                ->where('id', $employee->id)
                ->update(['position_id' => $positionId]);
        }
        
        // 3. Drop old position column
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('relationship', function (Blueprint $table) {
            //
        });
    }
};
