# Filament Inventory Module Starter Prompt

*You can copy and paste the prompt below into a new AI chat session whenever you want to generate the exact Inventory Management module we built, complete with Row-Level Security (RLS) and granular role-based access control.*

---

**Copy the text below:**

```text
Please build a comprehensive Inventory Management module for my Laravel Filament v3 project. It must feature Top Navigation tabs and strictly enforce Row-Level Security across all its models so regular users can only edit their own records.

Here are the exact requirements:

1. **Inventory Management Cluster**: 
   - Create a Filament Cluster named `InventoryManagementCluster`.
   - Set its `$navigationIcon = 'heroicon-o-archive-box'`, `$navigationLabel = 'Inventory'`, and `$navigationSort = 3`.
   - Configure it to use Top Navigation by setting `protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;`.

2. **Models and Migrations**:
   - Create models and migrations for: `Product`, `Warehouse`, `Location`, `ProductCategory`, `StockMove`, and `StockTransfer`.
   - **CRITICAL**: Every single one of these tables MUST include a `user_id` foreign key constrained to the `users` table to track who owns/created the record.
   - Create a model trait `HasUserOwnership` that hooks into the `creating` event to automatically set `$model->user_id = auth()->id()` if the user is authenticated. Use this trait on ALL of the inventory models listed above.

3. **Resources (Flat Tabs vs Grouped)**:
   - Generate Resources for all the models and assign them to the `InventoryManagementCluster`.
   - Set `$navigationSort = 1` for `ProductResource` and do NOT define a `$navigationGroup`, making it a flat primary tab.
   - Set `$navigationSort = 2` for `StockMoveResource` and do NOT define a `$navigationGroup`, making it a flat secondary tab.
   - Group the remaining resources (`WarehouseResource`, `LocationResource`, `ProductCategoryResource`, `StockTransferResource`) under a dropdown tab by setting their `$navigationGroup = 'Configuration'`.

4. **Row-Level Security (RLS) in Resources**:
   - Override the `getEloquentQuery()` method in EVERY generated resource. 
   - Check if the user has the `user_inventory` role. If they do, apply a `where('user_id', auth()->id())` scope so they only see records they created.

5. **Security Policies**:
   - Generate Laravel Policies for all inventory models (using Filament Shield if available).
   - In every Policy, modify the `update`, `delete`, and `restore` methods to strictly enforce ownership for standard users: 
     `if ($authUser->hasRole('user_inventory')) { return $model->user_id === $authUser->id; }`

6. **Roles setup**:
   - Create an `admin_inventory` role (full CRUD access, but explicitly deny `ForceDelete` capabilities).
   - Create a `user_inventory` role (can View, Create, and Update their own records, but explicitly deny ALL `Delete` and `ForceDelete` capabilities).

Please execute the necessary bash commands and write the code to implement this exact structure.
```
