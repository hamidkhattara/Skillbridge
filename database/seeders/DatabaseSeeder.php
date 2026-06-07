<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create the Admin User
        User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@skillbridge.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create Coach 1
        User::factory()->create([
            'name' => 'Coach One',
            'email' => 'coach1@skillbridge.test',
            'password' => Hash::make('password'),
            'role' => 'coach',
        ]);

        // 3. Create Coach 2
        User::factory()->create([
            'name' => 'Coach Two',
            'email' => 'coach2@skillbridge.test',
            'password' => Hash::make('password'),
            'role' => 'coach',
        ]);

        // 4. Generate 10 Fake Services using our Factory
        Service::factory(10)->create();
    }
}