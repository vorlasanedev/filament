---
name: improve-laravel-performance
description: Analyzes and optimizes a Laravel and Filament project for local development performance, including caching and N+1 query prevention.
---

# Improve Laravel & Filament Performance

When the user asks to improve or check the performance of a Laravel or Filament project, follow these steps:

1. **Optimize .env Configuration**:
   - Use the `view_file` tool to inspect the `.env` file.
   - Look for `CACHE_STORE=database` and `SESSION_DRIVER=database`.
   - If found, use `replace_file_content` to change them to `file` (or `redis` if applicable) for much faster local development performance. Explain to the user that changing the session driver will log them out.

2. **Prevent N+1 Queries (Lazy Loading)**:
   - Use the `view_file` tool to check `app/Providers/AppServiceProvider.php`.
   - Ensure that `Illuminate\Database\Eloquent\Model` is imported.
   - Inside the `boot()` method, add `Model::preventLazyLoading(! $this->app->isProduction());`.
   - This will throw a `LazyLoadingViolationException` locally if an N+1 query occurs, making it easy to spot and fix using eager loading (`->with('relationship')`).

3. **Cache Filament Components**:
   - Use the `run_command` tool to run `php artisan filament:optimize` (if it's a Filament project). This caches Blade components and icons, providing a massive speed boost.
   - Also run `php artisan optimize:clear` or `php artisan cache:clear; php artisan config:clear; php artisan view:clear` to ensure old caches are purged.

4. **Check Local Development Environment**:
   - If the user is on Windows and using Docker (Laravel Sail), check if the project files are mounted from a Windows drive (e.g., `C:` or `D:`).
   - If so, inform the user that WSL2 cross-OS file translation is notoriously slow and recommend moving the project inside the WSL filesystem (`\\wsl$\...`) or using Laravel Herd/XAMPP.

## Manual Improvements for the Developer
Whenever analyzing performance, also provide the user with these actionable tips and the specific files they need to edit:

1. **Fixing N+1 Queries (Location: `app/Filament/Resources/*Resource.php` or `app/Http/Controllers/*.php`)**: When the `LazyLoadingViolationException` is thrown, the developer must manually go into the code and use `->with('relationship_name')` on the query (or `->modifyQueryUsing()` in Filament tables) to explicitly eager-load the data.
2. **Database Indexing (Location: `database/migrations/`)**: Check if frequently searched or filtered columns (like `email`, `phone`, or foreign keys) have database indexes in their migration files (`$table->index('column_name')`).
3. **Queue Heavy Tasks (Location: `app/Jobs/` & `app/Mail/`)**: Ensure long-running tasks like sending emails, processing uploads, or generating large exports are pushed to a background Queue (`implements ShouldQueue`) rather than running synchronously.
4. **Optimize Eloquent Queries (Location: Controllers, Livewire, or Filament Queries)**: Use `->select('id', 'name')` to fetch only the columns you actually need, instead of `SELECT *` for very large tables.
5. **Filament Resource Caching (Location: `app/Filament/Widgets/` or Custom Pages)**: Consider using Laravel's `Cache::remember()` technique or Redis if your Filament tables/widgets are displaying data that doesn't change frequently.

**Note**: Always summarize the changes you made to the user so they understand what was optimized!
