---
name: fix-search-user-list
description: Zero-to-Hero instructions on fixing the Filament null record crash when creating records via Modals with active search filters.
---

# Skill: Fixing the Modal Search Crash (Zero to Hero)

When you convert a Filament Resource's Create/Edit pages into Modals (using `CreateAction::make()` on the List page), you might encounter a devastating 500 Internal Server Error when users try to create a record while a search filter is active.

**The Error:**
`Filament\Resources\Resource::getEditAuthorizationResponse(): Argument #1 ($record) must be of type Illuminate\Database\Eloquent\Model, null given`

## Phase 1: Understanding the Bug

When a record is created via a Modal, Filament attempts to fetch the *newly created record* from the database to instantly inject it into your table UI dynamically. 

However, Filament accidentally applies the *current table search query* to that fetch! 
If you search for "John", and then click "New User" and create "Jane", the query searches for `id = [NewId] AND name LIKE '%John%'`. Since it doesn't match, the database returns `null`. Filament then passes `null` to the row's `EditAction` authorization check, triggering a PHP `TypeError` crash.

## Phase 2: The Universal Fix

To fix this, we must instruct the `CreateAction` to clear the table search immediately before saving the record.

Open your Resource's List page (e.g., `app/Filament/Resources/UserResource/Pages/ListUsers.php`).

Add the `->before()` hook to your `CreateAction`:

```php
namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    // ...

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                // Add this hook to reset the search before saving!
                ->before(function ($livewire) {
                    $livewire->resetTableSearch();
                }),
        ];
    }
}
```

By resetting the table search before the creation finishes, Filament fetches the new row in a clean context, injecting it perfectly without crashing!

## Phase 3: Automated Testing

To ensure this bug never regressions, you can write a Livewire test using Pest/PHPUnit. This test mimics a user typing in the search box and then opening the modal to create a record.

Create a test file: `php artisan make:test UserResourceSearchCrashTest`

**Test Code (`tests/Feature/UserResourceSearchCrashTest.php`):**

```php
use App\Models\User;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use function Pest\Livewire\livewire;

it('can create a user while a search filter is active without crashing', function () {
    // 1. Authenticate as an admin
    $admin = User::factory()->create(['is_active' => true]);
    $this->actingAs($admin);

    // 2. Test the Livewire component directly
    livewire(ListUsers::class)
        // 3. Simulate typing a search that definitely won't match the new user
        ->set('tableSearch', 'ImpossibleSearchTerm123')
        
        // 4. Trigger the CreateAction modal
        ->callAction('create', data: [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'is_active' => true,
        ])
        
        // 5. Assert the action succeeds and no 500 error is thrown!
        ->assertHasNoActionErrors()
        ->assertSuccessful();
        
    // 6. Verify Jane was actually saved
    $this->assertDatabaseHas('users', [
        'email' => 'jane@example.com',
    ]);
});
```

### Pro-Tips
- **Other Filters:** If you have custom complex Table Filters (not just the search box) that exclude newly created records, you can also reset them inside the `before()` hook using `$livewire->resetTableFilters()`.
- **EditAction:** This bug only occurs if your table has an `EditAction` or `DeleteAction` that relies on the fetched `$record` to run Policy authorization checks!
