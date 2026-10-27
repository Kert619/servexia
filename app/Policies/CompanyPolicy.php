<?php

namespace App\Policies;

use App\Enums\Table;
use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    protected string $resource = Table::COMPANIES->value;

    public function viewAny(User $user): bool
    {
        return $user->can("{$this->resource}.readAll");
    }

    public function view(User $user, Company $company): bool
    {
        return $user->can("{$this->resource}.read") && $this->belongsToUser($user, $company);
    }

    public function create(User $user): bool
    {
        return $user->can("{$this->resource}.create");
    }

    public function update(User $user, Company $company): bool
    {
        return $user->can("{$this->resource}.update") && $this->belongsToUser($user, $company);
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->can("{$this->resource}.delete") && $this->belongsToUser($user, $company);
    }

    private function belongsToUser(User $user, Company $company): bool
    {
        return $user->user_id === $company->company_owner_id;
    }
}
