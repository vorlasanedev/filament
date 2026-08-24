---
name: new-module-template
description: Building New Modules with Row-Level Security
---
# Skill: Building New Modules with Row-Level Security

This document outlines the architectural logic and security philosophy for adding brand new feature modules (like Inventory, Employees, or Finance) to our standard Filament project template.

### 1. Modular Isolation via Clusters
Every distinct domain in the application gets its own **Filament Cluster**.
- **Separation of Concerns**: Resources belonging to a module (e.g., `ProductResource` and `WarehouseResource`) live inside their specific Cluster (e.g., `InventoryManagementCluster`).
- **UI Cleanliness**: The main sidebar only displays the Cluster icon. The actual module resources are displayed as horizontal Top Navigation tabs once the user clicks into the module.

### 2. Tab Structuring (Flat vs Dropdowns)
Inside the module's Top Navigation, we intentionally structure the tabs for optimal UX:
- **Primary Entities (Flat Tabs)**: The most frequently accessed resources (like Products in Inventory, or Profiles in Employees) are configured as **ungrouped** flat tabs, sorting them first.
- **Supporting Entities (Dropdown Tabs)**: Secondary resources (like Categories, Warehouses, Locations) are bundled together under a single `$navigationGroup` (e.g., "Configuration") so they collapse into a dropdown, saving horizontal space.

### 3. Data Ownership (Database Level)
To support granular permissions where users can only manage their own data, the database schema must support ownership.
- Every table in the module **must** include a `user_id` foreign key.
- A model trait (e.g., `HasUserOwnership`) hooks into the `creating` event to automatically bind the authenticated user's ID to the record before it saves to the database.

### 4. Row-Level Security (RLS) via Eloquent
Securing the UI ensures users don't see data they don't own.
- We override `getEloquentQuery()` in the Filament Resource.
- If the authenticated user has a restrictive role (e.g., `user_inventory`), the query is scoped (`where('user_id', auth()->id())`). This guarantees the Filament table only renders their specific records.

### 5. Policy Enforcement (Backend Level)
Hiding data in the UI is not enough; we must secure the backend routes.
- Using Filament Shield, we generate Laravel Policies for every model in the module.
- We customize the `update`, `delete`, and `restore` policy methods. Even if a user bypasses the UI and guesses the ID of a record they don't own, the Policy verifies `return $model->user_id === $authUser->id;` and blocks unauthorized modifications.
- **Force Delete**: Administrators are explicitly denied the `ForceDelete` permission via Spatie Roles, leaving that destructive action solely to the Superadmin.

