<?php

namespace App\Http\Requests\Assessment;

use App\Enums\ClassGroups;
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
            'class_group' => ['nullable', 'string', Rule::in(ClassGroups::values())],
            'academic_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'term' => ['required', 'string', Rule::in(['Term 1', 'Term 2', 'Term 3', 'Other'])],
            'exam_type' => ['required', 'string', Rule::in(['Unit Test', 'Term Test', 'Practice Exam', 'Other'])],
            'assessment_date' => ['nullable', 'date'],
            'total_marks' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_published' => ['boolean'],
        ];
    }
}
