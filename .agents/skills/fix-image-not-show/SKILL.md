---
name: fix-image-not-show
description: Fixes broken images in Filament by properly configuring filesystems and public storage paths.
---

# Fix Image Not Showing in Filament

When images uploaded via Filament (like `FileUpload::make('avatar_url')`) are broken or fail to display properly, follow these steps to resolve the issue:

1. **Check `FILESYSTEM_DISK` in `.env`**
   Filament uses the default disk. If your `.env` has `FILESYSTEM_DISK=local`, files will upload to `storage/app/private/` and will not be web-accessible.
   - Change `.env` to `FILESYSTEM_DISK=public`.

2. **Ensure Relative URLs for Public Disk**
   If `APP_URL` is set to `http://localhost`, it will cause image URLs to use port 80 and fail to load when running `php artisan serve` (port 8000).
   - In `config/filesystems.php`, change the `url` for the `public` disk from `env('APP_URL').'/storage'` to simply `'/storage'`.

3. **Run `storage:link`**
   Ensure the symlink exists from `public/storage` to `storage/app/public` by running:
   ```bash
   php artisan storage:link
   ```

4. **Clear Configuration Cache**
   Always clear the cache so the `filesystems.php` changes take effect:
   ```bash
   php artisan config:clear
   ```

5. **Clean Up Old Files (Optional)**
   Move any files accidentally uploaded to `storage/app/private` (or `storage/app/avatars`) into `storage/app/public/` so they become accessible.
