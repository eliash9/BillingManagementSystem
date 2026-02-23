<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'manage users',
            'manage roles',
            'manage customers',
            'manage services',
            'manage invoices',
            'manage payments',
            'view customers',
            'view services',
            'view invoices',
            'view payments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign created permissions
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        // Super Admin gets all permissions via Gate::before rule, but we can assign too
        $roleSuperAdmin->givePermissionTo(Permission::all());

        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAdmin->givePermissionTo(['manage customers', 'manage services', 'manage invoices', 'manage payments', 'view customers', 'view services', 'view invoices', 'view payments']);

        $roleFinance = Role::firstOrCreate(['name' => 'Finance']);
        $roleFinance->givePermissionTo(['manage invoices', 'manage payments', 'view invoices', 'view payments']);

        $roleCustomer = Role::firstOrCreate(['name' => 'Customer']);
        $roleCustomer->givePermissionTo(['view services', 'view invoices', 'view payments']);

        // Create initial Super Admin User
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole('Super Admin');
    }
}
