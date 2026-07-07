<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AnalysisService
{
    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly RankingService $rankingService,
    ) {}

    /** @return array<string, mixed> */
    public function snapshot(?string $grade, ?int $assessmentId): array
    {
        $gradeOptions = $this->dashboardService->availableGrades();
        $selectedGrade = in_array($grade, $gradeOptions, true) ? $grade : null;

        $assessmentOptions = Assessment::query()
            ->when($selectedGrade, function (Builder $query, string $selectedGrade) {
                $query->where(function (Builder $gradeQuery) use ($selectedGrade) {
                    $gradeQuery
                        ->where('class_name', $selectedGrade)
                        ->orWhereNull('class_name');
                });
            })
            ->latest('assessment_date')
            ->latest('id')
            ->get(['id', 'title', 'class_name', 'assessment_date', 'total_marks']);

        $selectedAssessment = $assessmentOptions->firstWhere('id', $assessmentId);
        $results = $this->resultsCollection($selectedGrade, $selectedAssessment?->id);
        $percentages = $results
            ->pluck('percentage')
            ->filter(static fn ($value) => $value !== null)
            ->values();

        return [
            'selected_grade' => $selectedGrade,
            'selected_assessment_id' => $selectedAssessment?->id,
            'grade_options' => $gradeOptions,
            'assessment_options' => $assessmentOptions,
            'selected_assessment' => $selectedAssessment,
            'summary' => [
                'highest_mark' => $percentages->isNotEmpty() ? round((float) $percentages->max(), 2) : null,
                'lowest_mark' => $percentages->isNotEmpty() ? round((float) $percentages->min(), 2) : null,
                'average_mark' => $percentages->isNotEmpty() ? round((float) $percentages->avg(), 2) : null,
                'results_count' => $results->count(),
                'students_ranked' => $results->pluck('student_id')->unique()->count(),
            ],
            'ranking' => $selectedAssessment
                ? $this->assessmentRanking($selectedAssessment->id, $selectedGrade)
                : $this->aggregateRanking($results),
            'grade_distribution' => $this->gradeDistribution($percentages),
        ];
    }

    /** @return Collection<int, array<string, mixed>> */
    private function resultsCollection(?string $grade, ?int $assessmentId): Collection
    {
        return AssessmentResult::query()
            ->join('students', 'students.id', '=', 'assessment_results.student_id')
            ->join('assessments', 'assessments.id', '=', 'assessment_results.assessment_id')
            ->where('students.is_active', true)
            ->when($grade, function (Builder $query, string $selectedGrade) {
                $query
                    ->where('students.class_name', $selectedGrade)
                    ->where(function (Builder $gradeQuery) use ($selectedGrade) {
                        $gradeQuery
                            ->where('assessments.class_name', $selectedGrade)
                            ->orWhereNull('assessments.class_name');
                    });
            })
            ->when($assessmentId, fn (Builder $query, int $selectedAssessmentId) => $query->where('assessment_results.assessment_id', $selectedAssessmentId))
            ->orderByDesc('assessment_results.marks')
            ->get([
                'assessment_results.student_id',
                'assessment_results.marks',
                'students.first_name',
                'students.last_name',
                'assessments.total_marks',
            ])
            ->map(function ($result): array {
                $totalMarks = (int) $result->total_marks;
                $marks = (float) $result->marks;

                return [
                    'student_id' => (int) $result->student_id,
                    'student_name' => trim("{$result->first_name} {$result->last_name}"),
                    'marks' => $marks,
                    'percentage' => $totalMarks > 0 ? ($marks / $totalMarks) * 100 : null,
                ];
            });
    }

    /** @return Collection<int, array<string, int|float|string>> */
    private function assessmentRanking(int $assessmentId, ?string $grade): Collection
    {
        $assessment = Assessment::query()->find($assessmentId);

        if (! $assessment) {
            return collect();
        }

        $ranks = $this->rankingService->ranksForAssessment($assessment)
            ->values();

        if (! $grade) {
            return $ranks;
        }

        $eligibleStudentIds = Student::query()
            ->where('is_active', true)
            ->where('class_name', $grade)
            ->pluck('id');

        $filteredRanks = $ranks
            ->filter(fn (array $rank) => $eligibleStudentIds->contains($rank['student_id']))
            ->values();

        return $this->reRank($filteredRanks, 'marks');
    }

    /** @param Collection<int, array<string, mixed>> $results */
    /** @return Collection<int, array<string, int|float|string>> */
    private function aggregateRanking(Collection $results): Collection
    {
        $rankable = $results
            ->groupBy('student_id')
            ->map(function (Collection $studentResults, int $studentId): array {
                $percentages = $studentResults
                    ->pluck('percentage')
                    ->filter(static fn ($value) => $value !== null);

                return [
                    'student_id' => (int) $studentId,
                    'student_name' => (string) $studentResults->first()['student_name'],
                    'marks' => round((float) $percentages->avg(), 2),
                ];
            })
            ->sortBy([
                ['marks', 'desc'],
                ['student_name', 'asc'],
            ])
            ->values();

        return $this->reRank($rankable, 'marks');
    }

    /** @param Collection<int, float|int> $percentages */
    /** @return array<int, array<string, int|string>> */
    private function gradeDistribution(Collection $percentages): array
    {
        $bands = [
            'A (75-100%)' => fn (float $percentage): bool => $percentage >= 75,
            'B (65-74%)' => fn (float $percentage): bool => $percentage >= 65 && $percentage < 75,
            'C (50-64%)' => fn (float $percentage): bool => $percentage >= 50 && $percentage < 65,
            'D (Below 50%)' => fn (float $percentage): bool => $percentage < 50,
        ];

        return collect($bands)
            ->map(fn ($matches, string $label): array => [
                'grade' => $label,
                'count' => $percentages->filter(fn ($percentage): bool => $matches((float) $percentage))->count(),
            ])
            ->values()
            ->all();
    }

    /** @param Collection<int, array<string, int|float|string>> $rows */
    /** @return Collection<int, array<string, int|float|string>> */
    private function reRank(Collection $rows, string $metric): Collection
    {
        $position = 0;
        $rank = 0;
        $previousScore = null;

        return $rows->map(function (array $row) use (&$position, &$rank, &$previousScore, $metric): array {
            $position++;
            $score = (float) $row[$metric];

            if ($previousScore === null || $score !== $previousScore) {
                $rank = $position;
                $previousScore = $score;
            }

            return [
                'rank' => $rank,
                ...$row,
            ];
        });
    }
}
