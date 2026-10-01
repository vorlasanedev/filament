<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class UserProfileService
{
    public function getProfileData(User $user): array
    {
        $isSuperAdmin = $user->hasRole('Super Admin') || $user->hasRole('super_admin');
        $userPermissions = $isSuperAdmin ? [] : $user->getAllPermissions()->pluck('name')->toArray();

        return [
            'info' => $this->getUserInfo($user),
            'roles' => $this->getRolesData($user, $isSuperAdmin),
            'modules' => $this->getModulesAccess($user, $isSuperAdmin, $userPermissions),
            'menu_tree' => $this->getMenuAccessTree($user, $isSuperAdmin, $userPermissions),
            'raw_permissions' => $userPermissions,
            'is_super_admin' => $isSuperAdmin,
        ];
    }

    protected function getUserInfo(User $user): array
    {
        $lastActivity = Activity::where(function ($q) use ($user) {
            $q->where('causer_id', $user->id)
              ->orWhere('subject_id', $user->id);
        })->latest()->first();

        $lastLoginText = $lastActivity
            ? $lastActivity->created_at->diffForHumans()
            : ($user->updated_at ? $user->updated_at->diffForHumans() : 'Never');

        $username = '@' . Str::before($user->email, '@');

        return [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $username,
            'email' => $user->email,
            'phone' => $user->phone ?: 'Not provided',
            'avatar_url' => $user->getFilamentAvatarUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=' . ($user->is_active ? '10b981' : '9ca3af') . '&color=fff&bold=true',
            'is_active' => (bool) $user->is_active,
            'email_verified_at' => $user->email_verified_at?->format('M d, Y H:i'),
            'created_at' => $user->created_at?->format('M d, Y'),
            'created_ago' => $user->created_at?->diffForHumans(),
            'last_login' => $lastLoginText,
        ];
    }

    protected function getRolesData(User $user, bool $isSuperAdmin): array
    {
        $roles = $user->roles;
        $primaryRoleName = null;

        if ($isSuperAdmin) {
            $primaryRoleName = $roles->firstWhere('name', 'Super Admin') ? 'Super Admin' : ($roles->firstWhere('name', 'super_admin') ? 'super_admin' : 'super_admin');
        } elseif ($roles->firstWhere('name', 'admin')) {
            $primaryRoleName = 'admin';
        } elseif ($roles->isNotEmpty()) {
            $primaryRoleName = $roles->first()->name;
        }

        $rolesList = $roles->map(function ($role) use ($primaryRoleName) {
            $isPrimary = $role->name === $primaryRoleName;
            $description = $this->getRoleDescription($role->name);

            return [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => Str::headline($role->name),
                'is_primary' => $isPrimary,
                'description' => $description,
                'guard_name' => $role->guard_name,
                'permissions_count' => $role->permissions()->count(),
            ];
        })->toArray();

        return [
            'list' => $rolesList,
            'primary_role' => $primaryRoleName ? Str::headline($primaryRoleName) : 'Standard User',
            'has_roles' => ! empty($rolesList),
        ];
    }

    protected function getRoleDescription(string $roleName): string
    {
        return match (strtolower($roleName)) {
            'super_admin', 'super admin' => 'Unrestricted master access to all system modules, pages, and administrative functions.',
            'admin' => 'High-level administrative privilege to manage users, products, configurations, and core modules.',
            'manager' => 'Operational oversight and reporting access with permission to review and update records.',
            'editor' => 'Content and record management access with permission to create and update.',
            'employee' => 'Standard internal staff access for day-to-day module operations.',
            default => 'Custom role with permissions assigned according to enterprise access policies.',
        };
    }

    protected function getModulesAccess(User $user, bool $isSuperAdmin, array $userPermissions): array
    {
        $discovery = app(PermissionDiscoveryService::class)->getDiscoveredPermissions();
        $models = $discovery['models'];
        $modulesGrouped = [];

        foreach ($models as $modelKey => $modelData) {
            $group = 'General';
            if ($modelData['resource'] && method_exists($modelData['resource'], 'getNavigationGroup')) {
                $resGroup = $modelData['resource']::getNavigationGroup();
                if (filled($resGroup)) {
                    $group = is_string($resGroup) ? $resGroup : $resGroup->getLabel();
                }
            }

            $actions = [
                'menu' => [
                    'label' => 'Menu',
                    'granted' => $isSuperAdmin || in_array("{$modelKey}.menu", $userPermissions) || in_array("view_menu_{$modelKey}", $userPermissions),
                ],
                'view' => [
                    'label' => 'View',
                    'granted' => $isSuperAdmin || in_array("{$modelKey}.view", $userPermissions) || in_array("{$modelKey}.view_any", $userPermissions),
                ],
                'create' => [
                    'label' => 'Create',
                    'granted' => $isSuperAdmin || in_array("{$modelKey}.create", $userPermissions),
                ],
                'update' => [
                    'label' => 'Edit',
                    'granted' => $isSuperAdmin || in_array("{$modelKey}.update", $userPermissions),
                ],
                'delete' => [
                    'label' => 'Delete',
                    'granted' => $isSuperAdmin || in_array("{$modelKey}.delete", $userPermissions),
                ],
                'restore' => [
                    'label' => 'Restore',
                    'granted' => $isSuperAdmin || in_array("{$modelKey}.restore", $userPermissions),
                ],
                'force_delete' => [
                    'label' => 'Force Delete',
                    'granted' => $isSuperAdmin || in_array("{$modelKey}.force_delete", $userPermissions),
                ],
                'export' => [
                    'label' => 'Export',
                    'granted' => $isSuperAdmin || in_array("{$modelKey}.export", $userPermissions),
                ],
                'import' => [
                    'label' => 'Import',
                    'granted' => $isSuperAdmin || in_array("{$modelKey}.import", $userPermissions),
                ],
                'approve' => [
                    'label' => 'Approve',
                    'granted' => $isSuperAdmin || in_array("{$modelKey}.approve", $userPermissions) || in_array("approve_{$modelKey}", $userPermissions),
                ],
            ];

            $grantedCount = count(array_filter($actions, fn ($a) => $a['granted']));

            $modulesGrouped[$group][] = [
                'key' => $modelKey,
                'name' => $modelData['name'],
                'label' => $modelData['label'],
                'actions' => $actions,
                'granted_count' => $grantedCount,
                'total_actions' => count($actions),
                'has_access' => $grantedCount > 0,
            ];
        }

        return $modulesGrouped;
    }

    protected function getMenuAccessTree(User $user, bool $isSuperAdmin, array $userPermissions): array
    {
        $menus = Menu::where('is_active', true)->orderBy('navigation_sort')->get();
        $tree = [];

        foreach ($menus as $menu) {
            $group = $menu->navigation_group ?: 'General';
            $isAccessible = $isSuperAdmin ||
                in_array($menu->permission_name, $userPermissions) ||
                in_array("{$menu->slug}.menu", $userPermissions) ||
                in_array("{$menu->slug}.view_any", $userPermissions);

            $tree[$group][] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'label' => $menu->navigation_label ?: $menu->name,
                'slug' => $menu->slug,
                'type' => $menu->type,
                'permission' => $menu->permission_name,
                'is_accessible' => $isAccessible,
            ];
        }

        return $tree;
    }
}
