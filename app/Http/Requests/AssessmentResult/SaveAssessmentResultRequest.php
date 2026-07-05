<?php

namespace App\Http\Requests\AssessmentResult;

use App\Enums\Permissions;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SaveAssessmentResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permissions::ManageResults->value) ?? false;
    }

    public function rules(): array
    {
        return [
            'assessment_id' => ['required', 'exists:assessments,id'],
            'student_id' => ['required', 'exists:students,id'],
            'marks' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $assessment = Assessment::query()->find($this->integer('assessment_id'));
            $student = Student::query()->find($this->integer('student_id'));

            if (! $assessment || ! $student) {
                return;
            }

            if (! $student->is_active) {
                $validator->errors()->add('student_id', 'Only active students can receive results.');
            }

            if ((float) $this->input('marks') > $assessment->total_marks) {
                $validator->errors()->add('marks', 'Marks cannot exceed the assessment total.');
            }

            /** @var AssessmentResult|null $result */
            $result = $this->route('result');

            /** @var AssessmentResult|null $duplicate */
            $duplicate = AssessmentResult::withTrashed()
                ->where('assessment_id', $assessment->id)
                ->where('student_id', $student->id)
                ->when($result, fn ($query) => $query->whereKeyNot($result->getKey()))
                ->first();

            if ($duplicate && ! $duplicate->trashed()) {
                $validator->errors()->add('student_id', 'This student already has a result for the selected assessment.');
            }
        });
    }
}
