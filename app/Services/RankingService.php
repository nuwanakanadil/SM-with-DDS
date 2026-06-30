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

    public function ranksForAssessment(Assessment $assessment): Collection
    {
        $results = $assessment->results()
            ->with('student')
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
        $placement = $this->ranksForAssessment($assessment)
            ->firstWhere('student_id', $student->id);

        return $placement;
    }

    public function getStudentRank(Student $student): ?int
    {
        $totals = AssessmentResult::query()
            ->selectRaw('student_id, SUM(marks) as total_marks')
            ->groupBy('student_id')
            ->orderByDesc('total_marks')
            ->pluck('student_id')
            ->values();

        $position = $totals->search($student->id);

        return $position === false ? null : $position + 1;
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
