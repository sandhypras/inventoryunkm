<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==================== CREATE PERMISSIONS ====================
        $permissions = [
            // Dashboard
            'view_dashboard',

            // Products
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',

            // Categories
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',

            // Suppliers
            'view_suppliers',
            'create_suppliers',
            'edit_suppliers',
            'delete_suppliers',

            // Stock In
            'view_stock_in',
            'create_stock_in',
            'edit_stock_in',
            'delete_stock_in',

            // Stock Out
            'view_stock_out',
            'create_stock_out',
            'edit_stock_out',
            'delete_stock_out',

            // Reports
            'view_reports',
            'download_reports',
            'email_reports',
            'view_profit_reports',

            // Users
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Roles
            'view_roles',
            'edit_roles',

            // Settings
            'view_settings',
            'edit_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // ==================== CREATE ROLES ====================

        // 1. ADMIN ROLE - Full Access
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // 2. GUDANG ROLE - Stock Management
        $gudang = Role::create(['name' => 'gudang']);
        $gudang->givePermissionTo([
            'view_dashboard',
            // Products
            'view_products',
            'create_products',
            'edit_products',
            // Suppliers
            'view_suppliers',
            'create_suppliers',
            'edit_suppliers',
            // Stock In
            'view_stock_in',
            'create_stock_in',
            'edit_stock_in',
            // Stock Out
            'view_stock_out',
            'create_stock_out',
            // Reports
            'view_reports',
            'download_reports',
        ]);

        // 3. KASIR ROLE - Sales Only
        $kasir = Role::create(['name' => 'kasir']);
        $kasir->givePermissionTo([
            'view_dashboard',
            // Products (read only)
            'view_products',
            // Stock Out (Sales)
            'view_stock_out',
            'create_stock_out',
            'edit_stock_out',
            // Reports (limited)
            'view_reports',
            'download_reports',
        ]);

        // 4. VIEWER ROLE - Read Only
        $viewer = Role::create(['name' => 'viewer']);
        $viewer->givePermissionTo([
            'view_dashboard',
            'view_products',
            'view_categories',
            'view_suppliers',
            'view_stock_in',
            'view_stock_out',
            'view_reports',
            'download_reports',
            'view_profit_reports',
        ]);

        // ==================== CREATE DEFAULT USERS ====================

        // Admin User
        $adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole('admin');

        // Gudang User
        $gudangUser = User::create([
            'name' => 'Staff Gudang',
            'email' => 'gudang@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'gudang',
            'email_verified_at' => now(),
        ]);
        $gudangUser->assignRole('gudang');

        // Kasir User
        $kasirUser = User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);
        $kasirUser->assignRole('kasir');

        // Viewer User
        $viewerUser = User::create([
            'name' => 'Viewer',
            'email' => 'viewer@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'viewer',
            'email_verified_at' => now(),
        ]);
        $viewerUser->assignRole('viewer');

        $this->command->info('✅ Roles & Permissions created successfully!');
        $this->command->info('');
        $this->command->info('📋 Default Users:');
        $this->command->info('Admin: admin@inventory.com / password');
        $this->command->info('Gudang: gudang@inventory.com / password');
        $this->command->info('Kasir: kasir@inventory.com / password');
        $this->command->info('Viewer: viewer@inventory.com / password');
    }
}
