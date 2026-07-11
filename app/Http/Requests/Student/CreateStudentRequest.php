<?php

namespace App\Http\Requests\Student;

use App\Enums\Grades;
use App\Enums\Permissions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permissions::ManageStudents->value) ?? false;
    }

    public function rules(): array
    {
        return [
            'admission_no' => ['required', 'string', 'max:50', 'unique:students,admission_no'],
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'school' => ['nullable', 'string', 'max:190'],
            'email' => ['nullable', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'class_name' => ['nullable', 'string', 'max:120', Rule::in(Grades::values())],
            'password' => ['nullable', Password::defaults()],
            'is_active' => ['boolean'],
        ];
    }
}
