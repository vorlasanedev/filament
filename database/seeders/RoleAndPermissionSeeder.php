<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin_inventory',
            'user_inventory',
            'admin_employee',
            'user_employee',
            'admin_finance',
            'user_finance',
        ];

        foreach ($roles as $roleName) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Inventory Module Permissions
        $inventoryModels = ['Product', 'Warehouse', 'Location', 'ProductCategory', 'StockMove', 'StockTransfer'];
        $adminInventoryPerms = [];
        $userInventoryPerms = [];

        foreach ($inventoryModels as $model) {
            array_push($adminInventoryPerms, "ViewAny:$model", "View:$model", "Create:$model", "Update:$model", "Delete:$model", "Restore:$model");
            array_push($userInventoryPerms, "ViewAny:$model", "View:$model", "Create:$model", "Update:$model");
        }

        \Spatie\Permission\Models\Role::findByName('admin_inventory')->syncPermissions($adminInventoryPerms);
        \Spatie\Permission\Models\Role::findByName('user_inventory')->syncPermissions($userInventoryPerms);

        // Employee Module Permissions
        $employeeModels = ['Employee', 'Department', 'Position'];
        $adminEmployeePerms = [];
        $userEmployeePerms = ['ViewAny:Employee', 'View:Employee', 'Update:Employee'];

        foreach ($employeeModels as $model) {
            array_push($adminEmployeePerms, "ViewAny:$model", "View:$model", "Create:$model", "Update:$model", "Delete:$model", "Restore:$model");
        }

        \Spatie\Permission\Models\Role::findByName('admin_employee')->syncPermissions($adminEmployeePerms);
        \Spatie\Permission\Models\Role::findByName('user_employee')->syncPermissions($userEmployeePerms);

        // Superadmin bypasses everything, but let's make sure it's created
        $superAdminName = config('filament-shield.super_admin.name', 'super_admin');
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => $superAdminName, 'guard_name' => 'web']);
    }
}
