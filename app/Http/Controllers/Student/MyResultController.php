<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class MyResultController extends Controller
{
    public function __invoke(): Response
    {
        $student = auth()->user()->student;
        $results = $student?->results()
            ->with('assessment')
            ->whereHas('assessment', fn ($query) => $query->where('is_published', true)->relevantToStudent($student))
            ->join('assessments', 'assessments.id', '=', 'assessment_results.assessment_id')
            ->orderByDesc('assessments.assessment_date')
            ->orderByDesc('assessment_results.id')
            ->select('assessment_results.*')
            ->get() ?? collect();

        return Inertia::render('student/MyResults', [
            'results' => $results,
        ]);
    }
}
