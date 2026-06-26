---
name: skill-create-menu
description: Zero-to-Hero instructions on creating a sub-menu item within the Inventory module cluster in Filament.
---

# Skill: Creating a Sub-Menu in the Inventory Module

To add a new page or resource as a sub-menu item in the Inventory module (which is structured as a Filament Cluster), follow these steps:

## Step 1: Create the Resource or Page
Generate your Filament Resource or Page as usual using artisan commands. For example:
```bash
php artisan filament:resource YourResource
# or
php artisan filament:page YourPage
```

## Step 2: Assign it to the Inventory Cluster
In your newly created Resource or Page class, define the `$cluster` property so it registers inside the Inventory module instead of the main sidebar.

```php
protected static ?string $cluster = \App\Filament\Clusters\InventoryManagement\InventoryManagementCluster::class;
```

## Step 3: Define the Navigation Group (Dropdown)
If you want the item to appear under an existing sub-menu dropdown (such as `Operations`, `Products`, `Reports`, or `Configuration`), set the `$navigationGroup`:

```php
protected static string|\UnitEnum|null $navigationGroup = 'Operations';
```

> **Note**: If you want the item to appear as a standalone, direct-clickable tab (like the Overview page) without being inside a dropdown, set `$navigationGroup` to `null`.

## Step 4: Set the Sort Order
Control where the item appears in its group by setting the `$navigationSort` value. Lower numbers appear first.

```php
protected static ?int $navigationSort = 3;
```
*(As a best practice, the default landing page like `Overview` is usually set to a negative number such as `-1` to guarantee it appears first.)*

## Complete Example
Here is what the configuration block looks like within a typical Filament Resource:

```php
namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Clusters\InventoryManagement\InventoryManagementCluster;

class CustomInventoryResource extends Resource
{
    // Assign to the cluster to place it in the Inventory sub-menu
    protected static ?string $cluster = InventoryManagementCluster::class;

    // Group it under the 'Operations' dropdown
    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    // Position it as the 3rd item in that dropdown
    protected static ?int $navigationSort = 3;
    
    // ... rest of resource ...
}
```
