<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;

class AnalysisService
{
    /** @return array<string, float|int|null> */
    public function dashboardSummary(): array
    {
        $summary = AssessmentResult::query()
            ->selectRaw('MAX(marks) as highest, MIN(marks) as lowest, AVG(marks) as average, COUNT(*) as total_results')
            ->first();

        return [
            'highest_mark' => $summary?->highest !== null ? (float) $summary->highest : null,
            'lowest_mark' => $summary?->lowest !== null ? (float) $summary->lowest : null,
            'average_mark' => $summary?->average !== null ? round((float) $summary->average, 2) : null,
            'total_students' => Student::query()->where('is_active', true)->count(),
            'total_assessments' => Assessment::query()->count(),
            'total_results' => (int) ($summary?->total_results ?? 0),
        ];
    }
}
