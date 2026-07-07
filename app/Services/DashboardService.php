<?php

namespace App\Services;

use App\Enums\Grades;
use App\Enums\UserTypes;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DashboardService
{
    /** @return array<string, mixed> */
    public function snapshot(?string $grade, bool $isAdmin): array
    {
        $gradeOptions = $this->availableGrades();
        $selectedGrade = in_array($grade, $gradeOptions, true) ? $grade : null;

        $overview = collect($gradeOptions)
            ->map(fn (string $gradeOption) => $this->buildOverviewRow($gradeOption))
            ->values();

        $summary = $selectedGrade
            ? $this->buildGradeSummary($selectedGrade, $isAdmin)
            : $this->buildAllGradesSummary($overview, $isAdmin);

        return [
            'viewer' => $isAdmin ? UserTypes::Admin->value : UserTypes::Staff->value,
            'selected_grade' => $selectedGrade,
            'grade_options' => $gradeOptions,
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
    private function buildGradeSummary(string $grade, bool $isAdmin): array
    {
        $students = $this->studentsQuery($grade)->count();
        $exams = $this->examsQuery($grade)->count();
        $resultsEntered = $this->resultsQuery($grade)->count();
        $pendingResults = max(0, ($students * $exams) - $resultsEntered);

        return [
            'total_students' => $students,
            'total_staff' => $isAdmin ? $this->staffCount() : 0,
            'total_exams' => $exams,
            'results_entered' => $resultsEntered,
            'pending_results' => $pendingResults,
        ];
    }

    /** @param Collection<int, array<string, int|string>> $overview */
    private function buildAllGradesSummary(Collection $overview, bool $isAdmin): array
    {
        $unassignedStudents = Student::query()
            ->where('is_active', true)
            ->whereNull('class_name')
            ->count();
        $sharedExams = Assessment::query()
            ->whereNull('class_name')
            ->count();
        $unassignedResults = AssessmentResult::query()
            ->join('students', 'students.id', '=', 'assessment_results.student_id')
            ->join('assessments', 'assessments.id', '=', 'assessment_results.assessment_id')
            ->where('students.is_active', true)
            ->whereNull('students.class_name')
            ->whereNull('assessments.class_name')
            ->count();

        return [
            'total_students' => Student::query()->where('is_active', true)->count(),
            'total_staff' => $isAdmin ? $this->staffCount() : 0,
            'total_exams' => Assessment::query()->count(),
            'results_entered' => (int) $overview->sum('results_entered') + $unassignedResults,
            'pending_results' => (int) $overview->sum('pending_results')
                + max(0, ($unassignedStudents * $sharedExams) - $unassignedResults),
        ];
    }

    /** @return array<string, int|string> */
    private function buildOverviewRow(string $grade): array
    {
        $students = $this->studentsQuery($grade)->count();
        $exams = $this->examsQuery($grade)->count();
        $resultsEntered = $this->resultsQuery($grade)->count();

        return [
            'grade' => $grade,
            'students' => $students,
            'exams' => $exams,
            'results_entered' => $resultsEntered,
            'pending_results' => max(0, ($students * $exams) - $resultsEntered),
        ];
    }

    private function studentsQuery(?string $grade = null): Builder
    {
        return Student::query()
            ->where('is_active', true)
            ->when($grade, fn (Builder $query, string $selectedGrade) => $query->where('class_name', $selectedGrade));
    }

    private function examsQuery(?string $grade = null): Builder
    {
        return Assessment::query()
            ->when($grade, function (Builder $query, string $selectedGrade) {
                $query->where(function (Builder $gradeQuery) use ($selectedGrade) {
                    $gradeQuery
                        ->where('class_name', $selectedGrade)
                        ->orWhereNull('class_name');
                });
            });
    }

    private function resultsQuery(?string $grade = null): Builder
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
            });
    }

    private function staffCount(): int
    {
        return User::role(UserTypes::Staff->value)->count();
    }
}
