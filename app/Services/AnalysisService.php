<?php

namespace App\Services;

use App\Enums\ClassGroups;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AnalysisService
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    /** @return array<string, mixed> */
    public function snapshot(?string $grade, ?string $classGroup, ?int $assessmentId): array
    {
        $gradeOptions = $this->dashboardService->availableGrades();
        $selectedGrade = in_array($grade, $gradeOptions, true) ? $grade : null;
        $classGroupOptions = ClassGroups::values();
        $selectedClassGroup = in_array($classGroup, $classGroupOptions, true) ? $classGroup : null;

        $assessmentOptions = Assessment::query()
            ->when($selectedGrade, function (Builder $query, string $selectedGrade) {
                $query->where(function (Builder $gradeQuery) use ($selectedGrade) {
                    $gradeQuery
                        ->where('class_name', $selectedGrade)
                        ->orWhereNull('class_name');
                });
            })
            ->when($selectedClassGroup, fn (Builder $query, string $group) => $query->eligibleForGroup($group))
            ->latest('assessment_date')
            ->latest('id')
            ->get(['id', 'title', 'class_name', 'class_group', 'academic_year', 'term', 'exam_type', 'assessment_date', 'total_marks']);

        $selectedAssessment = $assessmentOptions->firstWhere('id', $assessmentId);
        $results = $this->resultsCollection($selectedGrade, $selectedClassGroup, $selectedAssessment?->id);
        $groupComparisonResults = $selectedClassGroup
            ? $this->resultsCollection($selectedGrade, null, $selectedAssessment?->id)
            : $results;
        $unassignedStudents = Student::query()
            ->where('is_active', true)
            ->when($selectedGrade, fn (Builder $query, string $grade) => $query->where('class_name', $grade))
            ->where(function (Builder $query) use ($classGroupOptions) {
                $query
                    ->whereNull('class_group')
                    ->orWhereNotIn('class_group', $classGroupOptions);
            })
            ->count();
        $percentages = $results
            ->pluck('percentage')
            ->filter(static fn ($value) => $value !== null)
            ->values();

        return [
            'selected_grade' => $selectedGrade,
            'selected_class_group' => $selectedClassGroup,
            'selected_assessment_id' => $selectedAssessment?->id,
            'grade_options' => $gradeOptions,
            'class_group_options' => $classGroupOptions,
            'assessment_options' => $assessmentOptions,
            'selected_assessment' => $selectedAssessment,
            'summary' => [
                'highest_mark' => $percentages->isNotEmpty() ? round((float) $percentages->max(), 2) : null,
                'lowest_mark' => $percentages->isNotEmpty() ? round((float) $percentages->min(), 2) : null,
                'average_mark' => $percentages->isNotEmpty() ? round((float) $percentages->avg(), 2) : null,
                'results_count' => $results->count(),
                'students_ranked' => $results->pluck('student_id')->unique()->count(),
                'unassigned_students' => $unassignedStudents,
            ],
            'ranking' => $selectedAssessment
                ? $this->assessmentRanking($results)
                : $this->aggregateRanking($results),
            'group_summaries' => $this->groupSummaries($groupComparisonResults),
            'grade_distribution' => $this->gradeDistribution($percentages),
        ];
    }

    /** @return Collection<int, array<string, mixed>> */
    private function resultsCollection(?string $grade, ?string $classGroup, ?int $assessmentId): Collection
    {
        return AssessmentResult::query()
            ->join('students', 'students.id', '=', 'assessment_results.student_id')
            ->join('assessments', 'assessments.id', '=', 'assessment_results.assessment_id')
            ->where('students.is_active', true)
            ->whereNull('students.deleted_at')
            ->whereNull('assessments.deleted_at')
            ->whereIn('students.class_group', ClassGroups::values())
            ->where(function (Builder $groupQuery) {
                $groupQuery
                    ->whereNull('assessments.class_group')
                    ->orWhereColumn('assessments.class_group', 'students.class_group');
            })
            ->when($grade, function (Builder $query, string $selectedGrade) {
                $query
                    ->where('students.class_name', $selectedGrade)
                    ->where(function (Builder $gradeQuery) use ($selectedGrade) {
                        $gradeQuery
                            ->where('assessments.class_name', $selectedGrade)
                            ->orWhereNull('assessments.class_name');
                    });
            })
            ->when($classGroup, fn (Builder $query, string $selectedClassGroup) => $query->where('students.class_group', $selectedClassGroup))
            ->when($assessmentId, fn (Builder $query, int $selectedAssessmentId) => $query->where('assessment_results.assessment_id', $selectedAssessmentId))
            ->orderByDesc('assessment_results.marks')
            ->get([
                'assessment_results.student_id',
                'assessment_results.marks',
                'students.first_name',
                'students.last_name',
                'students.class_name',
                'students.class_group',
                'assessments.total_marks',
            ])
            ->map(function ($result): array {
                $totalMarks = (int) $result->total_marks;
                $marks = (float) $result->marks;

                return [
                    'student_id' => (int) $result->student_id,
                    'student_name' => trim("{$result->first_name} {$result->last_name}"),
                    'class_name' => $result->class_name,
                    'class_group' => $result->class_group,
                    'marks' => $marks,
                    'percentage' => $totalMarks > 0 ? ($marks / $totalMarks) * 100 : null,
                ];
            });
    }

    /** @param Collection<int, array<string, mixed>> $results */
    /** @return Collection<int, array<string, int|float|string|null>> */
    private function assessmentRanking(Collection $results): Collection
    {
        return $this->rankWithinClassGroups($results->map(fn (array $result): array => [
            'student_id' => $result['student_id'],
            'student_name' => $result['student_name'],
            'class_name' => $result['class_name'],
            'class_group' => $result['class_group'],
            'marks' => $result['marks'],
        ]));
    }

    /** @param Collection<int, array<string, mixed>> $results */
    /** @return Collection<int, array<string, int|float|string|null>> */
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
                    'class_name' => $studentResults->first()['class_name'],
                    'class_group' => $studentResults->first()['class_group'],
                    'marks' => round((float) $percentages->avg(), 2),
                ];
            })
            ->values();

        return $this->rankWithinClassGroups($rankable);
    }

    /** @param Collection<int, array<string, mixed>> $results */
    /** @return array<int, array<string, int|float|string|null>> */
    private function groupSummaries(Collection $results): array
    {
        return collect(ClassGroups::values())
            ->map(function (string $classGroup) use ($results): array {
                $groupResults = $results->where('class_group', $classGroup);
                $percentages = $groupResults
                    ->pluck('percentage')
                    ->filter(static fn ($value) => $value !== null);

                return [
                    'class_group' => $classGroup,
                    'highest_mark' => $percentages->isNotEmpty() ? round((float) $percentages->max(), 2) : null,
                    'lowest_mark' => $percentages->isNotEmpty() ? round((float) $percentages->min(), 2) : null,
                    'average_mark' => $percentages->isNotEmpty() ? round((float) $percentages->avg(), 2) : null,
                    'students_ranked' => $groupResults->pluck('student_id')->unique()->count(),
                    'results_count' => $groupResults->count(),
                ];
            })
            ->values()
            ->all();
    }

    /** @param Collection<int, array<string, mixed>> $rows */
    /** @return Collection<int, array<string, int|float|string|null>> */
    private function rankWithinClassGroups(Collection $rows): Collection
    {
        return $rows
            ->groupBy(fn (array $row): string => ($row['class_name'] ?? 'Unassigned').'|'.($row['class_group'] ?? 'Unassigned'))
            ->flatMap(function (Collection $groupRows): Collection {
                $sortedRows = $groupRows
                    ->sortBy([
                        ['marks', 'desc'],
                        ['student_name', 'asc'],
                    ])
                    ->values();

                return $this->reRank($sortedRows, 'marks');
            })
            ->sortBy([
                ['class_name', 'asc'],
                ['class_group', 'asc'],
                ['rank', 'asc'],
                ['student_name', 'asc'],
            ])
            ->values();
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

    /** @param Collection<int, array<string, int|float|string|null>> $rows */
    /** @return Collection<int, array<string, int|float|string|null>> */
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
