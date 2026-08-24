# ToDo: Configurable Sub-Menu Visibility Options

## Goal
Each module must support configurable sub-menu visibility options. Administrators should be able to show or hide specific sub-menus for individual users, allowing fine-grained control over which features are accessible. This ensures that permissions are not only role-based (Superadmin, Administrator, User) but also menu-specific, enabling restrictions or access at the sub-module level.

## Example Permissions Matrix with Sub-Menu Control

| Module | Role | Allowed Actions | Sub-Menu Visibility Control |
| :--- | :--- | :--- | :--- |
| **Inventory** | Superadmin | Full CRUD + force delete | Can show/hide all sub-menus |
| | Admin Inventory | CRUD + delete (no force delete) | Can show/hide sub-menus for users |
| | User Inventory | View, add, update own records | Sub-menus restricted by admin |
| **Employee** | Superadmin | Full CRUD + force delete | Can show/hide all sub-menus |
| | Admin Employee | CRUD + delete (no force delete) | Can show/hide sub-menus for users |
| | User Employee | View, update own profile | Sub-menus restricted by admin |

## Implementation Action Items
- [ ] Define how sub-menu visibility settings will be stored (e.g., a pivot table `user_menu_visibility`, JSON column on the `User` model, or leveraging direct Filament Shield permissions per user).
- [ ] Create a UI component (potentially on the User Edit page or a dedicated access control page) that allows Admins to toggle specific resource access for individual users.
- [ ] Enforce visibility at the Filament Navigation level using the `canViewAny()` or similar authorization logic on individual Resources.
- [ ] Ensure that an `Admin Inventory` can only toggle visibility for Inventory-related sub-menus (Products, Warehouses, etc.) and cannot alter Employee-related visibility.
- [ ] Ensure that only `Superadmin` can override everything globally.
