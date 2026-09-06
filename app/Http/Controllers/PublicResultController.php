<?php

namespace App\Http\Controllers;

use App\Models\AssessmentResult;
use App\Models\Student;
use App\Services\RankingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class PublicResultController extends Controller
{
    public function __construct(private readonly RankingService $rankingService) {}

    public function index(): Response
    {
        return Inertia::render('Results', [
            'searchUrl' => route('public.results.search'),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'admission_no' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9._\-\/]+$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid admission number.',
                'student' => null,
                'results' => [],
                'summary' => null,
            ], 422);
        }

        $validated = $validator->validated();

        $student = Student::query()
            ->where('is_active', true)
            ->where('admission_no', trim($validated['admission_no']))
            ->first();

        if (! $student) {
            return response()->json([
                'student' => null,
                'results' => [],
                'summary' => null,
            ]);
        }

        $results = AssessmentResult::query()
            ->with('assessment')
            ->where('student_id', $student->id)
            ->whereHas('assessment', fn ($query) => $query
                ->where('is_published', true)
                ->relevantToStudent($student))
            ->join('assessments', 'assessments.id', '=', 'assessment_results.assessment_id')
            ->orderByDesc('assessments.assessment_date')
            ->orderByDesc('assessment_results.id')
            ->select('assessment_results.*')
            ->get()
            ->map(function (AssessmentResult $result) use ($student): array {
                $assessment = $result->assessment;
                $summary = $assessment ? $this->rankingService->summaryForAssessment($assessment) : null;
                $placement = $assessment ? $this->rankingService->placementForStudent($assessment, $student) : null;
                $marks = (float) $result->marks;
                $totalMarks = $assessment?->total_marks ? (float) $assessment->total_marks : null;
                $percentage = $totalMarks && $totalMarks > 0 ? round(($marks / $totalMarks) * 100, 2) : null;

                return [
                    'row_key' => hash('sha256', $student->admission_no.'|'.$result->assessment_id),
                    'exam_name' => $assessment?->title,
                    'subject' => $assessment?->title,
                    'class_name' => $assessment?->class_name,
                    'assessment_date' => $assessment?->assessment_date?->toDateString(),
                    'marks' => $marks,
                    'total_marks' => $totalMarks,
                    'grade' => $percentage !== null ? $this->gradeForPercentage($percentage) : null,
                    'remarks' => $result->remarks,
                    'average' => $summary['average'] ?? null,
                    'rank' => $placement['rank'] ?? null,
                ];
            });

        return response()->json([
            'student' => [
                'name' => $student->full_name,
                'admission_no' => $student->admission_no,
                'class_name' => $student->class_name,
            ],
            'results' => $results,
            'summary' => [
                'total' => round((float) $results->sum('marks'), 2),
                'average' => $results->isNotEmpty() ? round((float) $results->avg('marks'), 2) : null,
                'rank' => $this->rankingService->getStudentRank($student),
            ],
        ]);
    }

    private function gradeForPercentage(float $percentage): string
    {
        return match (true) {
            $percentage >= 75 => 'A',
            $percentage >= 65 => 'B',
            $percentage >= 55 => 'C',
            $percentage >= 35 => 'S',
            default => 'W',
        };
    }
}
