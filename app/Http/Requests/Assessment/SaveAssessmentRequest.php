<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class SaveAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage_assessments') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:190'],
            'class_name' => ['nullable', 'string', 'max:120'],
            'assessment_date' => ['nullable', 'date'],
            'total_marks' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_published' => ['boolean'],
        ];
    }
}
