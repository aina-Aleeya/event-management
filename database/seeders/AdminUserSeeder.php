<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create a sample organiser
        User::updateOrCreate(
            ['email' => 'organiser@test.com'],
            [
                'name' => 'Organiser 1',
                'password' => Hash::make('organiser123'),
                'role' => 'organiser',
            ]
        );

        $this->command->info('Admin and Organiser users created successfully!');
        $this->command->info('Admin: admin@test.com / admin123');
        $this->command->info('Organiser: organiser@test.com / organiser123');
    }
}