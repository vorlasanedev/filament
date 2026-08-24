# Step-by-Step Guideline: Creating a New Module

Follow these exact technical steps whenever you need to add a brand new feature module (like Inventory or Finance) to the project, complete with Row-Level Security and Top Navigation.

## Step 1: Create the Module Cluster
Group the new feature into its own cluster.

Create `app/Filament/Clusters/[ModuleName]/[ModuleName]Cluster.php`:
```php
<?php
namespace App\Filament\Clusters\[ModuleName];

use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;

class [ModuleName]Cluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Module Name';
    protected static ?int $navigationSort = 3;
    
    // Force resources to render as Top Tabs
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
```

## Step 2: Database Preparation (Ownership)
Ensure all tables in this module can track which user created the record.

1. **Migrations**: Add `$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();` to every table in this module.
2. **Model Trait**: Add the `HasUserOwnership` trait to every Model in this module so the `user_id` is automatically populated on creation.
```php
class Product extends Model
{
    use \App\Models\Traits\HasUserOwnership;
    // ...
}
```

## Step 3: Configure Resources (Tabs and UI RLS)
Generate Filament Resources for your models and assign them to the cluster.

### Primary Resource (Flat Tab)
For the main resource (e.g., `ProductResource`), make it a flat tab:
```php
protected static ?string $cluster = \[ModuleName]Cluster::class;
protected static ?int $navigationSort = 1;
// DO NOT define $navigationGroup so it remains a flat tab
```

### Secondary Resources (Dropdown Tab)
For supporting resources (e.g., `CategoryResource`), group them into a dropdown:
```php
protected static ?string $cluster = \[ModuleName]Cluster::class;
protected static ?int $navigationSort = 2;
protected static ?string $navigationGroup = 'Configuration'; // Triggers dropdown
```

### Enforce UI Row-Level Security
In EVERY resource within the module, override `getEloquentQuery()` so standard users only see their own records:
```php
public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    $query = parent::getEloquentQuery();
    if (auth()->check() && auth()->user()->hasRole('user_[module_name]')) {
        $query->where('user_id', auth()->id());
    }
    return $query;
}
```

## Step 4: Backend Security Policies
Generate and modify the Laravel Policies to ensure users cannot hack the URL to edit data they don't own.

1. Run `php artisan shield:generate --all` and select your new module's resources.
2. Open the generated policies (e.g., `app/Policies/ProductPolicy.php`).
3. Modify the `update`, `delete`, and `restore` methods to check ownership:
```php
public function update(AuthUser $authUser, Product $model): bool
{
    if (!$authUser->can('Update:Product')) return false;
    
    if ($authUser->hasRole('user_[module_name]')) {
        return $model->user_id === $authUser->id;
    }
    return true;
}
```

## Step 5: Role Creation & Assignment
Finally, create the roles and assign the generated permissions. You can do this via the UI, or by adding to a Database Seeder:

1. Create `admin_[module_name]`: Assign ViewAny, View, Create, Update, Delete. (Do NOT assign ForceDelete).
2. Create `user_[module_name]`: Assign ViewAny, View, Create, Update. (Do NOT assign Delete or ForceDelete).

## Result
You have successfully added a new, secure module to the application. It sits cleanly in the sidebar, expands into organized top tabs when clicked, and strictly enforces data ownership at both the database and UI levels.
