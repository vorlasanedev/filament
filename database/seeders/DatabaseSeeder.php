<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdmin = User::firstOrCreate([
            'email' => 'superuser@gmail.com',
        ], [
            'name' => 'superuser',
            'password' => bcrypt('Root@mysql'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('Root@mysql'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->call([
            OneMillionUsersSeeder::class,
            ProductCategorySeeder::class,
            ProductTypeSeeder::class,
            ProductUnitSeeder::class,
            OneHundredThousandProductsSeeder::class,
        ]);
    }
}
