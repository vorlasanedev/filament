<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProductCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProductCategory');
    }

    public function view(AuthUser $authUser, ProductCategory $productCategory): bool
    {
        if (!$authUser->can('View:ProductCategory')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $productCategory->user_id === $authUser->id;
        }
        return true;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProductCategory');
    }

    public function update(AuthUser $authUser, ProductCategory $productCategory): bool
    {
        if (!$authUser->can('Update:ProductCategory')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $productCategory->user_id === $authUser->id;
        }
        return true;
    }

    public function delete(AuthUser $authUser, ProductCategory $productCategory): bool
    {
        if (!$authUser->can('Delete:ProductCategory')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $productCategory->user_id === $authUser->id;
        }
        return true;
    }

    public function restore(AuthUser $authUser, ProductCategory $productCategory): bool
    {
        if (!$authUser->can('Restore:ProductCategory')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $productCategory->user_id === $authUser->id;
        }
        return true;
    }

    public function forceDelete(AuthUser $authUser, ProductCategory $productCategory): bool
    {
        return $authUser->can('ForceDelete:ProductCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProductCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProductCategory');
    }

    public function replicate(AuthUser $authUser, ProductCategory $productCategory): bool
    {
        return $authUser->can('Replicate:ProductCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProductCategory');
    }

}