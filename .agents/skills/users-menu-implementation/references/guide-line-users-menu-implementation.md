# Step-by-Step Guideline: Creating a Top Navigation Menu with Filament Clusters

If you want to recreate the exact "Users" menu structure with top tabs (e.g., a "Users" tab and a "Configuration" dropdown tab for Roles/Permissions), follow these exact steps.

## Step 1: Create the Cluster
Create a new Cluster file in `app/Filament/Clusters/UserManagement/UserManagementCluster.php`:

```php
<?php
namespace App\Filament\Clusters\UserManagement;

use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;

class UserManagementCluster extends Cluster
{
    // The icon in the main sidebar
    protected static ?string $navigationIcon = 'heroicon-o-users';

    // The label in the main sidebar
    protected static ?string $navigationLabel = 'Users';

    // This makes it display as Top Tabs instead of a side menu
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    // Defines where it sits in the main sidebar relative to other items
    protected static ?int $navigationSort = 3;
}
```

## Step 2: Configure the Default Tab (UserResource)
The resource you want to load by default when clicking the sidebar must be sorted first. It also requires its own `$navigationGroup` to render as a distinct tab.

In `app/Filament/Resources/UserResource.php`, define the cluster, sort, and group:
```php
// Link to the cluster
protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;

// Make it the first tab (and default route)
protected static ?int $navigationSort = 1;

// DO NOT set a $navigationGroup. 
// Leaving it ungrouped ensures it becomes a flat tab (no dropdown) and automatically sorts before any grouped tabs.
```

## Step 3: Configure Dropdown Tabs (Roles and Permissions)
To group Roles and Permissions under a single "Configuration" tab, assign them both to the same cluster and the same `$navigationGroup`.

### RoleResource.php
```php
public static function getCluster(): ?string
{
    return \App\Filament\Clusters\UserManagement\UserManagementCluster::class;
}

// Positioned second
protected static ?int $navigationSort = 2;

// Grouped under "Configuration"
public static function getNavigationGroup(): ?string
{
    return 'Configuration';
}
```

### PermissionResource.php
```php
protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;

// Positioned third
protected static ?int $navigationSort = 3;

// Grouped under "Configuration" (MUST match exact PHP type hint)
protected static \UnitEnum|string|null $navigationGroup = 'Configuration';
```

## Result
1. The sidebar will show **Users**.
2. Clicking **Users** will load the `UserResource` list table by default.
3. The top of the page will show a **Users** tab (active) and a **Configuration** tab (containing a dropdown for Roles and Permissions).
