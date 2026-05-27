<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Employee;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Employee');
    }

    public function view(AuthUser $authUser, Employee $employee): bool
    {
        if (!$authUser->can('View:Employee')) {
            return false;
        }
        if ($authUser->hasRole('user_employee')) {
            return $employee->user_id === $authUser->id;
        }
        return true;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Employee');
    }

    public function update(AuthUser $authUser, Employee $employee): bool
    {
        if (!$authUser->can('Update:Employee')) {
            return false;
        }
        if ($authUser->hasRole('user_employee')) {
            return $employee->user_id === $authUser->id;
        }
        return true;
    }

    public function delete(AuthUser $authUser, Employee $employee): bool
    {
        if (!$authUser->can('Delete:Employee')) {
            return false;
        }
        if ($authUser->hasRole('user_employee')) {
            return $employee->user_id === $authUser->id;
        }
        return true;
    }

    public function restore(AuthUser $authUser, Employee $employee): bool
    {
        if (!$authUser->can('Restore:Employee')) {
            return false;
        }
        if ($authUser->hasRole('user_employee')) {
            return $employee->user_id === $authUser->id;
        }
        return true;
    }

    public function forceDelete(AuthUser $authUser, Employee $employee): bool
    {
        return $authUser->can('ForceDelete:Employee');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Employee');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Employee');
    }

    public function replicate(AuthUser $authUser, Employee $employee): bool
    {
        return $authUser->can('Replicate:Employee');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Employee');
    }

}