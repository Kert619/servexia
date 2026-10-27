<?php

namespace App\Models;

use App\Policies\CompanyPolicy;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table(name: 'companies', key: 'company_id')]
#[Fillable(['company_name', 'company_description', 'company_email', 'company_phone', 'company_owner_id'])]
#[UsePolicy(CompanyPolicy::class)]
#[UseFactory(CompanyFactory::class)]
class Company extends Model
{
    use SoftDeletes;
    use HasFactory;

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_owner_id', 'user_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'company_id', 'company_id');
    }
}
