<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles
        $roles =  [
            'Adopter',
            'Shelter',
            'Administrator',
        ];

        // Define permissions
        $permissions = [
            'accept adoption request',
            'delete request',
            'delete user',
            'edit pet listing',
            'delete pet listing',
            'change role',
            'request adoption',
            'view pets',
            'adoption status'
        ];

        // Create roles
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $rolePermissions = [
            'Adopter' => [
                'request adoption',
                'view pets',
                'adoption status'
            ],

            'Shelter' => [
                'accept adoption request',
                'edit pet listing',
                'delete pet listing',
                'delete request',
                'view pets',
                'adoption status'
            ],

            'Administrator' => [
                'accept adoption request',
                'delete request',
                'delete user',
                'edit pet listing',
                'delete pet listing',
                'change role',
                'view pets',
                'adoption status'
            ]
        ];

        foreach ($rolePermissions as $role => $perms) {
            $roleModel = Role::where('name', $role)->first();
            if ($roleModel) {
                $roleModel->syncPermissions($perms);
            }
        }

        // Assign roles to users
        $admin = User::where('email', 'marjunmmagallanes@gmail.com')->first();
        if ($admin) {
            $admin->assignRole('Administrator');
        }

        $shelter = User::where('email', 'skermart18@gmail.com')->first();
        if ($shelter) {
            $shelter->assignRole('Shelter');
        }

        $adopter = User::where('email', 'undefinedbusiness18@gmail.com')->first();
        if ($adopter) {
            $adopter->assignRole('Adopter');
        }
    }
}

// $user = User::create(['name' => 'Adopter',  'email' => 'undefinedbusiness18@gmail.com',  'password' => bcrypt('iamadopter')]);
// $user = User::create(['name' => 'Shelter',  'email' => 'skermart18@gmail.com',  'password' => bcrypt('iamshelter')]);
// $user = User::create(['name' => 'Admin',  'email' => 'marjunmmagallanes@gmail.com',  'password' => bcrypt('iamadmin')]);
