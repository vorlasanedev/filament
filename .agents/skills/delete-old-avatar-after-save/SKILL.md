---
name: delete-old-avatar-after-save
description: Ensure old uploaded image files (e.g. avatars) are deleted from storage when they are updated or when the record is deleted.
---

# Delete Old Uploaded Files After Save

By default, Filament and Laravel do not automatically delete old files when a `FileUpload` field is updated or when the underlying model is deleted. This can lead to orphaned files filling up your storage.

To fix this, you should hook into the Eloquent model's events to manage the lifecycle of the file.

### Instructions

1. Identify the model that handles the file upload (e.g., `User.php`).
2. Implement the `booted` method in the model to listen for the `updated` and `deleted` (or `forceDeleted` if using soft deletes) events.
3. Check if the file attribute was changed, retrieve the original file path, and use the `Storage` facade to delete the old file.

### Example Code

```php
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    // ...

    public static function booted(): void
    {
        // Handle when a user replaces their avatar or removes it completely
        static::updated(function ($model) {
            if ($model->wasChanged('avatar_url')) {
                $oldFile = $model->getOriginal('avatar_url');
                
                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
        });

        // Handle when a user is completely deleted
        // Note: Use `deleted` if you don't have SoftDeletes, and `forceDeleted` if you do.
        static::forceDeleted(function ($model) {
            if ($model->avatar_url && Storage::disk('public')->exists($model->avatar_url)) {
                Storage::disk('public')->delete($model->avatar_url);
            }
        });
    }
}
```

### Key Considerations
- **Soft Deletes**: If the model uses the `SoftDeletes` trait, use `static::forceDeleted` instead of `static::deleted`. Soft-deleted models might still be restored, and their avatars should remain intact until permanently removed.
- **Storage Disk**: Make sure you reference the correct disk (e.g., `Storage::disk('public')`) corresponding to where your files are uploaded.
