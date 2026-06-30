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
            ->get()
            ->sortByDesc(fn ($result) => $result->assessment?->assessment_date?->timestamp ?? 0)
            ->values() ?? collect();

        return Inertia::render('student/MyResults', [
            'results' => $results,
        ]);
    }
}
