<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'class_name',
        'navigation_group',
        'navigation_label',
        'navigation_sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'navigation_sort' => 'integer',
    ];

    public static function booted(): void
    {
        static::saved(function (Menu $menu) {
            \Illuminate\Support\Facades\Cache::forget('menu_active_' . md5($menu->class_name));
            \Illuminate\Support\Facades\Cache::forget('menu_perm_' . md5($menu->class_name));
            \Illuminate\Support\Facades\Cache::forget('dynamic_menus_all');
            \Illuminate\Support\Facades\Cache::forget('dynamic_menus_grouped');
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        });

        static::deleted(function (Menu $menu) {
            \Illuminate\Support\Facades\Cache::forget('menu_active_' . md5($menu->class_name));
            \Illuminate\Support\Facades\Cache::forget('menu_perm_' . md5($menu->class_name));
            \Illuminate\Support\Facades\Cache::forget('dynamic_menus_all');
            \Illuminate\Support\Facades\Cache::forget('dynamic_menus_grouped');
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        });
    }

    /**
     * Get the generated permission name for this menu.
     */
    public function getPermissionNameAttribute(): string
    {
        return 'view_menu_' . $this->slug;
    }

    /**
     * Scope a query to only include active menus.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Associated Spatie permission instance.
     */
    public function getPermission(): ?Permission
    {
        return Permission::where('name', $this->permission_name)->first();
    }

    /**
     * Generate a uniform, clean slug for any Filament class.
     */
    public static function generateSlugForClass(string $class): string
    {
        if (is_subclass_of($class, \Filament\Resources\Resource::class)) {
            if (method_exists($class, 'getSlug')) {
                $slug = $class::getSlug();
                if (filled($slug)) {
                    return Str::snake(str_replace(['/', '-'], '_', $slug));
                }
            }
        }

        $base = class_basename($class);
        $base = preg_replace('/(Resource|Cluster|Widget|Page)$/', '', $base) ?: $base;

        return Str::snake(Str::plural($base));
    }
}
