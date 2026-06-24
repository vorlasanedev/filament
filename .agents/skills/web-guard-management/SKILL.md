---
name: web-guard-management
description: Workflow and instructions for managing and setting up custom auth guards (like web guards) in Laravel and Filament.
---

# Web Guard Management Workflow in Filament

This skill outlines the workflow and relationship between files when you need to manage, create, or customize Authentication Guards (like the default `web` guard) for Filament Panels. 

By following this workflow, you can set up different guards for different types of users (e.g., `admin`, `customer`, `employee`) and assign them to specific Filament panels.

## 1. Define the Guard & Provider (`config/auth.php`)
This is the central configuration file for authentication in Laravel. Here you define **Guards** (how users are authenticated, e.g., via session) and **Providers** (where the users are retrieved from, e.g., Eloquent model).

- **File:** `config/auth.php`
- **What to do:**
  - Add your new guard under the `'guards'` array (e.g., `'admin' => ['driver' => 'session', 'provider' => 'admins']`).
  - Add the corresponding provider under the `'providers'` array pointing to your Eloquent model (e.g., `'admins' => ['driver' => 'eloquent', 'model' => App\Models\Admin::class]`).

## 2. Prepare the Model (`app/Models/YourModel.php`)
The Eloquent model that represents your user needs to be capable of authentication and must authorize access to the Filament panel.

- **File:** `app/Models/User.php` or your custom model like `app/Models/Admin.php`
- **What to do:**
  - Ensure the model extends `Illuminate\Foundation\Auth\User as Authenticatable`.
  - Implement the `Filament\Models\Contracts\FilamentUser` interface.
  - Add the `canAccessPanel(Panel $panel): bool` method to define the logic for who is allowed to log in to the panel.

## 3. Configure the Filament Panel (`app/Providers/Filament/YourPanelProvider.php`)
Each Filament panel has a Provider file where you register its configuration, including which guard it should use.

- **File:** `app/Providers/Filament/AdminPanelProvider.php` (or whichever panel you are modifying)
- **What to do:**
  - Add the `->authGuard('your_guard_name')` method inside the `panel(Panel $panel)` configuration chain. By default, Filament uses the `web` guard.
  - Make sure `->login()` is enabled if you want Filament to handle the login page for this guard.

## 4. Middleware Configuration (Optional)
If you have custom logic for redirecting unauthenticated users or users who are already logged in, you might need to adjust middleware.

- **Files:** `app/Http/Middleware/Authenticate.php` or `bootstrap/app.php` (depending on your Laravel version, Laravel 11 uses `bootstrap/app.php`).
- **What to do:** Adjust redirection logic based on the guard. For instance, if an admin tries to access a protected route while unauthenticated, redirect them to the admin login page instead of the default user login.

## Summary Checklist to Do It Yourself:
1. [ ] Edit `config/auth.php` to define the new guard and provider.
2. [ ] Create or update your Model to extend `Authenticatable` and implement `FilamentUser`.
3. [ ] Edit your Panel Provider (e.g., `AdminPanelProvider.php`) and add `->authGuard('your_guard')`.
4. [ ] Clear your config cache: `php artisan config:clear`.
