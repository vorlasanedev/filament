<?php

namespace App\Services;

use Filament\Clusters\Cluster;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionDiscoveryService
{
    public const MODEL_ACTIONS = [
        'menu' => 'Menu',
        'view_any' => 'View Any',
        'view' => 'View',
        'create' => 'Create',
        'update' => 'Edit',
        'delete' => 'Delete',
        'restore' => 'Restore',
        'force_delete' => 'Force Delete',
        'export' => 'Export',
        'import' => 'Import',
    ];

    public const PAGE_ACTIONS = [
        'menu' => 'Menu',
        'view' => 'View',
    ];

    public const CLUSTER_ACTIONS = [
        'menu' => 'Menu',
    ];

    public const WIDGET_ACTIONS = [
        'view' => 'View',
    ];

    /**
     * Synchronize all discovered permissions with Spatie permissions table.
     */
    public function sync(?string $panelId = 'admin'): array
    {
        if ($panelId && isset(Filament::getPanels()[$panelId])) {
            Filament::setCurrentPanel(Filament::getPanel($panelId));
        }

        $discovered = $this->getDiscoveredPermissions();
        $created = 0;
        $existing = 0;

        foreach ($discovered['all_permission_names'] as $permName) {
            $perm = Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'web',
            ]);

            if ($perm->wasRecentlyCreated) {
                $created++;
            } else {
                $existing++;
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return [
            'created' => $created,
            'existing' => $existing,
            'total' => count($discovered['all_permission_names']),
            'models' => $discovered['models'],
            'pages' => $discovered['pages'],
            'clusters' => $discovered['clusters'],
            'widgets' => $discovered['widgets'],
        ];
    }

    /**
     * Get all structured components, models, and their respective permissions for the matrix UI.
     */
    public function getDiscoveredPermissions(): array
    {
        $models = [];
        $pages = [];
        $clusters = [];
        $widgets = [];
        $allPermissions = [];

        // 1. Discover Models from Filament Resources
        foreach (Filament::getResources() as $resourceClass) {
            $modelClass = $resourceClass::getModel();
            if (! $modelClass || ! class_exists($modelClass)) {
                continue;
            }

            $modelKey = static::getModelKey($modelClass);
            $label = $resourceClass::getNavigationLabel() ?: Str::headline(class_basename($modelClass));

            if (! isset($models[$modelKey])) {
                $modelPerms = [];
                foreach (array_keys(self::MODEL_ACTIONS) as $action) {
                    $permName = "{$modelKey}.{$action}";
                    $modelPerms[$action] = $permName;
                    $allPermissions[] = $permName;
                }

                $models[$modelKey] = [
                    'key' => $modelKey,
                    'name' => class_basename($modelClass),
                    'label' => $label,
                    'class' => $modelClass,
                    'resource' => $resourceClass,
                    'permissions' => $modelPerms,
                ];
            }
        }

        // 2. Discover Standalone Eloquent Models in app/Models
        $modelFiles = File::glob(app_path('Models/*.php'));
        foreach ($modelFiles as $file) {
            $class = 'App\\Models\\' . basename($file, '.php');
            if (class_exists($class) && is_subclass_of($class, \Illuminate\Database\Eloquent\Model::class)) {
                $modelKey = static::getModelKey($class);
                if (! isset($models[$modelKey])) {
                    $modelPerms = [];
                    foreach (array_keys(self::MODEL_ACTIONS) as $action) {
                        $permName = "{$modelKey}.{$action}";
                        $modelPerms[$action] = $permName;
                        $allPermissions[] = $permName;
                    }

                    $models[$modelKey] = [
                        'key' => $modelKey,
                        'name' => class_basename($class),
                        'label' => Str::headline(class_basename($class)),
                        'class' => $class,
                        'resource' => null,
                        'permissions' => $modelPerms,
                    ];
                }
            }
        }

        // 3. Discover Pages & Clusters
        foreach (Filament::getPages() as $pageClass) {
            if (is_subclass_of($pageClass, Cluster::class)) {
                $clusterKey = 'cluster.' . Str::snake(preg_replace('/Cluster$/', '', class_basename($pageClass)));
                $permName = "{$clusterKey}.menu";
                $allPermissions[] = $permName;

                $clusters[$clusterKey] = [
                    'key' => $clusterKey,
                    'name' => class_basename($pageClass),
                    'label' => method_exists($pageClass, 'getNavigationLabel') ? $pageClass::getNavigationLabel() : Str::headline(class_basename($pageClass)),
                    'permissions' => [
                        'menu' => $permName,
                    ],
                ];
            } else {
                $pageKey = 'page.' . Str::snake(preg_replace('/Page$/', '', class_basename($pageClass)));
                $pagePerms = [];
                foreach (array_keys(self::PAGE_ACTIONS) as $action) {
                    $permName = "{$pageKey}.{$action}";
                    $pagePerms[$action] = $permName;
                    $allPermissions[] = $permName;
                }

                $pages[$pageKey] = [
                    'key' => $pageKey,
                    'name' => class_basename($pageClass),
                    'label' => method_exists($pageClass, 'getNavigationLabel') ? $pageClass::getNavigationLabel() : Str::headline(class_basename($pageClass)),
                    'permissions' => $pagePerms,
                ];
            }
        }

        // 4. Discover Widgets
        foreach (Filament::getWidgets() as $widgetClass) {
            $widgetKey = 'widget.' . Str::snake(class_basename($widgetClass));
            $permName = "{$widgetKey}.view";
            $allPermissions[] = $permName;

            $widgets[$widgetKey] = [
                'key' => $widgetKey,
                'name' => class_basename($widgetClass),
                'label' => Str::headline(class_basename($widgetClass)),
                'permissions' => [
                    'view' => $permName,
                ],
            ];
        }

        // Sort models by label
        uasort($models, fn ($a, $b) => strcmp($a['label'], $b['label']));

        return [
            'models' => $models,
            'pages' => $pages,
            'clusters' => $clusters,
            'widgets' => $widgets,
            'all_permission_names' => array_values(array_unique($allPermissions)),
        ];
    }

    /**
     * Uniform model key derivation (e.g. App\Models\User -> 'user').
     */
    public static function getModelKey(string $modelClass): string
    {
        return Str::snake(class_basename($modelClass));
    }
}
