<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Product');
    }

    public function view(AuthUser $authUser, Product $product): bool
    {
        if (!$authUser->can('View:Product')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $product->user_id === $authUser->id;
        }
        return true;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Product');
    }

    public function update(AuthUser $authUser, Product $product): bool
    {
        if (!$authUser->can('Update:Product')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $product->user_id === $authUser->id;
        }
        return true;
    }

    public function delete(AuthUser $authUser, Product $product): bool
    {
        if (!$authUser->can('Delete:Product')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $product->user_id === $authUser->id;
        }
        return true;
    }

    public function restore(AuthUser $authUser, Product $product): bool
    {
        if (!$authUser->can('Restore:Product')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $product->user_id === $authUser->id;
        }
        return true;
    }

    public function forceDelete(AuthUser $authUser, Product $product): bool
    {
        return $authUser->can('ForceDelete:Product');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Product');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Product');
    }

    public function replicate(AuthUser $authUser, Product $product): bool
    {
        return $authUser->can('Replicate:Product');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Product');
    }

}