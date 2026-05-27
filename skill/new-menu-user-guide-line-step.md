# New Step-by-Step Guide: Flat Tabs vs. Dropdown Tabs in Filament Clusters

This guide provides the exact steps needed to create a perfectly structured Top Navigation menu using Filament Clusters, where the primary menu item is a **flat, standalone tab** (no dropdown) positioned first, and secondary items are grouped into a **dropdown tab**.

## Step 1: Create the Cluster

First, define the main Cluster. By setting `SubNavigationPosition::Top`, we tell Filament to render the resources assigned to this cluster as top-level tabs.

```php
<?php
namespace App\Filament\Clusters\UserManagement;

use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;

class UserManagementCluster extends Cluster
{
    // The icon and label shown in the left sidebar
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Users';

    // Renders the internal resources as Top Tabs instead of a side menu
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    // Defines where this cluster sits in the main sidebar
    protected static ?int $navigationSort = 3;
}
```

## Step 2: Create a Flat, Standalone Tab (Primary Menu)

To create a tab that acts as a direct link without a dropdown arrow, you must **omit** the `$navigationGroup` property completely. 

> [!TIP]
> In Filament, any resource inside a cluster that does not have a `navigationGroup` is automatically rendered as a flat tab. Furthermore, ungrouped tabs are naturally sorted *before* grouped tabs!

In your `UserResource.php`, configure it as follows:

```php
// Link the resource to the cluster
protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;

// Set to 1 to ensure it is the very first tab
protected static ?int $navigationSort = 1;

// IMPORTANT: Do NOT define $navigationGroup here!
// Leaving it ungrouped ensures it becomes a flat tab and avoids the dropdown UI.
```

## Step 3: Create a Dropdown Tab (Grouped Menus)

To create a tab that acts as a dropdown menu (e.g., a "Configuration" tab that contains Roles and Permissions), you must assign multiple resources to the exact same `$navigationGroup`.

### RoleResource.php
```php
protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;

// Positioned second, inside the dropdown
protected static ?int $navigationSort = 2;

// Grouped under "Configuration"
protected static ?string $navigationGroup = 'Configuration';
```

### PermissionResource.php
```php
protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;

// Positioned third, inside the dropdown
protected static ?int $navigationSort = 3;

// Grouped under "Configuration"
protected static ?string $navigationGroup = 'Configuration';
```

## Step 4 (Optional): Global Panel Group Ordering

If you have multiple groups and want to guarantee their order globally across the entire panel, you can define `navigationGroups` in your `app/Providers/Filament/AdminPanelProvider.php`:

```php
->navigationGroups([
    'Overview',
    'Users',
    'Products',
    'Operations',
    'Report',
    'Configuration', // Forced to the end of the list
])
```

## Summary of the Result
By following these steps:
1. Clicking **Users** in the sidebar opens the cluster.
2. The page loads with **[ Users ] [ Configuration ▼ ]** tabs on top.
3. The **Users** tab is flat, has no dropdown, and is automatically selected as the first route.
4. The **Configuration** tab contains a neat dropdown for Roles and Permissions.
