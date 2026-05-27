# Filament Project Starter Prompt

*You can copy and paste the prompt below into a new AI chat session whenever you want to bootstrap a brand new Laravel Filament project with the exact User, Role, and Permission structure we built.*

---

**Copy the text below:**

```text
Please create a new Laravel 11 project with Filament v3 installed, and set up a foundational User Management module with Roles and Permissions. 

Here are the exact requirements:

1. **Packages**: Install `filament/filament` and `bezhansalleh/filament-shield` for role and permission management.
2. **User Management Cluster**: 
   - Create a Filament Cluster named `UserManagementCluster`.
   - Set its `$navigationIcon = 'heroicon-o-users'`, `$navigationLabel = 'Users'`, and `$navigationSort = 1`.
   - Configure it to use Top Navigation by setting `protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;`.
3. **User Resource (Primary Flat Tab)**:
   - Generate a `UserResource` and assign it to the `UserManagementCluster`.
   - Set its `$navigationSort = 1`.
   - **CRITICAL**: Do NOT define a `$navigationGroup` property on the `UserResource`. It must remain ungrouped so that it renders as a flat, standalone tab (without a dropdown) and automatically sorts first.
4. **Role & Permission Resources (Configuration Dropdown)**:
   - Configure the Shield `RoleResource` and `PermissionResource` to belong to the `UserManagementCluster`.
   - Assign both of them to the same `$navigationGroup = 'Configuration'`.
   - Set `$navigationSort = 2` for Roles and `$navigationSort = 3` for Permissions.
5. **Database Seeding**:
   - Create a `RoleAndPermissionSeeder`.
   - The seeder should create a `super_admin` role (which bypasses all gates), an `admin` role, and a standard `user` role.
   - Run the seeder as part of the setup process.
6. **Panel Provider**:
   - In `AdminPanelProvider`, define global navigation groups if necessary to ensure 'Users' is positioned correctly in the main sidebar.

Please execute the necessary bash commands to initialize the project, install the packages, and write the required code to fulfill these requirements.
```
