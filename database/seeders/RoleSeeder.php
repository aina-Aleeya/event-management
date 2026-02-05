<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'administrator',
                'description' => 'Has full access to all system features and settings',
                'permissions' => [
                    'create_event',
                    'group_participants',
                    'manage_scoresheet',
                    'submit_scores',
                    'manage_certificates',
                    'view_participants',
                ],
                'is_system' => true,
            ],
            [
                'name' => 'Judge',
                'slug' => 'judge',
                'description' => 'Can view scoresheets, submit scores, and view participants',
                'permissions' => [
                    'view_participants',
                    'manage_scoresheet',
                    'submit_scores',
                    'group_participants',
                ],
                'is_system' => true,
            ],
            [
                'name' => 'Coordinator',
                'slug' => 'coordinator',
                'description' => 'Can manage participants, grouping, and certificates',
                'permissions' => [
                    'view_participants',
                    'group_participants',
                    'manage_certificates',
                    'view_participants',
                ],
                'is_system' => true,
            ],
            [
                'name' => 'Technical Support',
                'slug' => 'technical-support',
                'description' => 'Can manage participants, grouping, and view scoresheets',
                'permissions' => [
                    'view_participants',
                    'group_participants',
                    'manage_scoresheet',
                    'view_participants',
                ],
                'is_system' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }

        $this->command->info('System roles created successfully!');
        $this->command->info('Created roles:');
        foreach ($roles as $role) {
            $this->command->info("   - {$role['name']} ({$role['slug']})");
        }
    }
}