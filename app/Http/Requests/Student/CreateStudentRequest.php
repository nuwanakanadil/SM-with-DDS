<?php

namespace App\Http\Requests\Student;

use App\Enums\Permissions;
use Illuminate\Foundation\Http\FormRequest;
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
            'email' => ['nullable', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'class_name' => ['nullable', 'string', 'max:120'],
            'password' => ['nullable', Password::defaults()],
            'is_active' => ['boolean'],
        ];
    }
}
