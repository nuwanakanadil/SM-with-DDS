<?php

namespace App\Services;

use App\Enums\ClassGroups;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use Illuminate\Support\Collection;

class RankingService
{
    public function summaryForAssessment(Assessment $assessment, ?string $classGroup = null, ?string $className = null): array
    {
        $summary = $assessment->results()
            ->whereHas('student', function ($query) use ($assessment, $classGroup, $className) {
                $query
                    ->where('is_active', true)
                    ->whereIn('class_group', ClassGroups::values());

                if ($assessment->class_name) {
                    $query->where('class_name', $assessment->class_name);
                } elseif ($className) {
                    $query->where('class_name', $className);
                }

                if ($assessment->class_group) {
                    $query->where('class_group', $assessment->class_group);
                }

                if ($classGroup) {
                    $query->where('class_group', $classGroup);
                }
            })
            ->selectRaw('MAX(marks) as highest, MIN(marks) as lowest, AVG(marks) as average, COUNT(*) as students_count')
            ->first();

        return [
            'highest' => $summary?->highest !== null ? (float) $summary->highest : null,
            'lowest' => $summary?->lowest !== null ? (float) $summary->lowest : null,
            'average' => $summary?->average !== null ? round((float) $summary->average, 2) : 0,
            'students_count' => (int) ($summary?->students_count ?? 0),
        ];
    }

    public function ranksForAssessment(Assessment $assessment, ?string $classGroup = null, ?string $className = null): Collection
    {
        $results = $assessment->results()
            ->with('student')
            ->whereHas('student', function ($query) use ($assessment, $classGroup, $className) {
                $query
                    ->where('is_active', true)
                    ->whereIn('class_group', ClassGroups::values());

                if ($assessment->class_name) {
                    $query->where('class_name', $assessment->class_name);
                } elseif ($className) {
                    $query->where('class_name', $className);
                }

                if ($assessment->class_group) {
                    $query->where('class_group', $assessment->class_group);
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
        if (! $student->class_name
            || ! $student->class_group
            || ! $assessment->is_published
            || ($assessment->class_name && $assessment->class_name !== $student->class_name)
            || ($assessment->class_group && $assessment->class_group !== $student->class_group)) {
            return null;
        }

        $ranks = $this->ranksForAssessment($assessment, $student->class_group, $student->class_name)
            ->values();

        /** @var array<string, int|float|string>|null $placement */
        $placement = $ranks->firstWhere('student_id', $student->id);

        return $placement
            ? [
                ...$placement,
                'students_count' => $ranks->count(),
                'class_group' => $student->class_group,
            ]
            : null;
    }

    public function gradePlacementForStudent(Assessment $assessment, Student $student): ?array
    {
        if (! $student->class_name
            || ! $student->class_group
            || ! $assessment->is_published
            || ($assessment->class_name && $assessment->class_name !== $student->class_name)
            || ($assessment->class_group && $assessment->class_group !== $student->class_group)) {
            return null;
        }

        $ranks = $this->ranksForAssessment($assessment, null, $student->class_name)->values();

        /** @var array<string, int|float|string>|null $placement */
        $placement = $ranks->firstWhere('student_id', $student->id);

        return $placement
            ? [
                ...$placement,
                'students_count' => $ranks->count(),
                'class_name' => $student->class_name,
            ]
            : null;
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
