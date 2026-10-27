<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Role;
use App\Policies\UserPolicy;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Table(name: 'users', key: 'user_id')]
#[Fillable(['email', 'password'])]
#[Hidden(['password', 'remember_token'])]
#[UsePolicy(UserPolicy::class)]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //for admin
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'company_owner_id', 'user_id');
    }

    //for employee
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id', 'user_id');
    }

    public function UserInfo(): HasOne
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'user_id');
    }

    #[Scope]
    public function belongingToUser(Builder $query, User $owner)
    {
        return $query->whereHas('employee', function ($q) use ($owner) {
            $q->whereHas('company', fn($q2) => $q2->where('company_owner_id', $owner->user_id));
        });
    }

    public function isOwner(): bool
    {
        return $this->hasRole(Role::OWNER->value);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN->value);
    }

    public function isWorker(): bool
    {
        return $this->hasRole(Role::WORKER->value);
    }

    public function isCustomer(): bool
    {
        return $this->hasRole(Role::CUSTOMER->value);
    }

    public function isAdminOrWorker(): bool
    {
        return $this->hasAnyRole([
            Role::ADMIN->value,
            Role::WORKER->value,
        ]);
    }
}
