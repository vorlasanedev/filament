<?php

namespace Database\Seeders;

use App\Models\ProductType;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::first()->id ?? 1;

        $types = [
            'Storable Product',
            'Consumable',
            'Service',
            'Event Ticket',
            'Digital Download',
        ];

        foreach ($types as $type) {
            ProductType::firstOrCreate(
                ['name' => $type],
                ['user_id' => $userId]
            );
        }

        $this->command->info('Successfully seeded product types.');
    }
}
