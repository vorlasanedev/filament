---
name: soft-delete-to-user-list
description: Zero-to-Hero instructions on implementing Soft Deletes in Filament.
---

# Skill: Implementing Soft Deletes (Zero to Hero)

Soft Deletes allow you to "delete" records in the database without actually wiping the data (they just get a `deleted_at` timestamp). This guide shows how to fully implement this in a Filament Resource.

## Phase 1: Database & Model Preparation

Before Filament can manage soft deletes, the model and database table must support them.

1. **Add to Database Migration:**
   Ensure your table has the `deleted_at` column.
   ```php
   public function up(): void
   {
       Schema::table('users', function (Blueprint $table) {
           $table->softDeletes(); // Adds the deleted_at column
       });
   }
   ```
   *Run `php artisan migrate` if you created a new migration!*

2. **Add Trait to the Model:**
   Open the model (e.g., `app/Models/User.php`) and add the `SoftDeletes` trait.
   ```php
   namespace App\Models;

   use Illuminate\Database\Eloquent\SoftDeletes; // Import it!

   class User extends Authenticatable
   {
       use SoftDeletes; // Add it inside the class!
   }
   ```

## Phase 2: Wiring it into Filament

Now we tell the Filament Resource to display and manage these soft-deleted records.

Open your resource class (e.g., `UserResource.php`).

1. **Add the Trashed Filter:**
   In the `table()` method's `filters()` array, add the `TrashedFilter`. This gives users a dropdown to view "Without Trashed", "With Trashed", or "Only Trashed" records.
   ```php
   use Filament\Tables\Filters\TrashedFilter;

   ->filters([
       TrashedFilter::make(),
   ])
   ```

2. **Add Restore & Force Delete Row Actions:**
   In the `actions()` array, add the buttons to interact with individual deleted rows.
   ```php
   use Filament\Tables\Actions\RestoreAction;
   use Filament\Tables\Actions\ForceDeleteAction;

   ->actions([
       // Existing actions like EditAction::make(),
       RestoreAction::make(),
       ForceDeleteAction::make(),
   ])
   ```

3. **Add Restore & Force Delete Bulk Actions:**
   In the `groupedBulkActions()` array, add the buttons to interact with multiple selected records.
   ```php
   use Filament\Tables\Actions\RestoreBulkAction;
   use Filament\Tables\Actions\ForceDeleteBulkAction;

   ->bulkActions([
       Tables\Actions\BulkActionGroup::make([
           // Existing bulk actions...
           RestoreBulkAction::make(),
           ForceDeleteBulkAction::make(),
       ]),
   ])
   ```

## Phase 3: Handling View & Edit Pages (Optional)

If your resource uses separate View or Edit pages (not modals), you must also add the `TrashedFilter` traits to those pages to prevent 404 errors when attempting to view a soft-deleted record.

In `app/Filament/Resources/UserResource/Pages/EditUser.php`:
```php
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;

protected function getHeaderActions(): array
{
    return [
        RestoreAction::make(),
        ForceDeleteAction::make(),
    ];
}
```

### Pro-Tips
- **Hidden by Default:** The Restore and Force Delete actions are smart. They will automatically hide themselves if the record is *not* soft-deleted, keeping your UI clean!
