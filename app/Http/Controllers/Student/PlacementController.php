<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Services\RankingService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlacementController extends Controller
{
    public function __construct(private readonly RankingService $rankingService) {}

    public function __invoke(Request $request): Response
    {
        $student = auth()->user()->student;
        $relevantAssessments = Assessment::query()
            ->where('is_published', true)
            ->relevantToStudent($student);

        $assessment = (clone $relevantAssessments)
            ->when($request->assessment_id, fn ($query, $id) => $query->where('id', $id))
            ->latest('assessment_date')
            ->latest('id')
            ->first();

        return Inertia::render('student/Placement', [
            'assessments' => (clone $relevantAssessments)->latest('assessment_date')->latest('id')->get(['id', 'title', 'class_name', 'assessment_date', 'total_marks', 'is_published']),
            'selectedAssessment' => $assessment,
            'placement' => $student && $assessment ? $this->rankingService->placementForStudent($assessment, $student) : null,
        ]);
    }
}
