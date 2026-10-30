<?php

namespace App\Services;

use App\Enums\ClassGroups;
use App\Enums\Grades;
use App\Enums\UserTypes;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /** @return array<string, mixed> */
    public function snapshot(?string $grade, ?string $classGroup, bool $isAdmin): array
    {
        $gradeOptions = $this->availableGrades();
        $selectedGrade = in_array($grade, $gradeOptions, true) ? $grade : null;
        $selectedClassGroup = in_array($classGroup, ClassGroups::values(), true) ? $classGroup : null;

        $overview = collect($gradeOptions)
            ->map(fn (string $gradeOption) => $this->buildOverviewRow($gradeOption, $selectedClassGroup))
            ->values();

        $summary = $selectedGrade
            ? $this->buildGradeSummary($selectedGrade, $selectedClassGroup, $isAdmin)
            : $this->buildAllGradesSummary($overview, $selectedClassGroup, $isAdmin);

        return [
            'viewer' => $isAdmin ? UserTypes::Admin->value : UserTypes::Staff->value,
            'selected_grade' => $selectedGrade,
            'selected_class_group' => $selectedClassGroup,
            'grade_options' => $gradeOptions,
            'class_group_options' => ClassGroups::values(),
            'summary' => $summary,
            'overview' => $overview->all(),
        ];
    }

    /** @return array<int, string> */
    public function availableGrades(): array
    {
        $knownGrades = collect(Grades::values());
        $availableGrades = Student::query()
            ->whereNotNull('class_name')
            ->distinct()
            ->pluck('class_name')
            ->merge(
                Assessment::query()
                    ->whereNotNull('class_name')
                    ->distinct()
                    ->pluck('class_name')
            )
            ->filter()
            ->unique()
            ->values();

        return $knownGrades
            ->filter(fn (string $grade) => $availableGrades->contains($grade))
            ->merge(
                $availableGrades
                    ->reject(fn (string $grade) => $knownGrades->contains($grade))
                    ->sort()
                    ->values()
            )
            ->values()
            ->all();
    }

    /** @return array<string, int> */
    private function buildGradeSummary(string $grade, ?string $classGroup, bool $isAdmin): array
    {
        $students = $this->studentsQuery($grade, $classGroup)->whereIn('class_group', ClassGroups::values())->count();
        $exams = $this->examsQuery($grade, $classGroup)->count();
        $resultsEntered = $this->resultsQuery($grade, $classGroup)->count();
        $pendingResults = max(0, $this->eligiblePairsCount($grade, $classGroup) - $resultsEntered);

        return [
            'total_students' => $students,
            'total_staff' => $isAdmin ? $this->staffCount() : 0,
            'total_exams' => $exams,
            'results_entered' => $resultsEntered,
            'pending_results' => $pendingResults,
        ];
    }

    /** @param Collection<int, array<string, int|string>> $overview */
    private function buildAllGradesSummary(Collection $overview, ?string $classGroup, bool $isAdmin): array
    {
        return [
            'total_students' => $this->studentsQuery(null, $classGroup)->count(),
            'total_staff' => $isAdmin ? $this->staffCount() : 0,
            'total_exams' => $this->examsQuery(null, $classGroup)->count(),
            'results_entered' => (int) $overview->sum('results_entered'),
            'pending_results' => (int) $overview->sum('pending_results'),
        ];
    }

    /** @return array<string, int|string> */
    private function buildOverviewRow(string $grade, ?string $classGroup): array
    {
        $students = $this->studentsQuery($grade, $classGroup)->whereIn('class_group', ClassGroups::values())->count();
        $exams = $this->examsQuery($grade, $classGroup)->count();
        $resultsEntered = $this->resultsQuery($grade, $classGroup)->count();

        return [
            'grade' => $grade,
            'students' => $students,
            'exams' => $exams,
            'results_entered' => $resultsEntered,
            'pending_results' => max(0, $this->eligiblePairsCount($grade, $classGroup) - $resultsEntered),
        ];
    }

    private function studentsQuery(?string $grade = null, ?string $classGroup = null): Builder
    {
        return Student::query()
            ->where('is_active', true)
            ->when($grade, fn (Builder $query, string $selectedGrade) => $query->where('class_name', $selectedGrade))
            ->when($classGroup, fn (Builder $query, string $group) => $query->where('class_group', $group));
    }

    private function examsQuery(?string $grade = null, ?string $classGroup = null): Builder
    {
        return Assessment::query()
            ->when($grade, function (Builder $query, string $selectedGrade) {
                $query->where(function (Builder $gradeQuery) use ($selectedGrade) {
                    $gradeQuery
                        ->where('class_name', $selectedGrade)
                        ->orWhereNull('class_name');
                });
            })
            ->when($classGroup, fn (Builder $query, string $group) => $query->eligibleForGroup($group));
    }

    private function resultsQuery(?string $grade = null, ?string $classGroup = null): Builder
    {
        return AssessmentResult::query()
            ->join('students', 'students.id', '=', 'assessment_results.student_id')
            ->join('assessments', 'assessments.id', '=', 'assessment_results.assessment_id')
            ->where('students.is_active', true)
            ->whereIn('students.class_group', ClassGroups::values())
            ->when($classGroup, fn (Builder $query, string $group) => $query->where('students.class_group', $group))
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
            });
    }

    private function eligiblePairsCount(string $grade, ?string $classGroup = null): int
    {
        return DB::table('students')
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
            ->whereNull('students.deleted_at')
            ->whereNull('assessments.deleted_at')
            ->where('students.class_name', $grade)
            ->whereIn('students.class_group', ClassGroups::values())
            ->when($classGroup, fn ($query, string $group) => $query->where('students.class_group', $group))
            ->count();
    }

    private function staffCount(): int
    {
        return User::role(UserTypes::Staff->value)->count();
    }
}
