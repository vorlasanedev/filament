---
name: web-guard-management
description: Zero-to-Hero instructions on setting up custom Auth Guards for frontend and backend separation.
---

# Skill: Web Guard Management (Zero to Hero)

By default, Laravel uses a single auth guard (`web`). If you are building a system where "Admins" log into Filament, but "Customers" log into a custom React/Vue frontend (or a separate Blade frontend), you usually want to separate them using multiple Guards.

## Phase 1: Define the Guards

Open `config/auth.php`. You will define separate guards and providers for Admins and Customers.

```php
'guards' => [
    'web' => [ // Used for standard frontend users
        'driver' => 'session',
        'provider' => 'users',
    ],
    'admin' => [ // Used for Filament!
        'driver' => 'session',
        'provider' => 'admins',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class, // Normal users
    ],
    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class, // Admin model
    ],
],
```

## Phase 2: Configure Filament to use the Guard

You must tell Filament to stop using the default `web` guard and use the new `admin` guard.

Open `app/Providers/Filament/AdminPanelProvider.php`.

Add the `->authGuard()` method to the chain:

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->authGuard('admin') // Tell Filament to use the admin guard!
        ->login()
        // ...
}
```

## Phase 3: The Authentication Model

If you created a new `Admin` model for this guard (as shown in Phase 1), ensure the model extends `Authenticatable` and implements `FilamentUser` so they are allowed to log into the panel.

```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class Admin extends Authenticatable implements FilamentUser
{
    // ...

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Or add strict logic here based on roles
    }
}
```

## Phase 4: Using Auth in Code

Once guards are separated, you must be careful when calling `auth()->user()`.

- **In the Frontend:** `auth()->user()` or `auth('web')->user()` retrieves the Customer.
- **In Filament:** `auth('admin')->user()` retrieves the Admin user. Filament handles this automatically inside its own components, but if you write custom logic, you must specify the guard!

### Pro-Tips
- **Do I *need* multiple guards?** If your Admins and Customers share the same database table (`users`) and just have different *Roles* (e.g., via Spatie Permissions), you DO NOT need multiple guards! Just use the `FilamentUser` interface's `canAccessPanel()` method to reject customers. Only use multiple guards if they are entirely different models/tables!
