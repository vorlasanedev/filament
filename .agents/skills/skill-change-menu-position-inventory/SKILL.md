---
name: skill-change-menu-position-inventory
description: Zero-to-Hero instructions on how to change the sub-menu order and position within the Inventory module (and globally).
---

# Skill: Changing Menu Position for the Inventory Module

To reorder sub-menus or move items within the Inventory module, you need to configure the global Panel Provider for standard group ordering, but **most importantly**, you must configure the `$navigationSort` of the Resources/Pages within the cluster itself.

Follow these steps:

## Step 1: Reordering Groups Globally in the Panel Provider
To arrange the sequence of dropdown groups for standard sidebar menus, define a simple array of strings in your Panel Provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`).

```php
// In your panel provider:
->navigationGroups([
    'Operations',
    'Products',
    'Reports',
    'Configuration',
])
```
*(Note: Do NOT use `NavigationGroup::make()->sort()` as it will cause a 500 BadMethodCallException!)*

## Step 2: Sorting Cluster Navigation Groups
**CRITICAL FOR CLUSTERS:** A Filament Cluster dynamically orders its sub-navigation groups based on the **lowest `$navigationSort` of the items inside that group**.

If a group is appearing out of order (for example, at the very front), it means one of the Resources/Pages in that group has a `null` or `0` sort order. 

To fix this, you must ensure **all** Resources/Pages in your cluster have a `$navigationSort` defined!

For example, if you want `Configuration` to appear last, make sure all its resources have the highest sort numbers:
```php
// In ProductTypeResource.php
protected static string|\UnitEnum|null $navigationGroup = 'Configuration';
protected static ?int $navigationSort = 8;

// In ProductUnitResource.php
protected static string|\UnitEnum|null $navigationGroup = 'Configuration';
protected static ?int $navigationSort = 9;
```

## Step 3: Positioning a Standalone Menu Item
If you want an item (like the `Overview` page) to appear directly on the navigation bar without a dropdown, assign its `navigationGroup` to `null`.
Items without a group naturally render first, before the dropdown groups.

```php
// Inside your Resource or Page (e.g., OverviewDashboard.php)
protected static string|\UnitEnum|null $navigationGroup = null;
```

## Step 4: Making a Page the Default Landing Page
To explicitly control the position of a Resource/Page to be the absolute first item in the cluster (making it the default landing page), use a negative number.

```php
protected static ?int $navigationSort = -1;
```

## Complete Standalone Example
If you want an `OverviewDashboard` to be the first item outside of any dropdown group, configure it like this:

```php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Clusters\InventoryManagement\InventoryManagementCluster;

class OverviewDashboard extends Page
{
    // Register to Inventory cluster
    protected static ?string $cluster = InventoryManagementCluster::class;

    // Display label
    protected static ?string $navigationLabel = 'Overview';

    // Null group makes it a top-level, standalone tab
    protected static string|\UnitEnum|null $navigationGroup = null;

    // Highest priority sort makes it the default landing page
    protected static ?int $navigationSort = -1;
    
    // ...
}
```
