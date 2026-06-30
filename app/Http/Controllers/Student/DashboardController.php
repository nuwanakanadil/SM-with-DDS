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

        return Inertia::render('student/Dashboard', [
            'student' => $student,
            'latestAssessment' => $latestAssessment,
            'latestResult' => $latestResult,
            'latestPlacement' => $student && $latestAssessment ? $this->rankingService->placementForStudent($latestAssessment, $student) : null,
            'publishedAssessmentsCount' => (clone $relevantAssessments)->count(),
            'resultsCount' => $resultsQuery?->count() ?? 0,
            'averageMarks' => $resultsQuery ? round((float) $resultsQuery->avg('marks'), 2) : null,
        ]);
    }
}
