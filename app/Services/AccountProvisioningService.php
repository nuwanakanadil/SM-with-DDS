<?php

namespace App\Services;

use App\Enums\UserTypes;
use App\Mail\AccountCreatedMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
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
            'must_change_password' => in_array($role, [UserTypes::Staff, UserTypes::Student], true),
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

    public function sendAccountCreatedMailSafely(User $user, string $plainPassword, UserTypes $role): ?string
    {
        try {
            $this->sendAccountCreatedMail($user, $plainPassword, $role);

            return null;
        } catch (\Throwable $exception) {
            Log::error('Failed to send account email.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $role->value,
                'exception' => $exception,
            ]);

            return 'The account was created, but sending login details by email failed.';
        }
    }

    public function syncPassword(User $user, ?string $plainPassword): void
    {
        if (! filled($plainPassword)) {
            return;
        }

        $user->password = Hash::make($plainPassword);
        $user->must_change_password = false;
    }

    public function issueTemporaryPassword(User $user, UserTypes $role): ?string
    {
        $temporaryPassword = $this->generatePassword();

        $user->forceFill([
            'password' => Hash::make($temporaryPassword),
            'must_change_password' => true,
        ])->save();

        return $this->sendAccountCreatedMailSafely($user, $temporaryPassword, $role);
    }

    public function generatePassword(int $length = 12): string
    {
        return Str::random($length);
    }
}
