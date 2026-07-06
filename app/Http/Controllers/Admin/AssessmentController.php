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
        return Inertia::render('admin/assessments/Index', [
            'assessments' => $this->assessmentService->paginated($request->string('search')->toString()),
            'filters' => $request->only('search'),
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
}
