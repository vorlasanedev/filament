---
name: skill-change-menu-position-inventory
description: Zero-to-Hero instructions on how to change the sub-menu order and position within the Inventory module (and globally).
---

# Skill: Changing Menu Position for the Inventory Module

To reorder sub-menus or move items within the Inventory module, you need to configure the global Panel Provider for group ordering and the individual Resource/Page classes for specific placement.

Follow these steps:

## Step 1: Reordering Groups in the Panel Provider
If your Inventory module has multiple dropdown groups (like `Operations`, `Products`, `Reports`, `Configuration`) and you want to change the order they appear from left to right (or top to bottom), update your Panel Provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`).

Find the `->navigationGroups([...])` method and arrange the array in your desired order:

```php
->navigationGroups([
    'Operations',
    'Products',
    'Reports',
    'Configuration',
])
```

## Step 2: Positioning a Standalone Menu Item
If you want an item (like the `Overview` page) to appear directly on the navigation bar without a dropdown, assign its `navigationGroup` to `null`.
Items without a group naturally render first, before the dropdown groups.

```php
// Inside your Resource or Page (e.g., OverviewDashboard.php)
protected static string|\UnitEnum|null $navigationGroup = null;
```

## Step 3: Sorting Items within a Group or Cluster
To explicitly control the position of a Resource/Page—either within its dropdown group or at the top level of the cluster—use the `$navigationSort` property.

```php
protected static ?int $navigationSort = -1;
```
* **Negative numbers (e.g., `-1`)**: Ensure the item is pushed to the very front. This is ideal for making a page the default landing page for the cluster.
* **Positive numbers (e.g., `1, 2, 3`)**: Order items sequentially within their respective `navigationGroup`.

## Complete Example

If you want an `OverviewDashboard` to be the first item outside of any dropdown group, you would configure it like this:

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
