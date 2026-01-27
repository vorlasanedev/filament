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
        // 1. Convert existing data to JSON format
        $employees = \Illuminate\Support\Facades\DB::table('employees')->get();
        foreach ($employees as $employee) {
            \Illuminate\Support\Facades\DB::table('employees')
                ->where('id', $employee->id)
                ->update([
                    'first_name' => json_encode(['en' => $employee->first_name], JSON_UNESCAPED_UNICODE),
                    'last_name' => json_encode(['en' => $employee->last_name], JSON_UNESCAPED_UNICODE),
                    'position' => json_encode(['en' => $employee->position], JSON_UNESCAPED_UNICODE),
                ]);
        }

        // 2. Change column type to JSON (or text if JSON not strictly supported by driver/version without dbal)
        // Note: modify/change often requires doctrine/dbal. 
        // If this fails, we might need to rely on the fact that existing string columns can hold JSON text.
        // But let's try to be explicit.
        Schema::table('employees', function (Blueprint $table) {
            $table->text('first_name')->change();
            $table->text('last_name')->change();
            $table->text('position')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Revert column type
        Schema::table('employees', function (Blueprint $table) {
            $table->string('first_name')->change();
            $table->string('last_name')->change();
            $table->string('position')->change();
        });

        // 2. Revert data (unwrap JSON) - simplistic approach taking 'en'
        $employees = \Illuminate\Support\Facades\DB::table('employees')->get();
        foreach ($employees as $employee) {
            $firstName = json_decode($employee->first_name, true)['en'] ?? $employee->first_name;
            $lastName = json_decode($employee->last_name, true)['en'] ?? $employee->last_name;
            $position = json_decode($employee->position, true)['en'] ?? $employee->position;

            \Illuminate\Support\Facades\DB::table('employees')
                ->where('id', $employee->id)
                ->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'position' => $position,
                ]);
        }
    }
};
