<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService
{
    /** @return array{student: Student, warning: string|null} */
    public function create(array $data): array
    {
        $data = $this->prepareAccessCode($data);
        $student = DB::transaction(function () use ($data): Student {
            return Student::query()->create($data);
        });

        return [
            'student' => $student,
            'warning' => null,
        ];
    }

    /** @return array{student: Student, warning: string|null} */
    public function update(Student $student, array $data): array
    {
        $data = $this->prepareAccessCode($data);
        $updatedStudent = DB::transaction(function () use ($student, $data): Student {
            $student->fill($data);
            $student->save();

            return $student->fresh();
        });

        return [
            'student' => $updatedStudent,
            'warning' => null,
        ];
    }

    private function prepareAccessCode(array $data): array
    {
        $accessCode = $data['result_access_code'] ?? null;
        unset($data['result_access_code']);

        if ($accessCode !== null && $accessCode !== '') {
            $data['result_access_code_hash'] = Hash::make($accessCode);
        }

        return $data;
    }
}
