<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\StockTransfer;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockTransferPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:StockTransfer');
    }

    public function view(AuthUser $authUser, StockTransfer $stockTransfer): bool
    {
        if (!$authUser->can('View:StockTransfer')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $stockTransfer->user_id === $authUser->id;
        }
        return true;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:StockTransfer');
    }

    public function update(AuthUser $authUser, StockTransfer $stockTransfer): bool
    {
        if (!$authUser->can('Update:StockTransfer')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $stockTransfer->user_id === $authUser->id;
        }
        return true;
    }

    public function delete(AuthUser $authUser, StockTransfer $stockTransfer): bool
    {
        if (!$authUser->can('Delete:StockTransfer')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $stockTransfer->user_id === $authUser->id;
        }
        return true;
    }

    public function restore(AuthUser $authUser, StockTransfer $stockTransfer): bool
    {
        if (!$authUser->can('Restore:StockTransfer')) {
            return false;
        }
        if ($authUser->hasRole('user_inventory')) {
            return $stockTransfer->user_id === $authUser->id;
        }
        return true;
    }

    public function forceDelete(AuthUser $authUser, StockTransfer $stockTransfer): bool
    {
        return $authUser->can('ForceDelete:StockTransfer');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:StockTransfer');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:StockTransfer');
    }

    public function replicate(AuthUser $authUser, StockTransfer $stockTransfer): bool
    {
        return $authUser->can('Replicate:StockTransfer');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:StockTransfer');
    }

}