# Filament Employee Module Starter Prompt

*You can copy and paste the prompt below into a new AI chat session whenever you want to generate the exact Employee Management module we built, complete with top navigation tabs, Row-Level Security (RLS) for own records, and role-based access control.*

---

**Copy the text below:**

```text
Please build an Employee Management module for my Laravel Filament v3 project. It must feature Top Navigation tabs and strictly enforce Row-Level Security so regular users can only edit their own profiles.

Here are the exact requirements:

1. **Employee Management Cluster**: 
   - Create a Filament Cluster named `EmployeeManagementCluster`.
   - Set its `$navigationIcon = 'heroicon-o-user-group'`, `$navigationLabel = 'Employees'`, and `$navigationSort = 2`.
   - Configure it to use Top Navigation by setting `protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;`.

2. **Models and Migrations**:
   - Create models and migrations for `Employee`, `Department`, and `Position`.
   - **CRITICAL**: The `employees` table MUST include a `user_id` foreign key constrained to the `users` table.
   - Create a model trait `HasUserOwnership` that hooks into the `creating` event to automatically set `$model->user_id = auth()->id()` if the user is authenticated. Use this trait on the `Employee` model.

3. **Employee Resource (Primary Flat Tab)**:
   - Generate an `EmployeeResource` and assign it to the `EmployeeManagementCluster`.
   - Set `$navigationSort = 1`.
   - Do NOT define a `$navigationGroup` property, so it renders as a flat, standalone tab that sorts first.
   - **Row-Level Security**: Override `getEloquentQuery()` in the resource to check if the user has the `user_employee` role. If so, apply a `where('user_id', auth()->id())` scope so they only see their own record.

4. **Configuration Dropdown (Departments & Positions)**:
   - Generate a `DepartmentResource` and `PositionResource` inside the cluster.
   - Assign both to the same `$navigationGroup = 'Configuration'`.
   - Set `$navigationSort = 2` and `3` respectively so they group into a dropdown tab.

5. **Security Policies**:
   - Generate Laravel Policies for these models (using Filament Shield if available).
   - In `EmployeePolicy`, modify the `update`, `delete`, and `restore` methods to strictly enforce ownership for standard users: 
     `if ($authUser->hasRole('user_employee')) { return $employee->user_id === $authUser->id; }`

6. **Roles setup**:
   - Ensure you create an `admin_employee` role (full CRUD, no force delete) and a `user_employee` role (can only View and Update their own Employee record).

Please execute the necessary bash commands and write the code to implement this exact structure.
```
