<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
    Schema::disableForeignKeyConstraints();
    Role::truncate();
    Schema::enableForeignKeyConstraints();
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Full system access, usually reserved for gym owners or directors.'
            ],
            [
                'name' => 'General Manager',
                'slug' => 'general_manager',
                'description' => 'Oversees site operations, staff performance, and high-level reporting.'
            ],
            [
                'name' => 'Accounts Clerk',
                'slug' => 'accounts_clerk',
                'description' => 'Handles M-Pesa Paybill/Till reconciliation, invoicing, and KRA tax compliance.'
            ],
            [
                'name' => 'Sales Consultant',
                'slug' => 'sales_consultant',
                'description' => 'Manages leads, corporate membership outreach, and CRM follow-ups.'
            ],
            [
                'name' => 'Receptionist',
                'slug' => 'receptionist',
                'description' => 'Front desk operations, biometric check-ins, and walk-in inquiries.'
            ],
            [
                'name' => 'Fitness Trainer',
                'slug' => 'trainer',
                'description' => 'Manages training schedules, personal training clients, and workout logs.'
            ],
            [
                'name' => 'Member',
                'slug' => 'member',
                'description' => 'End-user access for booking classes, viewing payments, and tracking progress.'
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']], // Unique identifier
                [
                    'name' => $role['name'],
                    'description' => $role['description'],
                ]
            );
        }
    }
}
