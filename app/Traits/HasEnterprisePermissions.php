<?php

namespace App\Traits;

use App\Services\PermissionDiscoveryService;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasEnterprisePermissions
{
    /**
     * Get the permission identifier for this resource/page.
     */
    public static function getPermissionIdentifier(): string
    {
        if (is_subclass_of(static::class, Resource::class)) {
            $modelClass = static::getModel();
            if ($modelClass) {
                return PermissionDiscoveryService::getModelKey($modelClass);
            }
        }

        if (is_subclass_of(static::class, Page::class)) {
            return 'page.' . Str::snake(preg_replace('/Page$/', '', class_basename(static::class)));
        }

        return Str::snake(class_basename(static::class));
    }

    /**
     * Check if current user has Super Admin role or specific permission.
     */
    public static function checkEnterprisePermission(string $action): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // 1. Super Admin bypass
        if ($user->hasRole('Super Admin') || $user->hasRole('super_admin')) {
            return true;
        }

        $key = static::getPermissionIdentifier();

        // Primary: Check {model}.{action}
        if ($user->can("{$key}.{$action}")) {
            return true;
        }

        // Backward compatibility fallback for legacy permissions or view_menu_*
        if ($action === 'menu' && ($user->can("{$key}.view_any") || $user->can("view_menu_{$key}"))) {
            return true;
        }

        $pascalModel = Str::studly($key);
        $pascalAction = Str::studly($action);
        if ($user->can("{$pascalAction}:{$pascalModel}")) {
            return true;
        }

        return false;
    }

    /**
     * Controls whether navigation item is registered.
     */
    public static function shouldRegisterNavigation(): bool
    {
        return static::checkEnterprisePermission('menu');
    }

    /**
     * Direct URL access & authorization (aborts 403 on denial).
     */
    public static function canAccess(): bool
    {
        return static::checkEnterprisePermission('view_any') || static::checkEnterprisePermission('menu');
    }

    /**
     * List records view permission.
     */
    public static function canViewAny(): bool
    {
        return static::checkEnterprisePermission('view_any');
    }

    /**
     * View single record permission.
     */
    public static function canView(Model $record): bool
    {
        return static::checkEnterprisePermission('view');
    }

    /**
     * Create record permission.
     */
    public static function canCreate(): bool
    {
        return static::checkEnterprisePermission('create');
    }

    /**
     * Edit record permission.
     */
    public static function canEdit(Model $record): bool
    {
        return static::checkEnterprisePermission('update');
    }

    /**
     * Delete record permission.
     */
    public static function canDelete(Model $record): bool
    {
        return static::checkEnterprisePermission('delete');
    }

    /**
     * Restore record permission.
     */
    public static function canRestore(Model $record): bool
    {
        return static::checkEnterprisePermission('restore');
    }

    /**
     * Force delete record permission.
     */
    public static function canForceDelete(Model $record): bool
    {
        return static::checkEnterprisePermission('force_delete');
    }
}
