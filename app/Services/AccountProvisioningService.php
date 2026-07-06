<?php

namespace App\Services;

use App\Enums\UserTypes;
use App\Mail\AccountCreatedMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AccountProvisioningService
{
    public function create(array $attributes, UserTypes $role, ?string $plainPassword = null): array
    {
        $resolvedPassword = filled($plainPassword) ? $plainPassword : $this->generatePassword();

        $user = User::query()->create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($resolvedPassword),
            'email_verified_at' => now(),
        ]);

        Role::findOrCreate($role->value, 'web');
        $user->assignRole($role->value);

        return [
            'user' => $user,
            'plain_password' => $resolvedPassword,
        ];
    }

    public function sendAccountCreatedMail(User $user, string $plainPassword, UserTypes $role): void
    {
        Mail::to($user->email)->send(new AccountCreatedMail($user, $plainPassword, $role));
    }

    public function syncPassword(User $user, ?string $plainPassword): void
    {
        if (! filled($plainPassword)) {
            return;
        }

        $user->password = Hash::make($plainPassword);
    }

    public function generatePassword(int $length = 12): string
    {
        return Str::random($length);
    }
}
