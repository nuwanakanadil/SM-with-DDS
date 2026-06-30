<?php

namespace App\Services;

use App\Enums\UserTypes;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentService
{
    public function create(array $data): Student
    {
        return DB::transaction(function () use ($data): Student {
            $student = new Student(Arr::except($data, ['password']));

            if ($user = $this->makeLinkedUser($data)) {
                $student->user()->associate($user);
            }

            $student->save();

            return $student->fresh(['user']);
        });
    }

    public function update(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data): Student {
            $student->fill(Arr::except($data, ['password']));
            $student->save();

            $this->syncLinkedUser($student, $data);

            return $student->fresh(['user']);
        });
    }

    private function syncLinkedUser(Student $student, array $data): void
    {
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if ($student->user) {
            $student->user->fill([
                'name' => $this->resolveUserName($data),
            ]);

            if (filled($email)) {
                $student->user->email = $email;
            }

            if (filled($password)) {
                $student->user->password = Hash::make($password);
            }

            if ($student->user->isDirty()) {
                $student->user->save();
            }

            return;
        }

        if (! filled($email)) {
            return;
        }

        $user = $this->makeLinkedUser($data);

        if (! $user) {
            return;
        }

        $student->user()->associate($user);
        $student->save();
    }

    private function makeLinkedUser(array $data): ?User
    {
        $email = $data['email'] ?? null;

        if (! filled($email)) {
            return null;
        }

        $user = User::create([
            'name' => $this->resolveUserName($data),
            'email' => $email,
            'password' => $data['password'] ?: $data['admission_no'],
        ]);

        if (Role::query()->where('name', UserTypes::Student->value)->exists()) {
            $user->assignRole(UserTypes::Student->value);
        }

        return $user;
    }

    private function resolveUserName(array $data): string
    {
        $name = trim(sprintf(
            '%s %s',
            $data['first_name'] ?? '',
            $data['last_name'] ?? '',
        ));

        return $name !== '' ? $name : ($data['admission_no'] ?? 'Student');
    }
}
