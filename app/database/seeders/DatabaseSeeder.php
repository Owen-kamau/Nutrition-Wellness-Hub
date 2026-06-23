<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::findOrCreate('patient');
        Role::findOrCreate('nutritionist');
        Role::findOrCreate('administrator');

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@nutrition.local'],
            ['name' => 'System Admin', 'password' => bcrypt('password123')],
        );
        $admin->assignRole('administrator');

        $nutritionist = User::query()->firstOrCreate(
            ['email' => 'nutritionist@nutrition.local'],
            ['name' => 'Lead Nutritionist', 'password' => bcrypt('password123')],
        );
        $nutritionist->assignRole('nutritionist');

        $patient = User::query()->firstOrCreate(
            ['email' => 'patient@nutrition.local'],
            ['name' => 'Demo Patient', 'password' => bcrypt('password123')],
        );
        $patient->assignRole('patient');

        $this->call(NutritionSystemSeeder::class);
    }
}
