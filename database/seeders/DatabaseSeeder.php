<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the member role using the factory.
        Role::factory()->create([
            'name' => 'member',
            'description' => 'Member role with limited access',
        ]);

        // Create the admin role using the factory.
        $admin = Role::factory()->create([
            'name' => 'admin',
            'description' => 'Administrator role with full access',
        ]);

        // Create the manager role using the factory.
        Role::factory()->create([
            'name' => 'manager',
            'description' => 'Manager role with elevated access',
        ]);

        // Create the admin user and assign the role_id properly.
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@synai.eu',
            'password' => bcrypt('password'),
            'role_id' => $admin->id,  // Ensure role_id is passed as the foreign key
        ]);
    }
}
