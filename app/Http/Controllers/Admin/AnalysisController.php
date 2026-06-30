<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use Inertia\Inertia;
use Inertia\Response;

class AnalysisController extends Controller
{
    public function __invoke(): Response
    {
        $summary = AssessmentResult::query()
            ->selectRaw('MAX(marks) as highest, MIN(marks) as lowest, AVG(marks) as average, COUNT(*) as total_results')
            ->first();

        return Inertia::render('admin/analysis/Index', [
            'analysis' => [
                'highest_mark' => $summary?->highest !== null ? (float) $summary->highest : null,
                'lowest_mark' => $summary?->lowest !== null ? (float) $summary->lowest : null,
                'average_mark' => $summary?->average !== null ? round((float) $summary->average, 2) : null,
                'total_students' => Student::query()->count(),
                'total_assessments' => Assessment::query()->count(),
                'total_results' => (int) ($summary?->total_results ?? 0),
            ],
        ]);
    }
}
