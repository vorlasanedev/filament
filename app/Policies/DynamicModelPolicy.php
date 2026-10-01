<?php

namespace App\Policies;

use App\Services\PermissionDiscoveryService;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Str;

class DynamicModelPolicy
{
    use HandlesAuthorization;

    protected function check(AuthUser $user, string $action, ?Model $model = null, ?string $modelClass = null): bool
    {
        if ($user->hasRole('Super Admin') || $user->hasRole('super_admin')) {
            return true;
        }

        $targetClass = $model ? get_class($model) : $modelClass;
        $modelKey = PermissionDiscoveryService::getModelKey($targetClass ?: 'model');

        if ($user->can("{$modelKey}.{$action}")) {
            return true;
        }

        // Fallback for PascalCase format e.g. ViewAny:User
        $pascalModel = Str::studly($modelKey);
        $pascalAction = Str::studly($action);
        if ($user->can("{$pascalAction}:{$pascalModel}")) {
            return true;
        }

        return false;
    }

    public function viewAny(AuthUser $user, ?string $modelClass = null): bool
    {
        return $this->check($user, 'view_any', null, $modelClass);
    }

    public function view(AuthUser $user, Model $model): bool
    {
        return $this->check($user, 'view', $model);
    }

    public function create(AuthUser $user, ?string $modelClass = null): bool
    {
        return $this->check($user, 'create', null, $modelClass);
    }

    public function update(AuthUser $user, Model $model): bool
    {
        return $this->check($user, 'update', $model);
    }

    public function delete(AuthUser $user, Model $model): bool
    {
        return $this->check($user, 'delete', $model);
    }

    public function restore(AuthUser $user, Model $model): bool
    {
        return $this->check($user, 'restore', $model);
    }

    public function forceDelete(AuthUser $user, Model $model): bool
    {
        return $this->check($user, 'force_delete', $model);
    }

    public function export(AuthUser $user, ?string $modelClass = null): bool
    {
        return $this->check($user, 'export', null, $modelClass);
    }

    public function import(AuthUser $user, ?string $modelClass = null): bool
    {
        return $this->check($user, 'import', null, $modelClass);
    }
}
