<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentService
{
    /** @return array{student: Student, warning: string|null} */
    public function create(array $data): array
    {
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
}
