<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Services\RankingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly RankingService $rankingService) {}

    public function __invoke(): Response|RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['admin', 'staff'])) {
            return to_route('admin.dashboard');
        }

        $student = $user->student;
        $relevantAssessments = Assessment::query()
            ->where('is_published', true)
            ->relevantToStudent($student);

        $latestResult = $student
            ? AssessmentResult::query()
                ->with('assessment')
                ->where('student_id', $student->id)
                ->whereHas('assessment', fn ($query) => $query->where('is_published', true)->relevantToStudent($student))
                ->join('assessments', 'assessments.id', '=', 'assessment_results.assessment_id')
                ->orderByDesc('assessments.assessment_date')
                ->orderByDesc('assessment_results.id')
                ->select('assessment_results.*')
                ->first()
            : null;

        $latestAssessment = $latestResult?->assessment
            ?? (clone $relevantAssessments)->latest('assessment_date')->latest('id')->first();

        $resultsQuery = $student?->results()
            ->whereHas('assessment', fn ($query) => $query->where('is_published', true)->relevantToStudent($student));

        $averagePercentageRow = $student
            ? AssessmentResult::query()
                ->join('assessments', 'assessments.id', '=', 'assessment_results.assessment_id')
                ->where('assessment_results.student_id', $student->id)
                ->where('assessments.is_published', true)
                ->whereNull('assessments.deleted_at')
                ->where(function ($query) use ($student) {
                    $query->where('assessments.class_name', $student->class_name)
                        ->orWhereNull('assessments.class_name');
                })
                ->where(function ($query) use ($student) {
                    $query->whereNull('assessments.class_group')
                        ->orWhere('assessments.class_group', $student->class_group);
                })
                ->where('assessments.total_marks', '>', 0)
                ->selectRaw('AVG((assessment_results.marks * 100.0) / assessments.total_marks) as average_percentage')
                ->first()
            : null;

        return Inertia::render('student/Dashboard', [
            'student' => $student,
            'latestAssessment' => $latestAssessment,
            'latestResult' => $latestResult,
            'latestPlacement' => $student && $latestResult?->assessment
                ? $this->rankingService->placementForStudent($latestResult->assessment, $student)
                : null,
            'latestGradePlacement' => $student && $latestResult?->assessment
                ? $this->rankingService->gradePlacementForStudent($latestResult->assessment, $student)
                : null,
            'publishedAssessmentsCount' => (clone $relevantAssessments)->count(),
            'resultsCount' => $resultsQuery?->count() ?? 0,
            'averageMarks' => $averagePercentageRow?->average_percentage !== null
                ? round((float) $averagePercentageRow->average_percentage, 2)
                : null,
        ]);
    }
}
