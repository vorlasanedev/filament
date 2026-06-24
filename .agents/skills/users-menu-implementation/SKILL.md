---
name: users-menu-implementation
description: Zero-to-Hero instructions on implementing Top Navigation Clusters in Filament.
---

# Skill: Top Navigation Clusters (Zero to Hero)

By default, Filament places all navigation items in a sidebar. Clusters allow you to group related resources together under a single top-level navigation item, which is fantastic for massive admin panels.

## Phase 1: Generating the Cluster

A Cluster is a special PHP class that defines the group.

1. **Create the Cluster:**
   ```bash
   php artisan make:filament-cluster UserManagement
   ```
2. **Configure the Cluster Class:**
   Open the newly generated file: `app/Filament/Clusters/UserManagement.php`
   
   Here, you can define the Icon and Label that appears in the main navigation sidebar.
   ```php
   namespace App\Filament\Clusters;

   use Filament\Clusters\Cluster;

   class UserManagement extends Cluster
   {
       protected static ?string $navigationIcon = 'heroicon-o-users';
       protected static ?string $navigationLabel = 'User Management';
       protected static ?string $navigationGroup = 'Settings'; // Optional top-level group
   }
   ```

## Phase 2: Adding Resources to the Cluster

Now you need to tell your existing Resources to live *inside* this new cluster instead of the main sidebar.

Open your Resource (e.g., `app/Filament/Resources/UserResource.php`).

Add the `$cluster` property pointing to the class you just created.

```php
namespace App\Filament\Resources;

use App\Filament\Clusters\UserManagement; // Import the cluster
use Filament\Resources\Resource;

class UserResource extends Resource
{
    // Bind the resource to the cluster!
    protected static ?string $cluster = UserManagement::class;
    
    // ...
}
```

## Phase 3: Changing the Cluster Layout

By default, clicking a Cluster in the sidebar opens a sub-menu. If you prefer the Cluster items to appear as a Top Navigation bar across the screen (with the sidebar acting as the main category switcher):

1. **Update Panel Configuration:**
   Open `app/Providers/Filament/AdminPanelProvider.php`.
   
2. **Enable Top Navigation:**
   Inside the `->panel()` configuration chain, add `->topNavigation()`.
   
   ```php
   public function panel(Panel $panel): Panel
   {
       return $panel
           ->default()
           ->id('admin')
           ->path('admin')
           ->login()
           ->topNavigation() // Adds top navigation for clusters/groups
           // ...
   }
   ```

### Pro-Tips
- **Ordering:** You can control the order of items *inside* the cluster by defining `protected static ?int $navigationSort = 1;` inside the respective Resource classes.
- **Pages:** You can add custom Pages to a cluster too, not just Resources! Just define `$cluster = UserManagement::class;` inside your custom Page class.
