<?php

namespace App\Traits;

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;

trait HasDynamicMenuPermission
{
    /**
     * Resolve the dynamic permission key (e.g. view_menu_users) for this class.
     */
    public static function getDynamicMenuPermission(): ?string
    {
        $class = static::class;

        return Cache::rememberForever('menu_perm_' . md5($class), function () use ($class) {
            $menu = Menu::where('class_name', $class)->first();

            if ($menu) {
                return $menu->permission_name;
            }

            $slug = Menu::generateSlugForClass($class);

            return 'view_menu_' . $slug;
        });
    }

    /**
     * Check if the authenticated user has access to this menu/component.
     */
    public static function hasDynamicMenuAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // 1. Super Admin bypass (handled also by Gate::before, but explicit check for fast-path)
        if ($user->hasRole('Super Admin') || $user->hasRole('super_admin')) {
            return true;
        }

        $class = static::class;

        // 2. Check if the menu is disabled by administrator
        $isActive = Cache::remember('menu_active_' . md5($class), 300, function () use ($class) {
            $menu = Menu::where('class_name', $class)->first();

            return $menu ? (bool) $menu->is_active : true;
        });

        if (! $isActive) {
            return false;
        }

        // 3. Check dynamic permission
        $permission = static::getDynamicMenuPermission();

        if (! $permission) {
            return true;
        }

        return $user->can($permission);
    }

    /**
     * Controls direct URL access. Throws 403 Unauthorized if false.
     */
    public static function canAccess(): bool
    {
        return static::hasDynamicMenuAccess();
    }

    /**
     * Controls whether the resource list can be viewed.
     */
    public static function canViewAny(): bool
    {
        return static::hasDynamicMenuAccess();
    }

    /**
     * Controls visibility of the navigation item in sidebar and top navigation.
     */
    public static function shouldRegisterNavigation(): bool
    {
        return static::hasDynamicMenuAccess();
    }
}
