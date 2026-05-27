# Skill: Foundational Project Architecture

This document outlines the core architectural logic and technical philosophy behind our standard Laravel Filament project template. Understanding these principles is crucial for building robust, scalable applications.

### 1. Core Stack & Role-Based Access Control (RBAC)
- **Framework**: Laravel 11 + Filament v3.
- **Authorization**: We rely on `bezhansalleh/filament-shield`, which acts as a powerful UI and logic wrapper around `spatie/laravel-permission`.
- **Philosophy**: Security is managed through **Roles** (e.g., `admin_inventory`, `user_employee`) rather than raw permissions assigned to users. The `super_admin` role acts as an absolute override (via global Gate interception) that bypasses all policy checks.

### 2. Module Organization via Clusters
Instead of dumping all resources into the main sidebar, we organize the application into logical domains using **Filament Clusters**.
- **Domains**: Every major feature set (e.g., Users, Inventory, Employees) gets its own Cluster (e.g., `UserManagementCluster`).
- **Sidebar Efficiency**: The main sidebar only shows the top-level Clusters, preventing UI clutter as the application grows.

### 3. Top Navigation vs. Side Navigation
Within a Cluster, we utilize **Top Navigation** (`SubNavigationPosition::Top`) to present the resources as horizontal tabs.
- **Flat Tabs**: By omitting the `$navigationGroup` property on a Resource, it renders as a standalone, clickable tab. We use this for the primary data entity of the cluster (e.g., `UserResource` in the Users cluster, or `ProductResource` in Inventory).
- **Grouped Tabs (Dropdowns)**: By assigning multiple resources the *same* `$navigationGroup` (e.g., `'Configuration'`), Filament automatically groups them into a dropdown tab. This is perfect for secondary or settings-related resources like Roles and Permissions.

### 4. Routing and Tab Ordering
- When a user clicks a Cluster in the main sidebar, Filament automatically routes them to the Resource with the lowest `$navigationSort` value.
- By setting the primary flat tab to `$navigationSort = 1`, we guarantee it is the default view. Grouped dropdowns are given higher sort values (e.g., 2, 3) to ensure they sit cleanly to the right of the primary tab.

### 5. Automated Seeding
Instead of manually creating roles in the UI after a fresh installation, the foundational template relies on a `RoleAndPermissionSeeder`. This guarantees that the necessary foundational roles (`super_admin`, `admin`, `user`) are created programmatically and consistently across all environments.
