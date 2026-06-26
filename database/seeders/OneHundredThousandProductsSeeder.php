<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OneHundredThousandProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting 100,000 Product Seeder...');
        $startTime = microtime(true);

        $now = now();
        $totalRecords = 100000;
        $chunkSize = 4000;
        $chunks = ceil($totalRecords / $chunkSize);

        // Fetch valid foreign keys to avoid constraint failures
        $userId = User::first()->id ?? 1;
        $categoryIds = ProductCategory::pluck('id')->toArray();
        $hasCategories = count($categoryIds) > 0;
        
        $typeIds = \App\Models\ProductType::pluck('id')->toArray();
        $hasTypes = count($typeIds) > 0;
        
        $unitIds = \App\Models\ProductUnit::pluck('id')->toArray();
        $hasUnits = count($unitIds) > 0;

        // Disable query log in memory to prevent memory exhaustion
        DB::connection()->unsetEventDispatcher();
        DB::disableQueryLog();
        
        $bar = $this->command->getOutput()->createProgressBar($chunks);
        $bar->start();

        for ($i = 0; $i < $chunks; $i++) {
            $products = [];
            
            for ($j = 0; $j < $chunkSize; $j++) {
                $counter = ($i * $chunkSize) + $j + 1;
                
                if ($counter > $totalRecords) {
                    break;
                }

                $randomCategoryId = $hasCategories ? $categoryIds[array_rand($categoryIds)] : null;
                $randomTypeId = $hasTypes ? $typeIds[array_rand($typeIds)] : null;
                $randomUnitId = $hasUnits ? $unitIds[array_rand($unitIds)] : null;

                $products[] = [
                    'name'                => "Product {$counter}",
                    'sku'                 => "SKU-" . str_pad($counter, 6, "0", STR_PAD_LEFT),
                    'cost'                => rand(10, 100) + (rand(0, 99) / 100),
                    'price'               => rand(100, 500) + (rand(0, 99) / 100),
                    'weight'              => rand(1, 50),
                    'strategy'            => 'FIFO',
                    'safety_stock'        => rand(10, 50),
                    'lead_time'           => rand(1, 14),
                    'is_active'           => true,
                    'product_category_id' => $randomCategoryId,
                    'product_type_id'     => $randomTypeId,
                    'product_unit_id'     => $randomUnitId,
                    'user_id'             => $userId,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }

            // Raw bulk insert bypasses Eloquent hydration and events for maximum speed
            Product::insert($products);
            $bar->advance();
        }

        $bar->finish();
        $this->command->line('');
        
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        
        $this->command->info("Completed inserting {$totalRecords} products in {$executionTime} seconds.");
    }
}
