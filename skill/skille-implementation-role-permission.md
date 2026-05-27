# Skill Implementation: Roles & Permissions

This document outlines the implementation details for the Roles and Permissions system within this Filament project.

## 1. Overview
The project uses the `bezhan-salleh/filament-shield` package to manage roles and permissions via the underlying `spatie/laravel-permission` library. The UI is structured so that **Users**, **Roles**, and **Permissions** are all neatly grouped together in the sidebar under the **User Management** navigation group.

## 2. Navigation Structure
To provide a clean UX, the Filament Cluster feature was bypassed in favor of a standard Navigation Group. The resources are sorted as follows:

1. **Users** (`navigationSort = 1`)
2. **Roles** (`navigationSort = 2`)
3. **Permissions** (`navigationSort = 3`)

All of these share the following property in their respective Resource classes:
```php
protected static \UnitEnum|string|null $navigationGroup = 'User Management';
```

## 3. Resources

### UserResource
- **Path:** `app/Filament/Resources/UserResource.php`
- **Model:** `App\Models\User`
- **Features:** Allows creating and managing users. Uses a `Select` component to attach Spatie Roles to the user (`->relationship('roles', 'name')`).

### RoleResource
- **Path:** `app/Filament/Resources/Roles/RoleResource.php`
- **Features:** Provided by Filament Shield. Customized by modifying `getNavigationGroup()` to place it in "User Management" instead of "Roles and Permission".

### PermissionResource
- **Path:** `app/Filament/Resources/PermissionResource.php`
- **Model:** `Spatie\Permission\Models\Permission`
- **Features:** A custom-built resource that allows viewing and creating fine-grained permissions. Uses `Filament\Schemas\Schema` for the form configuration.

## 4. Admin Panel Provider Configuration
In `app/Providers/Filament/AdminPanelProvider.php`, the Filament Shield plugin is configured to use the correct navigation group to match our custom resources:
```php
->plugins([
    \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
        ->navigationGroup('User Management')
])
```

## 5. Generating Permissions
When a new Resource, Page, or Widget is created in Filament, you must generate the corresponding permissions so they can be assigned to Roles. 
Run the following Artisan command:
```bash
php artisan shield:generate --all
```
*To generate permissions for a specific resource (e.g., PermissionResource), run:*
```bash
php artisan shield:generate --resource=PermissionResource
```

## 6. Super Admin Access
The `Super Admin` role bypasses all permission checks via Laravel's `Gate::before` intercept, which is automatically handled by the Shield package. Ensure that your initial user has the `Super Admin` role assigned to manage the system fully.
