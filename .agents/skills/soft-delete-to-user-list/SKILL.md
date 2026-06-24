---
name: soft-delete-to-user-list
description: Instructions on how to add Soft Deletes (TrashedFilter, Restore, ForceDelete) to a Filament Resource table.
---

# Add Soft Deletes to a Filament Resource

When the user asks to add Soft Deletes functionality to a Filament Resource (like the User list or any other table), follow these steps:

1. **Verify the Model**:
   - Check the Eloquent model (e.g., `app/Models/User.php`) using the `view_file` tool to ensure it uses the `Illuminate\Database\Eloquent\SoftDeletes` trait.
   - If it doesn't, add `use SoftDeletes;` inside the model class and import the trait.

2. **Update the Resource Table Filters**:
   - Open the corresponding Filament Resource (e.g., `app/Filament/Resources/UserResource.php`).
   - In the `table(Table $table)` method, add the `TrashedFilter` to the `filters([])` array:
     ```php
     Tables\Filters\TrashedFilter::make(),
     ```

3. **Update Record Actions**:
   - In the `recordActions([])` array of the table, ensure the following actions are present:
     ```php
     Tables\Actions\EditAction::make(),
     Tables\Actions\DeleteAction::make(),
     Tables\Actions\RestoreAction::make(),
     Tables\Actions\ForceDeleteAction::make(),
     ```

4. **Update Bulk Actions**:
   - In the `groupedBulkActions([])` or `bulkActions([])` array, ensure the following bulk actions are present:
     ```php
     Tables\Actions\DeleteBulkAction::make(),
     Tables\Actions\RestoreBulkAction::make(),
     Tables\Actions\ForceDeleteBulkAction::make(),
     ```

5. **Verify Imports**:
   - Ensure the necessary Action classes are imported at the top of the file if they are not prefixed with `Tables\Actions\`.

6. **Inform the User**:
   - Let the user know that the table now supports filtering by "Trashed" records, and they can easily Restore or permanently Force Delete them directly from the UI.
