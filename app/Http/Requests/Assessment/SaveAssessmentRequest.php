<?php

namespace App\Http\Requests\Assessment;

use App\Enums\Grades;
use App\Enums\Permissions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permissions::ManageAssessments->value) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:190'],
            'class_name' => ['nullable', 'string', 'max:120', Rule::in(Grades::values())],
            'assessment_date' => ['nullable', 'date'],
            'total_marks' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_published' => ['boolean'],
        ];
    }
}
