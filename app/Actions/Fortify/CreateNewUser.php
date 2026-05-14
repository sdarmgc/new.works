<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

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
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),

            'gender' => ['nullable', 'in:0,1,2'],

            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],

            'languages' => ['nullable', 'array'],
            'languages.*' => ['exists:languages,id'],
            'countries' => ['nullable', 'array'],
            'countries.*' => ['exists:countries,id'],

            'roles' => ['nullable'],
            'other_role' => ['nullable', 'string', 'max:255'],

            'g-recaptcha-response' => ['required', 'captcha'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $extra = "Responsibility : ";
        if (!empty($input['roles'])) {
            $extra .= implode(', ', $input['roles']);
            if (in_array('Other', $input['roles']) && strlen($input['roles_other']) > 1) {
                $extra .= ' - ' . $input['roles_other'];
            }
        }

        // Create user
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // Save profile
        $user->profile()->create([
            'gender' => $input['gender'] ?? null,
            'first_name' => $input['first_name'] ?? null,
            'last_name' => $input['last_name'] ?? null,
            'phone' => $input['phone'] ?? null,
            'extra' => $extra,
        ]);

        // Save languages (pivot table)
        $user->languages()->sync($input['languages'] ?? []);

        // Save countries (pivot table)
        $user->countries()->sync($input['countries'] ?? []);

        return $user;
    }
}
