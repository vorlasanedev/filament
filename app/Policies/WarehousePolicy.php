<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Warehouse;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehousePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Warehouse');
    }

    public function view(AuthUser $authUser, Warehouse $warehouse): bool
    {
        if (!$authUser->can('View:Warehouse')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $warehouse->user_id === $authUser->id;
        }
        return true;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Warehouse');
    }

    public function update(AuthUser $authUser, Warehouse $warehouse): bool
    {
        if (!$authUser->can('Update:Warehouse')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $warehouse->user_id === $authUser->id;
        }
        return true;
    }

    public function delete(AuthUser $authUser, Warehouse $warehouse): bool
    {
        if (!$authUser->can('Delete:Warehouse')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $warehouse->user_id === $authUser->id;
        }
        return true;
    }

    public function restore(AuthUser $authUser, Warehouse $warehouse): bool
    {
        if (!$authUser->can('Restore:Warehouse')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $warehouse->user_id === $authUser->id;
        }
        return true;
    }

    public function forceDelete(AuthUser $authUser, Warehouse $warehouse): bool
    {
        return $authUser->can('ForceDelete:Warehouse');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Warehouse');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Warehouse');
    }

    public function replicate(AuthUser $authUser, Warehouse $warehouse): bool
    {
        return $authUser->can('Replicate:Warehouse');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Warehouse');
    }

}