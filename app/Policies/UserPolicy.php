<?php

namespace App\Policies;

use App\Enums\Role;
use App\Enums\Table;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    protected string $resource = Table::USERS->value;

    /**
     * Can the user view the user list?
     */
    public function viewAny(User $user): bool
    {
        return $user->can("{$this->resource}.readAll");
    }

    /**
     * Can the authenticated user view the target user?
     */
    public function view(User $user, User $model): bool
    {
        // Everyone can view themselves.
        if ($this->isSelf($user, $model)) {
            return $user->can("{$this->resource}.read");
        }

        // Owner can view their admins and workers.
        if ($user->isOwner()) {
            return $user->can("{$this->resource}.read")
                && $model->isAdminOrWorker()
                && $this->belongsToSameOwner($user, $model);
        }

        // Admin can view their workers.
        if ($user->isAdmin()) {
            return $user->can("{$this->resource}.read")
                && $model->isWorker()
                && $this->belongsToSameOwner($user, $model);
        }

        // Workers/customers can only view themselves.
        return false;
    }

    /**
     * Can a user create an account with the given role?
     */
    public function create(?User $user, Role $role): bool
    {
        // Super admin can never be created through this policy.
        if ($role === Role::SUPER_ADMIN) {
            return false;
        }

        // Not logged in:
        // only Owner or Customer accounts can be registered.
        if (!$user) {
            return in_array($role, [
                Role::OWNER,
                Role::CUSTOMER,
            ], true);
        }

        // Owner can create workers and admins.
        if ($user->isOwner()) {
            return $user->can("{$this->resource}.create")
                && in_array($role, [
                    Role::ADMIN,
                    Role::WORKER,
                ], true);
        }

        // Admin can create workers.
        if ($user->isAdmin()) {
            return $user->can("{$this->resource}.create")
                && $role === Role::WORKER;
        }

        // Workers/customers cannot create accounts.
        return false;
    }

    /**
     * Can the authenticated user update the target user?
     */
    public function update(User $user, User $model): bool
    {
        // Everyone can update themselves,
        // assuming they have the update permission.
        if ($this->isSelf($user, $model)) {
            return $user->can("{$this->resource}.update");
        }

        // Owner can update admins and workers.
        if ($user->isOwner()) {
            return $user->can("{$this->resource}.update")
                && $model->isAdminOrWorker()
                && $this->belongsToSameOwner($user, $model);
        }

        // Admin can update workers.
        if ($user->isAdmin()) {
            return $user->can("{$this->resource}.update")
                && $model->isWorker()
                && $this->belongsToSameOwner($user, $model);
        }

        // Worker/customer can only update themselves.
        return false;
    }

    /**
     * Can the authenticated user delete the target user?
     */
    public function delete(User $user, User $model): bool
    {
        // Nobody can delete themselves.
        if ($this->isSelf($user, $model)) {
            return false;
        }

        // Owner can delete admins and workers.
        if ($user->isOwner()) {
            return $user->can("{$this->resource}.delete")
                && $model->isAdminOrWorker()
                && $this->belongsToSameOwner($user, $model);
        }

        // Admin can delete workers.
        if ($user->isAdmin()) {
            return $user->can("{$this->resource}.delete")
                && $model->isWorker()
                && $this->belongsToSameOwner($user, $model);
        }

        // Workers/customers cannot delete users.
        return false;
    }

    private function isSelf(User $user, User $model): bool
    {
        return $user->user_id === $model->user_id;
    }

    private function belongsToSameOwner(User $user, User $model): bool
    {
        if ($user->hasRole(Role::OWNER->value)) {
            return $model->employee?->company?->company_owner_id === $user->user_id;
        } else if ($user->hasRole(Role::ADMIN->value)) {
            return $model->employee?->company?->company_owner_id === $user->employee?->company?->company_owner_id;
        }

        return false;
    }
}
