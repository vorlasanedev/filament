<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Location;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Location');
    }

    public function view(AuthUser $authUser, Location $location): bool
    {
        if (!$authUser->can('View:Location')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $location->user_id === $authUser->id;
        }
        return true;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Location');
    }

    public function update(AuthUser $authUser, Location $location): bool
    {
        if (!$authUser->can('Update:Location')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $location->user_id === $authUser->id;
        }
        return true;
    }

    public function delete(AuthUser $authUser, Location $location): bool
    {
        if (!$authUser->can('Delete:Location')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $location->user_id === $authUser->id;
        }
        return true;
    }

    public function restore(AuthUser $authUser, Location $location): bool
    {
        if (!$authUser->can('Restore:Location')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $location->user_id === $authUser->id;
        }
        return true;
    }

    public function forceDelete(AuthUser $authUser, Location $location): bool
    {
        return $authUser->can('ForceDelete:Location');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Location');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Location');
    }

    public function replicate(AuthUser $authUser, Location $location): bool
    {
        return $authUser->can('Replicate:Location');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Location');
    }

}