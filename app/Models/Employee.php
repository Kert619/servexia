<?php

namespace App\Models;

use App\Policies\EmployeePolicy;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table(name: 'employees', key: 'employee_id')]
#[Fillable(['user_id', 'company_id', 'employee_hire_date', 'employee_comission_rate', 'employee_status'])]
#[UsePolicy(EmployeePolicy::class)]
#[UseFactory(EmployeeFactory::class)]
class Employee extends Model
{
    use SoftDeletes;
    use HasFactory;

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function user(): BelongsTo
    {
        return  $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
