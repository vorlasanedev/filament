# ToDo: Scaffold a New Project Template

Use this checklist when initializing a brand new Laravel Filament project to ensure it strictly follows our foundational architecture (Clusters, Top Navigation, RBAC).

## 1. Initial Setup
- [ ] Initialize a new Laravel project (`composer create-project laravel/laravel new-project`).
- [ ] Configure the `.env` database connection.
- [ ] Run `php artisan migrate`.

## 2. Install Core Packages
- [ ] Install Filament (`composer require filament/filament:"^3.2" -W`).
- [ ] Install Filament Admin Panel (`php artisan filament:install --panels`).
- [ ] Install Filament Shield (`composer require bezhansalleh/filament-shield`).
- [ ] Run Shield Installation (`php artisan shield:install`).

## 3. Configure the Base "Users" Module
- [ ] Create the `UserManagementCluster` with `SubNavigationPosition::Top`.
- [ ] Move `UserResource` into the cluster.
- [ ] Set `UserResource` to `$navigationSort = 1` and remove its `$navigationGroup` to make it a flat tab.
- [ ] Move Shield's `RoleResource` and `PermissionResource` into the cluster.
- [ ] Group both Roles and Permissions into `$navigationGroup = 'Configuration'` and sort them as 2 and 3.

## 4. Define Panel Navigation
- [ ] Update `app/Providers/Filament/AdminPanelProvider.php` to define the global navigation group ordering.
  ```php
  ->navigationGroups([
      'Overview',
      'Users',
      'Configuration',
  ])
  ```

## 5. Seed Core Roles
- [ ] Create a `RoleAndPermissionSeeder`.
- [ ] Ensure the seeder automatically establishes the `super_admin` (using Shield config name), `admin`, and `user` roles in the database.
- [ ] Run `php artisan db:seed --class=RoleAndPermissionSeeder`.

## 6. Verification
- [ ] Create a test user and assign the `super_admin` role.
- [ ] Log in and verify that the sidebar only shows the top-level "Users" cluster.
- [ ] Click "Users" and verify the page loads with a flat "Users" tab and a dropdown "Configuration" tab for Roles and Permissions.
