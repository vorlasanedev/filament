---
name: change-edit-page-to-modal
description: Instructions on how to convert a Filament resource's Create and Edit pages into Modal popups.
---

# Change Create/Edit Pages to Modals in Filament

When the user asks to change a form (like Create or Edit) to a modal in a Filament Resource, follow these steps:

1. **Locate the Resource Class**:
   - Find the main Resource file for the entity (e.g., `app/Filament/Resources/UserResource.php`) using the `view_file` tool.

2. **Modify the `getPages()` Method**:
   - In Filament 3, the `CreateAction` and `EditAction` components on the List page automatically fallback to opening a Modal if the dedicated `create` or `edit` pages do not exist in the routing table.
   - Use the `replace_file_content` tool to remove the `'create'` and `'edit'` entries from the returned array in the `getPages()` method.
   
   *Before*:
   ```php
   public static function getPages(): array
   {
       return [
           'index' => Pages\ListUsers::route('/'),
           'create' => Pages\CreateUser::route('/create'),
           'edit' => Pages\EditUser::route('/{record}/edit'),
       ];
   }
   ```
   
   *After*:
   ```php
   public static function getPages(): array
   {
       return [
           'index' => Pages\ListUsers::route('/'),
       ];
   }
   ```

3. **(Optional) Clean up unused files**:
   - Once removed from `getPages()`, the dedicated `CreateRecord` and `EditRecord` page classes (e.g., `CreateUser.php` and `EditUser.php`) are technically orphaned and no longer used. You can safely delete them from the `Pages` directory using terminal commands if you wish to keep the codebase clean.

4. **Inform the User**:
   - Explain to the user that by unregistering the dedicated page routes, Filament automatically adapts the buttons on the List page to open the forms inside a beautiful modal dialog instead of redirecting them.
