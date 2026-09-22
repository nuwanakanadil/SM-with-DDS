<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use Illuminate\Support\Collection;

class RankingService
{
    public function summaryForAssessment(Assessment $assessment): array
    {
        $summary = $assessment->results()
            ->selectRaw('MAX(marks) as highest, MIN(marks) as lowest, AVG(marks) as average, COUNT(*) as students_count')
            ->first();

        return [
            'highest' => $summary?->highest !== null ? (float) $summary->highest : null,
            'lowest' => $summary?->lowest !== null ? (float) $summary->lowest : null,
            'average' => $summary?->average !== null ? round((float) $summary->average, 2) : 0,
            'students_count' => (int) ($summary?->students_count ?? 0),
        ];
    }

    public function ranksForAssessment(Assessment $assessment, ?string $classGroup = null): Collection
    {
        $results = $assessment->results()
            ->with('student')
            ->whereHas('student', function ($query) use ($assessment, $classGroup) {
                $query->where('is_active', true);

                if ($assessment->class_name) {
                    $query->where('class_name', $assessment->class_name);
                }

                if ($classGroup) {
                    $query->where('class_group', $classGroup);
                }
            })
            ->orderByDesc('marks')
            ->orderBy('student_id')
            ->get();

        $position = 0;
        $rank = 0;
        $previousMarks = null;

        return $results->map(function (AssessmentResult $result) use (&$position, &$rank, &$previousMarks): array {
            $position++;
            $marks = (float) $result->marks;

            if ($previousMarks === null || $marks !== $previousMarks) {
                $rank = $position;
                $previousMarks = $marks;
            }

            return [
                'rank' => $rank,
                'student_id' => $result->student_id,
                'student_name' => $result->student?->full_name ?? 'Unknown Student',
                'marks' => $marks,
            ];
        });
    }

    public function placementForStudent(Assessment $assessment, Student $student): ?array
    {
        /** @var array<string, int|float|string>|null $placement */
        $placement = $this->ranksForAssessment($assessment, $student->class_group)
            ->firstWhere('student_id', $student->id);

        return $placement;
    }

    public function getStudentRank(Student $student): ?int
    {
        return $this->getStudentGradeRank($student);
    }

    public function getStudentGradeRank(Student $student): ?int
    {
        if (! $student->class_name) {
            return null;
        }

        $totals = AssessmentResult::query()
            ->join('assessments', 'assessments.id', '=', 'assessment_results.assessment_id')
            ->join('students', 'students.id', '=', 'assessment_results.student_id')
            ->where('students.is_active', true)
            ->where('assessments.is_published', true)
            ->whereNull('assessments.deleted_at')
            ->whereNull('students.deleted_at')
            ->where('students.class_name', $student->class_name)
            ->where('assessments.class_name', $student->class_name)
            ->selectRaw('assessment_results.student_id, SUM(assessment_results.marks) as total_marks')
            ->groupBy('assessment_results.student_id')
            ->orderByDesc('total_marks')
            ->orderBy('assessment_results.student_id')
            ->get();

        $position = 0;
        $rank = 0;
        $previousMarks = null;

        foreach ($totals as $total) {
            $position++;
            $marks = (float) $total->total_marks;

            if ($previousMarks === null || $marks !== $previousMarks) {
                $rank = $position;
                $previousMarks = $marks;
            }

            if ((int) $total->student_id === $student->id) {
                return $rank;
            }
        }

        return null;
    }

    public function getHighestMarks(int $limit = 10)
    {
        return AssessmentResult::query()
            ->with(['student', 'assessment'])
            ->orderByDesc('marks')
            ->limit($limit)
            ->get();
    }
}
