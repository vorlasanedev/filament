<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OneMillionUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting 1 Million User Seeder...');
        $startTime = microtime(true);

        // Pre-compute values to save CPU cycles
        $password = Hash::make('password');
        $now = now();
        $totalRecords = 1000000;
        $chunkSize = 5000;
        $chunks = ceil($totalRecords / $chunkSize);

        // Disable query log in memory to prevent memory exhaustion
        DB::connection()->unsetEventDispatcher();
        DB::disableQueryLog();
        
        $bar = $this->command->getOutput()->createProgressBar($chunks);
        $bar->start();

        for ($i = 0; $i < $chunks; $i++) {
            $users = [];
            
            for ($j = 0; $j < $chunkSize; $j++) {
                $counter = ($i * $chunkSize) + $j + 1;
                
                // Break if we hit the exact target
                if ($counter > $totalRecords) {
                    break;
                }

                $users[] = [
                    'name'              => "User {$counter}",
                    'email'             => "user_{$counter}@example.com",
                    'phone'             => "1800" . str_pad($counter, 7, "0", STR_PAD_LEFT),
                    'email_verified_at' => $now,
                    'password'          => $password,
                    'remember_token'    => Str::random(10),
                    'is_active'         => true,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];
            }

            // Raw bulk insert bypasses Eloquent hydration and events for maximum speed
            User::insert($users);
            $bar->advance();
        }

        $bar->finish();
        $this->command->line('');
        
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        
        $this->command->info("Completed inserting {$totalRecords} users in {$executionTime} seconds.");
    }
}
