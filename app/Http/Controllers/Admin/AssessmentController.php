<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Grades;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\SaveAssessmentRequest;
use App\Models\Assessment;
use App\Services\AssessmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssessmentController extends Controller
{
    public function __construct(private readonly AssessmentService $assessmentService) {}

    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'class_name' => ['nullable', 'string', 'max:120', \Illuminate\Validation\Rule::in(Grades::values())],
            'status' => ['nullable', 'string', \Illuminate\Validation\Rule::in(['published', 'draft'])],
            'sort' => ['nullable', 'string', \Illuminate\Validation\Rule::in(['title_asc', 'title_desc', 'date_asc', 'date_desc'])],
        ]);

        $filters = array_merge([
            'search' => '',
            'class_name' => '',
            'status' => '',
            'sort' => '',
        ], $filters);

        return Inertia::render('admin/assessments/Index', [
            'assessments' => $this->assessmentService->paginated($filters),
            'filters' => $filters,
            'gradeOptions' => Grades::values(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/assessments/Manage', [
            'gradeOptions' => Grades::values(),
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
}
