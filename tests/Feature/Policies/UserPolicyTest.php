<?php

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Log;

describe('UserPolicy:viewAny', function () {
    it('allows users with readAll permission', function () {
        $owner = User::factory()->owner()->create();
        $admin = User::factory()->admin()->create();

        expect($owner->can('viewAny', User::class))->toBeTrue();
        expect($admin->can('viewAny', User::class))->toBeTrue();
    });

    it('deny users without readAll permission', function () {
        $worker = User::factory()->worker()->create();
        $customer = User::factory()->customer()->Create();

        expect($worker->can('viewAny', User::class))->toBeFalse();
        expect($customer->can('viewAny', User::class))->toBeFalse();
    });
});

describe('UserPolicy:view', function () {
    it('allows users to view themselves', function () {
        $owner = User::factory()->owner()->create();
        expect($owner->can('view', $owner))->toBeTrue();
    });

    it('allows owner to view their admins and workers', function () {
        $owner = User::factory()->owner()->create();
        $company = Company::factory()->for($owner, 'owner')->create();

        $admin = User::factory()->admin()->has(Employee::factory()->for($company, 'company'), 'employee')->create();
        $worker = User::factory()->worker()->has(Employee::factory()->for($company, 'company', 'employee'))->create();

        expect($owner->can('view', $admin))->toBeTrue();
        expect($owner->can('view', $worker))->toBeTrue();
    });

    it('deny owners to view other owners employees', function () {
        $owner1 = User::factory()->owner()->create();
        $company = Company::factory()->for($owner1, 'owner')->create();

        $owner2 = User::factory()->owner()->has(Company::factory(), 'companies')->create();

        $admin = User::factory()->admin()->has(Employee::factory()->for($company), 'employee')->create();
        $worker = User::factory()->worker()->has(Employee::factory()->for($company), 'employee')->create();

        expect($owner2->can('view', $admin))->toBeFalse();
        expect($owner2->can('view', $worker))->toBeFalse();
    });
});
