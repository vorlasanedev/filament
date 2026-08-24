---
name: update-list-page-to-full-page
description: Updates a Filament resource list page (or any page) to use the full width of the screen.
---

# Update List Page to Full Screen

When the user asks to make a Filament page (like a table, list, or resource page) full screen or full width, follow these steps:

1. **Locate the Target Page**:
   - Identify the resource and page the user is referring to (e.g., `app/Filament/Resources/Roles/Pages/ListRoles.php` or `app/Filament/Resources/UserResource/Pages/ListUsers.php`).
   - Use the `view_file` tool to inspect the page class.

2. **Add the `getMaxContentWidth` Method**:
   - Check if the `getMaxContentWidth` method already exists in the class.
   - If it doesn't, use the `replace_file_content` tool to add the following method to the class:
     ```php
     public function getMaxContentWidth(): \Filament\Support\Enums\Width|string|null
     {
         return 'full';
     }
     ```
   - *Note: For Filament v3, you can also return an enum like `\Filament\Support\Enums\Width::Full`, but returning the string `'full'` works universally.*

3. **Verify the Change**:
   - Ensure the PHP syntax is correct and the method is placed cleanly inside the class body, typically right below the `getActions()` or `getHeaderActions()` array.
   - Inform the user that the file has been updated and the page will now span the full width of their screen upon refresh.
