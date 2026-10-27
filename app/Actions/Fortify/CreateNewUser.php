<?php

namespace App\Actions\Fortify;

use App\Enums\Role;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'role' => ['required', Rule::in([Role::OWNER->value, Role::CUSTOMER->value])]
        ])->validate();

        DB::beginTransaction();

        try {
            $user = User::create([
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            $user->assignRole($input['role']);

            //save user info
            UserInfo::create([
                'user_id' => $user->user_id,
                'user_info_first_name' => $input['first_name'],
                'user_info_last_name' => $input['last_name']
            ]);

            DB::commit();

            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
