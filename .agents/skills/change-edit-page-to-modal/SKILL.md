---
name: change-edit-page-to-modal
description: Zero-to-Hero instructions on converting a standard Filament resource into a Modal-based CRUD interface.
---

# Skill: Converting Pages to Modals (Zero to Hero)

By default, Filament generates separate full pages for Creating and Editing records. This guide explains how to convert a Resource to use elegant pop-up Modals instead, which keeps users on the List page.

## Phase 1: Removing the Pages

If you generated a standard Resource, Filament created three pages: `List`, `Create`, and `Edit`.
To use modals, you must delete the Create and Edit pages.

1. **Delete the files:**
   - `app/Filament/Resources/UserResource/Pages/CreateUser.php`
   - `app/Filament/Resources/UserResource/Pages/EditUser.php`

2. **Update the Resource Pages array:**
   Open your Resource (e.g., `UserResource.php`) and remove the routes for `create` and `edit`.
   
   ```php
   public static function getPages(): array
   {
       return [
           // ONLY keep the index route!
           'index' => Pages\ListUsers::route('/'),
       ];
   }
   ```

## Phase 2: Wiring the Actions

Now that the separate pages are gone, you must tell the List page to use Modals.

1. **Update the List Page (Create Modal):**
   Open `app/Filament/Resources/UserResource/Pages/ListUsers.php`.
   
   ```php
   protected function getHeaderActions(): array
   {
       return [
           // This will automatically open a Modal because the Create page route is gone!
           Actions\CreateAction::make(), 
       ];
   }
   ```

2. **Update the Resource Table (Edit Modal):**
   Open `UserResource.php` and look at the `table()` method.
   
   ```php
   ->actions([
       // Because the Edit page route is gone, this automatically opens a Modal!
       Tables\Actions\EditAction::make(),
       Tables\Actions\DeleteAction::make(),
   ])
   ```

## Phase 3: Fine-Tuning the Modals

You can customize how the Modals behave by chaining methods onto the Actions.

```php
// Make the modal wider
Tables\Actions\EditAction::make()
    ->modalWidth('4xl');

// Prevent closing the modal by clicking the backdrop
Actions\CreateAction::make()
    ->modalCloseButton(false)
    ->closeModalByClickingAway(false);

// Change the modal header title
Tables\Actions\EditAction::make()
    ->modalHeading('Modify User Details');
```

### Pro-Tips
- **Form Schema:** The modal uses the exact same fields defined in your Resource's `form()` method.
- **Slide-overs:** If you prefer a slide-over panel instead of a center modal, just chain `->slideOver()` to your Action!
