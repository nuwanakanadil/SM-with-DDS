<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssessmentResult\SaveAssessmentResultRequest;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Services\AssessmentResultService;
use App\Services\RankingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssessmentResultController extends Controller
{
    public function __construct(private readonly AssessmentResultService $resultService, private readonly RankingService $rankingService) {}

    public function index(Request $request): Response
    {
        $assessment = $request->assessment_id
            ? Assessment::find($request->assessment_id)
            : Assessment::query()->latest()->first();

        return Inertia::render('admin/results/Index', [
            'assessments' => Assessment::latest()->get(['id', 'title', 'class_name', 'assessment_date', 'total_marks']),
            'selectedAssessment' => $assessment,
            'results' => $assessment?->results()->with('student')->orderByDesc('marks')->paginate(20),
            'summary' => $assessment ? $this->rankingService->summaryForAssessment($assessment) : null,
            'ranks' => $assessment ? $this->rankingService->ranksForAssessment($assessment) : [],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/results/Manage', [
            'assessments' => Assessment::latest()->get(['id', 'title', 'class_name', 'total_marks']),
            'students' => Student::where('is_active', true)->orderBy('first_name')->get(['id', 'admission_no', 'first_name', 'last_name', 'class_name']),
        ]);
    }

    public function store(SaveAssessmentResultRequest $request): RedirectResponse
    {
        $this->resultService->saveResult($request->validated());

        return redirect()->route('admin.results.index', ['assessment_id' => $request->assessment_id])->with('success', 'Result saved successfully.');
    }

    public function edit(AssessmentResult $result): Response
    {
        return Inertia::render('admin/results/Manage', [
            'result' => $result->load('student', 'assessment'),
            'assessments' => Assessment::latest()->get(['id', 'title', 'class_name', 'total_marks']),
            'students' => Student::where('is_active', true)->orderBy('first_name')->get(['id', 'admission_no', 'first_name', 'last_name', 'class_name']),
        ]);
    }

    public function update(SaveAssessmentResultRequest $request, AssessmentResult $result): RedirectResponse
    {
        $this->resultService->updateResult($result, $request->validated());

        return redirect()->route('admin.results.index', ['assessment_id' => $request->assessment_id])->with('success', 'Result updated successfully.');
    }

    public function destroy(AssessmentResult $result): RedirectResponse
    {
        $assessmentId = $result->assessment_id;
        $result->delete();

        return redirect()->route('admin.results.index', ['assessment_id' => $assessmentId])->with('success', 'Result deleted successfully.');
    }
}
