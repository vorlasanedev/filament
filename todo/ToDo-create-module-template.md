# ToDo: Create a New Module Template

Use this checklist whenever you are building a new feature module (like Inventory or Finance) to ensure it strictly follows our application architecture for UI layout and database security.

## 1. Create the Module Cluster
- [ ] Create a new Cluster class (e.g., `FinanceManagementCluster`).
- [ ] Configure the Cluster icon, label, and navigation sort order.
- [ ] Set `protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;` to enable Top Navigation.

## 2. Prepare Database Ownership
- [ ] Create database migrations for the new module's tables.
- [ ] **CRITICAL**: Add `$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();` to every table to track record ownership.
- [ ] Run `php artisan migrate`.
- [ ] Add the `use \App\Models\Traits\HasUserOwnership;` trait to every Eloquent Model in the new module to automatically assign the authenticated user's ID upon creation.

## 3. Configure Filament Resources
- [ ] Generate the Filament Resources and assign them to the new Cluster.
- [ ] Identify the **Primary Resource** (e.g., `FinanceRequestResource`). Make it a flat tab by sorting it first (`$navigationSort = 1`) and omitting the `$navigationGroup` property.
- [ ] Identify the **Secondary Resources** (e.g., Settings, Categories). Group them into a dropdown tab by assigning them the same `$navigationGroup` (e.g., `'Configuration'`) and sorting them sequentially.

## 4. Enforce Row-Level Security (UI Level)
- [ ] In EVERY generated resource, override the `getEloquentQuery()` method.
- [ ] Check if the user has the module's standard user role (e.g., `user_finance`).
- [ ] If they do, apply the scope: `$query->where('user_id', auth()->id());` to hide records they don't own.

## 5. Generate and Patch Backend Security Policies
- [ ] Run `php artisan shield:generate --all` to generate Laravel Policies for the new models.
- [ ] Open each generated policy.
- [ ] Patch the `update`, `delete`, and `restore` methods to enforce ownership. Example:
  ```php
  if ($authUser->hasRole('user_finance')) {
      return $model->user_id === $authUser->id;
  }
  ```

## 6. Configure Roles & Access Control
- [ ] Create the Admin role (e.g., `admin_finance`). Grant full CRUD access, but explicitly deny the `ForceDelete` permission.
- [ ] Create the User role (e.g., `user_finance`). Grant View, Create, and Update access, but explicitly deny `Delete` and `ForceDelete` permissions.
- [ ] Test the implementation by logging in as both role types to verify UI visibility and backend restriction logic.
