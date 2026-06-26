<?php

namespace Database\Seeders;

use App\Models\ProductUnit;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductUnitSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::first()->id ?? 1;

        $units = [
            'pcs',
            'kg',
            'g',
            'L',
            'mL',
            'box',
            'dozen',
            'meter',
            'hours',
        ];

        foreach ($units as $unit) {
            ProductUnit::firstOrCreate(
                ['name' => $unit],
                ['user_id' => $userId]
            );
        }

        $this->command->info('Successfully seeded product units.');
    }
}
