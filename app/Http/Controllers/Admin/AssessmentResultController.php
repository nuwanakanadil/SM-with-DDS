<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ClassGroups;
use App\Enums\Grades;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssessmentResult\SaveAssessmentResultRequest;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Services\AssessmentResultService;
use App\Services\DashboardService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'class_group' => ['nullable', 'string', Rule::in(ClassGroups::values())],
            'assessment_id' => ['nullable', 'integer', 'exists:assessments,id'],
        ]);

        $grade = $validated['grade'] ?? null;
        $classGroup = $validated['class_group'] ?? null;
        $assessmentId = $validated['assessment_id'] ?? null;

        $pendingResults = DB::table('students')
            ->join('assessments', function ($join) {
                $join->where(function ($gradeQuery) {
                    $gradeQuery
                        ->whereColumn('assessments.class_name', 'students.class_name')
                        ->orWhereNull('assessments.class_name');
                })->where(function ($groupQuery) {
                    $groupQuery
                        ->whereColumn('assessments.class_group', 'students.class_group')
                        ->orWhereNull('assessments.class_group');
                });
            })
            ->where('students.is_active', true)
            ->whereIn('students.class_group', ClassGroups::values())
            ->whereNull('students.deleted_at')
            ->whereNull('assessments.deleted_at')
            ->when($grade, fn ($query, string $selectedGrade) => $query->where('students.class_name', $selectedGrade))
            ->when($classGroup, fn ($query, string $selectedGroup) => $query->where('students.class_group', $selectedGroup))
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
                'students.class_group as student_class_group',
                'assessments.id as assessment_id',
                'assessments.title as assessment_title',
                'assessments.class_name as assessment_class_name',
                'assessments.class_group as assessment_class_group',
                'assessments.assessment_date',
                'assessments.total_marks',
            ])
            ->orderBy('students.class_name')
            ->orderBy('students.class_group')
            ->orderBy('assessments.assessment_date')
            ->orderBy('assessments.title')
            ->orderBy('students.first_name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/results/Pending', [
            'assessments' => Assessment::latest()->get(['id', 'title', 'class_name', 'assessment_date', 'total_marks']),
            'gradeOptions' => app(DashboardService::class)->availableGrades(),
            'groupOptions' => ClassGroups::values(),
            'filters' => [
                'grade' => $grade,
                'class_group' => $classGroup,
                'assessment_id' => $assessmentId,
            ],
            'pendingResults' => $pendingResults,
        ]);
    }

    public function bulk(Request $request): Response
    {
        $validated = $request->validate([
            'assessment_id' => ['nullable', 'integer', 'exists:assessments,id'],
        ]);
        $assessment = isset($validated['assessment_id'])
            ? Assessment::query()->findOrFail($validated['assessment_id'])
            : Assessment::query()->latest('assessment_date')->latest('id')->first();

        $students = collect();
        if ($assessment) {
            $students = Student::query()
                ->where('is_active', true)
                ->whereIn('class_group', ClassGroups::values())
                ->when($assessment->class_name, fn (Builder $query, string $grade) => $query->where('class_name', $grade))
                ->when($assessment->class_group, fn (Builder $query, string $group) => $query->where('class_group', $group))
                ->with(['results' => fn ($query) => $query->where('assessment_id', $assessment->id)])
                ->orderBy('class_group')
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get()
                ->map(function (Student $student): array {
                    $result = $student->results->first();

                    return [
                        'student_id' => $student->id,
                        'admission_no' => $student->admission_no,
                        'student_name' => trim("{$student->first_name} {$student->last_name}"),
                        'class_name' => $student->class_name,
                        'class_group' => $student->class_group,
                        'marks' => $result?->marks,
                        'remarks' => $result?->remarks,
                    ];
                });
        }

        return Inertia::render('admin/results/Bulk', [
            'assessments' => Assessment::query()->latest('assessment_date')->latest('id')
                ->get(['id', 'title', 'class_name', 'class_group', 'academic_year', 'term', 'exam_type', 'assessment_date', 'total_marks']),
            'selectedAssessment' => $assessment,
            'rows' => $students,
        ]);
    }

    public function bulkStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'assessment_id' => ['required', 'integer', 'exists:assessments,id'],
            'results' => ['required', 'array', 'max:500'],
            'results.*.student_id' => ['required', 'integer', 'distinct', 'exists:students,id'],
            'results.*.marks' => ['nullable', 'numeric', 'min:0'],
            'results.*.remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $assessment = Assessment::query()->findOrFail($validated['assessment_id']);
        $rows = collect($validated['results'])->filter(fn (array $row) => $row['marks'] !== null && $row['marks'] !== '');
        $eligibleStudentIds = Student::query()
            ->whereKey($rows->pluck('student_id'))
            ->where('is_active', true)
            ->whereIn('class_group', ClassGroups::values())
            ->when($assessment->class_name, fn (Builder $query, string $grade) => $query->where('class_name', $grade))
            ->when($assessment->class_group, fn (Builder $query, string $group) => $query->where('class_group', $group))
            ->pluck('id');

        if ($eligibleStudentIds->count() !== $rows->count()) {
            throw ValidationException::withMessages(['results' => 'One or more students are not eligible for this exam. Refresh and try again.']);
        }
        if ($rows->contains(fn (array $row) => (float) $row['marks'] > $assessment->total_marks)) {
            throw ValidationException::withMessages(['results' => "Marks cannot exceed {$assessment->total_marks}."]);
        }

        DB::transaction(function () use ($rows, $assessment): void {
            $rows->each(fn (array $row) => $this->resultService->saveResult([
                'assessment_id' => $assessment->id,
                'student_id' => $row['student_id'],
                'marks' => $row['marks'],
                'remarks' => $row['remarks'] ?? null,
            ]));
        });

        return back()->with('success', $rows->count().' result(s) saved successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $validated = $request->validate(['assessment_id' => ['required', 'integer', 'exists:assessments,id']]);
        $assessment = Assessment::query()->findOrFail($validated['assessment_id']);
        $results = $assessment->results()->with('student')->orderByDesc('marks')->get();

        return response()->streamDownload(function () use ($assessment, $results): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Exam', 'Admission No', 'Student', 'Grade', 'Group', 'Marks', 'Total', 'Percentage', 'Remarks']);
            foreach ($results as $result) {
                fputcsv($output, [
                    $assessment->title,
                    $result->student?->admission_no,
                    trim(($result->student?->first_name ?? '').' '.($result->student?->last_name ?? '')),
                    $result->student?->class_name,
                    $result->student?->class_group,
                    $result->marks,
                    $assessment->total_marks,
                    $assessment->total_marks > 0 ? round(((float) $result->marks / $assessment->total_marks) * 100, 2) : null,
                    $result->remarks,
                ]);
            }
            fclose($output);
        }, 'results-'.$assessment->id.'-'.now()->format('Y-m-d-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function create(Request $request): Response
    {
        $validated = $request->validate([
            'assessment_id' => ['nullable', 'integer', 'exists:assessments,id'],
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
        ]);

        return Inertia::render('admin/results/Manage', [
            'assessments' => Assessment::latest()->get(['id', 'title', 'class_name', 'total_marks']),
            'students' => Student::where('is_active', true)->orderBy('first_name')->get(['id', 'admission_no', 'first_name', 'last_name', 'class_name', 'class_group']),
            'defaultAssessmentId' => $request->filled('assessment_id') ? $request->integer('assessment_id') : null,
            'defaultStudentId' => $request->filled('student_id') ? $request->integer('student_id') : null,
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
            'students' => Student::where('is_active', true)->orderBy('first_name')->get(['id', 'admission_no', 'first_name', 'last_name', 'class_name', 'class_group']),
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
