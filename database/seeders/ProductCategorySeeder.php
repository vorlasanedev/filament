<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::first()->id ?? 1;

        $categories = [
            'Electronics',
            'Clothing',
            'Home & Garden',
            'Sports & Outdoors',
            'Toys & Games',
            'Health & Beauty',
            'Automotive',
            'Books',
            'Groceries',
            'Office Supplies',
        ];

        foreach ($categories as $categoryName) {
            ProductCategory::firstOrCreate(
                ['name' => $categoryName],
                ['user_id' => $userId]
            );
        }

        $this->command->info('Successfully seeded 10 product categories.');
    }
}
