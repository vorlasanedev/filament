<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            $warehouse = \App\Models\Warehouse::create([
                'name' => "Warehouse $i",
                'short_name' => "WH$i",
                'address' => "Address $i",
                'is_active' => true,
            ]);

            for ($j = 1; $j <= 5; $j++) {
                \App\Models\Location::create([
                    'name' => "Location $j (WH$i)",
                    'type' => 'internal',
                    'warehouse_id' => $warehouse->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
