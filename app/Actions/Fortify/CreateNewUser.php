<?php

namespace App\Actions\Fortify;

use App\Models\User;
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
     */
    public function create(array $input): User
    {
        // 1. Validate input
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        // 2. Check if any user with given email exists
        /** @var \App\Models\User|null $user */
        $user = User::where('email', $input['email'])->first();
        if (! is_null($user) && $user->state === 'REGISTERED') {
            throw ValidationException::withMessages([
                'email' => ['Felaktig e-postadress'],
            ]);
        }

        if (! is_null($user) && $user->state === 'INVITED') {
            $user->update([
                'name' => $input['name'],
                'password' => Hash::make($input['password']),
                'state' => 'REGISTERED',
            ]);

            return $user;
        } elseif (is_null($user)) {
            return User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);
        }

    }
}
