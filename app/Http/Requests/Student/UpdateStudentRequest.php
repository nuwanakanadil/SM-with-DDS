<?php

namespace App\Http\Requests\Student;

use App\Enums\Grades;
use App\Enums\Permissions;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permissions::ManageStudents->value) ?? false;
    }

    public function rules(): array
    {
        /** @var Student|null $student */
        $student = $this->route('student');

        return [
            'admission_no' => ['required', 'string', 'max:50', Rule::unique('students', 'admission_no')->ignore($student?->id)],
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190', Rule::unique('users', 'email')->ignore($student?->user_id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'class_name' => ['nullable', 'string', 'max:120', Rule::in(Grades::values())],
            'password' => ['nullable', Password::defaults()],
            'is_active' => ['boolean'],
        ];
    }
}
