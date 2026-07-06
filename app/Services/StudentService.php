<?php

namespace App\Services;

use App\Enums\UserTypes;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function __construct(
        private readonly AccountProvisioningService $accountProvisioningService,
    ) {}

    public function create(array $data): Student
    {
        $createdAccount = null;

        $student = DB::transaction(function () use ($data, &$createdAccount): Student {
            $student = new Student(Arr::except($data, ['password']));

            if ($user = $this->makeLinkedUser($data, $createdAccount)) {
                $student->user()->associate($user);
            }

            $student->save();

            return $student->fresh(['user']);
        });

        if (is_array($createdAccount)) {
            $this->accountProvisioningService->sendAccountCreatedMail(
                $createdAccount['user'],
                $createdAccount['plain_password'],
                UserTypes::Student,
            );
        }

        return $student;
    }

    public function update(Student $student, array $data): Student
    {
        $createdAccount = null;

        $updatedStudent = DB::transaction(function () use ($student, $data, &$createdAccount): Student {
            $student->fill(Arr::except($data, ['password']));
            $student->save();

            $this->syncLinkedUser($student, $data, $createdAccount);

            return $student->fresh(['user']);
        });

        if (is_array($createdAccount)) {
            $this->accountProvisioningService->sendAccountCreatedMail(
                $createdAccount['user'],
                $createdAccount['plain_password'],
                UserTypes::Student,
            );
        }

        return $updatedStudent;
    }

    private function syncLinkedUser(Student $student, array $data, ?array &$createdAccount = null): void
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
                $this->accountProvisioningService->syncPassword($student->user, $password);
            }

            if ($student->user->isDirty()) {
                $student->user->save();
            }

            return;
        }

        if (! filled($email)) {
            return;
        }

        $user = $this->makeLinkedUser($data, $createdAccount);

        if (! $user) {
            return;
        }

        $student->user()->associate($user);
        $student->save();
    }

    private function makeLinkedUser(array $data, ?array &$createdAccount = null): ?User
    {
        $email = $data['email'] ?? null;

        if (! filled($email)) {
            return null;
        }

        $temporaryPassword = filled($data['password'] ?? null)
            ? $data['password']
            : null;

        $createdAccount = $this->accountProvisioningService->create([
            'name' => $this->resolveUserName($data),
            'email' => $email,
        ], UserTypes::Student, $temporaryPassword);

        return $createdAccount['user'];
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
