# Step-by-Step Guideline: Foundational Project Setup

Follow these exact steps to set up the core infrastructure of the project, including Filament, Shield (RBAC), and the primary User Management cluster.

## Step 1: Install Dependencies
First, ensure your Laravel project is set up, then install Filament and Filament Shield.

```bash
composer require filament/filament:"^3.2" -W
php artisan filament:install --panels

composer require bezhansalleh/filament-shield
php artisan shield:install
```

## Step 2: Create the User Management Cluster
Instead of letting User, Role, and Permission resources clutter the main sidebar, we group them into a Cluster.

Create `app/Filament/Clusters/UserManagement/UserManagementCluster.php`:
```php
<?php
namespace App\Filament\Clusters\UserManagement;

use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;

class UserManagementCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Users';
    protected static ?int $navigationSort = 1;
    
    // Force resources to render as Top Tabs
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
```

## Step 3: Configure the User Resource (Primary Flat Tab)
Move your `UserResource.php` into the cluster and configure it as the default flat tab.

```php
// Inside UserResource.php

// 1. Link to the cluster
protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;

// 2. Sort it first so it becomes the default loaded page
protected static ?int $navigationSort = 1;

// 3. CRITICAL: Do NOT define $navigationGroup.
// Leaving it ungrouped creates a flat tab instead of a dropdown.
```

## Step 4: Configure Roles & Permissions (Dropdown Tab)
Move `RoleResource.php` and `PermissionResource.php` (published by Shield) into the cluster, and group them into a single dropdown tab.

### RoleResource.php
```php
protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;
protected static ?int $navigationSort = 2;
protected static ?string $navigationGroup = 'Configuration'; // Creates the dropdown
```

### PermissionResource.php
```php
protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;
protected static ?int $navigationSort = 3;
protected static ?string $navigationGroup = 'Configuration'; // Joins the dropdown
```

## Step 5: Global Panel Navigation Groups
To ensure the main sidebar renders clusters in the correct order, define global navigation groups in `app/Providers/Filament/AdminPanelProvider.php`.

```php
->navigationGroups([
    'Overview',
    'Users', // Ensures the UserManagementCluster sits here
    'Inventory',
    'Operations',
    'Configuration',
])
```

## Step 6: Create the Default Seeder
We must establish the default roles programmatically so the system is immediately usable after a fresh migration.

Create `database/seeders/RoleAndPermissionSeeder.php`:
```php
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure super_admin exists and bypasses everything
        $superAdmin = config('filament-shield.super_admin.name', 'super_admin');
        Role::firstOrCreate(['name' => $superAdmin, 'guard_name' => 'web']);

        // Create standard roles
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
    }
}
```
Run `php artisan db:seed --class=RoleAndPermissionSeeder` to apply the roles.

## Result
You now have a clean, scalable architectural foundation. The main sidebar features a single "Users" icon. Clicking it opens a page with a flat "Users" tab (selected by default) and a "Configuration" tab containing Roles and Permissions in a dropdown.
