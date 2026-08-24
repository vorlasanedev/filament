---
name: fix-image-not-show
description: Zero-to-Hero instructions on properly configuring filesystems to fix broken image uploads in Filament.
---

# Skill: Fixing Broken Images (Zero to Hero)

A very common issue in Filament (and Laravel in general) is uploading an image via `FileUpload::make()`, but seeing a broken image icon when trying to display it in a table or on the frontend.

This is almost always a storage symlink or disk configuration issue.

## Phase 1: Understanding the Problem

When Filament uploads a file, it saves it to the `storage/app/public` directory by default. 
However, web browsers can only access files located inside the `public/` directory!

Laravel solves this by creating a "symlink" (a shortcut) from `public/storage` that points directly to `storage/app/public`. If this link is missing, images won't show up.

## Phase 2: The Symlink Fix

1. **Delete any broken symlinks:**
   If you migrated environments (e.g., from Windows to Linux) or cloned a repo, your old symlink might be broken.
   ```bash
   # Windows PowerShell
   Remove-Item public/storage
   
   # Linux/Mac
   rm public/storage
   ```

2. **Generate the Symlink:**
   Run the artisan command to recreate the shortcut:
   ```bash
   php artisan storage:link
   ```

## Phase 3: The Environment Fix

If the symlink exists but images *still* break, the issue is usually your `APP_URL`. 
Laravel uses this environment variable to generate the absolute URL for the image (e.g., `http://localhost:8000/storage/avatar.png`).

1. Open `.env`.
2. Ensure `APP_URL` perfectly matches how you access the site in your browser.
   
   *Wrong:*
   ```env
   APP_URL=http://localhost
   ```
   *Right (if using php artisan serve):*
   ```env
   APP_URL=http://localhost:8000
   ```
   *Right (if using Herd/Valet):*
   ```env
   APP_URL=http://myproject.test
   ```

## Phase 4: Enforcing the Public Disk

Sometimes, a Filament field might be configured to upload to the private `local` disk instead of `public`.

Check your Resource (e.g., `UserResource.php`) and explicitly set the disk to `public` on your `FileUpload` and `ImageColumn` fields.

**In the Form:**
```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('avatar_url')
    ->disk('public') // EXPLICITLY set the disk
    ->directory('avatars')
    ->image()
```

**In the Table:**
```php
use Filament\Tables\Columns\ImageColumn;

ImageColumn::make('avatar_url')
    ->disk('public') // EXPLICITLY set the disk
```

### Pro-Tips
- **Server Deployments:** You must run `php artisan storage:link` on your production server as part of your deployment script! Git does not track symlinks across OS environments reliably.
