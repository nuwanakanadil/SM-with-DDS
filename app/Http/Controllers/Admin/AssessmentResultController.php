<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Grades;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssessmentResult\SaveAssessmentResultRequest;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Services\AssessmentResultService;
use App\Services\DashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AssessmentResultController extends Controller
{
    public function __construct(private readonly AssessmentResultService $resultService) {}

    public function index(Request $request): Response
    {
        $validated = $request->validate([
            'assessment_id' => ['nullable', 'integer', 'exists:assessments,id'],
        ]);

        $assessmentId = $validated['assessment_id'] ?? null;

        $assessment = $assessmentId
            ? Assessment::find($assessmentId)
            : Assessment::query()->latest()->first();

        return Inertia::render('admin/results/Index', [
            'assessments' => Assessment::latest()->get(['id', 'title', 'class_name', 'assessment_date', 'total_marks']),
            'selectedAssessment' => $assessment,
            'results' => $assessment?->results()->with('student')->orderByDesc('marks')->paginate(20),
        ]);
    }

    public function pending(Request $request): Response
    {
        $validated = $request->validate([
            'grade' => ['nullable', 'string', 'max:120', Rule::in(Grades::values())],
            'assessment_id' => ['nullable', 'integer', 'exists:assessments,id'],
        ]);

        $grade = $validated['grade'] ?? null;
        $assessmentId = $validated['assessment_id'] ?? null;

        $pendingResults = DB::table('students')
            ->join('assessments', function ($join) {
                $join
                    ->where(function ($query) {
                        $query
                            ->whereNotNull('students.class_name')
                            ->where(function ($gradeQuery) {
                                $gradeQuery
                                    ->whereColumn('assessments.class_name', 'students.class_name')
                                    ->orWhereNull('assessments.class_name');
                            });
                    })
                    ->orWhere(function ($query) {
                        $query
                            ->whereNull('students.class_name')
                            ->whereNull('assessments.class_name');
                    });
            })
            ->where('students.is_active', true)
            ->whereNull('students.deleted_at')
            ->whereNull('assessments.deleted_at')
            ->when($grade, fn ($query, string $selectedGrade) => $query->where('students.class_name', $selectedGrade))
            ->when($assessmentId, fn ($query, int $selectedAssessmentId) => $query->where('assessments.id', $selectedAssessmentId))
            ->whereNotExists(function ($query) {
                $query
                    ->selectRaw('1')
                    ->from('assessment_results')
                    ->whereColumn('assessment_results.student_id', 'students.id')
                    ->whereColumn('assessment_results.assessment_id', 'assessments.id')
                    ->whereNull('assessment_results.deleted_at');
            })
            ->select([
                'students.id as student_id',
                'students.admission_no',
                'students.first_name',
                'students.last_name',
                'students.class_name as student_class_name',
                'assessments.id as assessment_id',
                'assessments.title as assessment_title',
                'assessments.class_name as assessment_class_name',
                'assessments.assessment_date',
                'assessments.total_marks',
            ])
            ->orderBy('students.class_name')
            ->orderBy('assessments.assessment_date')
            ->orderBy('assessments.title')
            ->orderBy('students.first_name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/results/Pending', [
            'assessments' => Assessment::latest()->get(['id', 'title', 'class_name', 'assessment_date', 'total_marks']),
            'gradeOptions' => app(DashboardService::class)->availableGrades(),
            'filters' => [
                'grade' => $grade,
                'assessment_id' => $assessmentId,
            ],
            'pendingResults' => $pendingResults,
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
