<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ClassGroups;
use App\Enums\Grades;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\SaveAssessmentRequest;
use App\Models\Assessment;
use App\Services\AssessmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AssessmentController extends Controller
{
    public function __construct(private readonly AssessmentService $assessmentService) {}

    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'class_name' => ['nullable', 'string', 'max:120', Rule::in(Grades::values())],
            'class_group' => ['nullable', 'string', Rule::in(ClassGroups::values())],
            'academic_year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            'term' => ['nullable', 'string', Rule::in($this->termOptions())],
            'exam_type' => ['nullable', 'string', Rule::in($this->examTypeOptions())],
            'status' => ['nullable', 'string', Rule::in(['published', 'draft'])],
            'sort' => ['nullable', 'string', Rule::in(['title_asc', 'title_desc', 'date_asc', 'date_desc'])],
        ]);

        $filters = array_merge([
            'search' => '',
            'class_name' => '',
            'class_group' => '',
            'academic_year' => '',
            'term' => '',
            'exam_type' => '',
            'status' => '',
            'sort' => '',
        ], $filters);

        return Inertia::render('admin/assessments/Index', [
            'assessments' => $this->assessmentService->paginated($filters),
            'filters' => $filters,
            'gradeOptions' => Grades::values(),
            ...$this->academicOptions(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/assessments/Manage', [
            'gradeOptions' => Grades::values(),
            ...$this->academicOptions(),
        ]);
    }

    public function store(SaveAssessmentRequest $request): RedirectResponse
    {
        $this->assessmentService->create($request->validated());

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment created successfully.');
    }

    public function edit(Assessment $assessment): Response
    {
        return Inertia::render('admin/assessments/Manage', [
            'assessment' => $assessment,
            'gradeOptions' => Grades::values(),
            ...$this->academicOptions(),
        ]);
    }

    public function update(SaveAssessmentRequest $request, Assessment $assessment): RedirectResponse
    {
        $this->assessmentService->update($assessment, $request->validated());

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment updated successfully.');
    }

    public function destroy(Assessment $assessment): RedirectResponse
    {
        $this->assessmentService->delete($assessment);

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment deleted successfully.');
    }

    public function publish(Assessment $assessment): RedirectResponse
    {
        $this->assessmentService->publish($assessment);

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment published successfully.');
    }

    public function unpublish(Assessment $assessment): RedirectResponse
    {
        $this->assessmentService->unpublish($assessment);

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment unpublished successfully.');
    }

    private function academicOptions(): array
    {
        return [
            'groupOptions' => ClassGroups::values(),
            'yearOptions' => range((int) now()->year + 1, 2020),
            'termOptions' => $this->termOptions(),
            'examTypeOptions' => $this->examTypeOptions(),
        ];
    }

    private function termOptions(): array
    {
        return ['Term 1', 'Term 2', 'Term 3', 'Other'];
    }

    private function examTypeOptions(): array
    {
        return ['Unit Test', 'Term Test', 'Practice Exam', 'Other'];
    }
}
