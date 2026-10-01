<?php

namespace App\Services;

use App\Models\Menu;
use Filament\Clusters\Cluster;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class MenuSyncService
{
    /**
     * Synchronize all Filament components with the menus and permissions tables.
     *
     * @param string|null $panelId
     * @return array
     */
    public function sync(?string $panelId = 'admin'): array
    {
        if ($panelId && isset(Filament::getPanels()[$panelId])) {
            Filament::setCurrentPanel(Filament::getPanel($panelId));
        }

        $discovered = [];

        // 1. Discover Resources
        foreach (Filament::getResources() as $resourceClass) {
            if ($this->shouldIgnoreClass($resourceClass)) {
                continue;
            }

            $discovered[] = $this->syncEntity(
                class: $resourceClass,
                type: 'resource',
                label: $this->resolveNavigationLabel($resourceClass),
                group: $this->resolveNavigationGroup($resourceClass),
                sort: $this->resolveNavigationSort($resourceClass)
            );
        }

        // 2. Discover Pages & Clusters
        foreach (Filament::getPages() as $pageClass) {
            if ($this->shouldIgnoreClass($pageClass)) {
                continue;
            }

            $isCluster = is_subclass_of($pageClass, Cluster::class);
            $type = $isCluster ? 'cluster' : 'page';

            $discovered[] = $this->syncEntity(
                class: $pageClass,
                type: $type,
                label: $this->resolveNavigationLabel($pageClass),
                group: $this->resolveNavigationGroup($pageClass),
                sort: $this->resolveNavigationSort($pageClass)
            );
        }

        // 3. Discover Widgets
        foreach (Filament::getWidgets() as $widgetClass) {
            if ($this->shouldIgnoreClass($widgetClass)) {
                continue;
            }

            $name = Str::headline(class_basename($widgetClass));

            $discovered[] = $this->syncEntity(
                class: $widgetClass,
                type: 'widget',
                label: $name,
                group: 'Widgets',
                sort: null
            );
        }

        // Clear permissions and menu cache
        $this->clearCache();

        return $discovered;
    }

    /**
     * Sync single entity into menus table and create corresponding Spatie permission.
     */
    protected function syncEntity(string $class, string $type, ?string $label, ?string $group, ?int $sort): Menu
    {
        $existing = Menu::where('class_name', $class)->first();

        $slug = $existing?->slug ?? Menu::generateSlugForClass($class);

        // Ensure unique slug
        $baseSlug = $slug;
        $counter = 1;
        while (Menu::where('slug', $slug)->where('class_name', '!=', $class)->exists()) {
            $slug = "{$baseSlug}_{$counter}";
            $counter++;
        }

        $name = $label ?: Str::headline(class_basename($class));

        $menu = Menu::updateOrCreate(
            ['class_name' => $class],
            [
                'name' => $name,
                'slug' => $slug,
                'type' => $type,
                'navigation_group' => $group,
                'navigation_label' => $label ?: $name,
                'navigation_sort' => $sort,
                'is_active' => $existing ? $existing->is_active : true,
            ]
        );

        // Ensure Spatie Permission exists
        Permission::firstOrCreate([
            'name' => $menu->permission_name,
            'guard_name' => 'web',
        ]);

        return $menu;
    }

    protected function resolveNavigationLabel(string $class): ?string
    {
        if (method_exists($class, 'getNavigationLabel')) {
            $label = $class::getNavigationLabel();
            if (filled($label)) {
                return (string) $label;
            }
        }

        if (method_exists($class, 'getTitle')) {
            $title = $class::getTitle();
            if (filled($title)) {
                return (string) $title;
            }
        }

        return Str::headline(class_basename($class));
    }

    protected function resolveNavigationGroup(string $class): ?string
    {
        if (method_exists($class, 'getNavigationGroup')) {
            $group = $class::getNavigationGroup();
            if (filled($group)) {
                return is_string($group) ? $group : (string) $group->getLabel();
            }
        }

        if (method_exists($class, 'getCluster')) {
            $clusterClass = $class::getCluster();
            if ($clusterClass && method_exists($clusterClass, 'getNavigationLabel')) {
                return (string) $clusterClass::getNavigationLabel();
            }
        }

        return 'General';
    }

    protected function resolveNavigationSort(string $class): ?int
    {
        if (method_exists($class, 'getNavigationSort')) {
            return $class::getNavigationSort();
        }

        return null;
    }

    protected function shouldIgnoreClass(string $class): bool
    {
        // Don't register internal or excluded classes if any
        $ignored = [
            \Filament\Pages\Dashboard::class, // Usually handled or included, let's keep Dashboard though!
        ];

        return false;
    }

    public function clearCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Cache::forget('dynamic_menus_all');
        Cache::forget('dynamic_menus_grouped');
    }
}
