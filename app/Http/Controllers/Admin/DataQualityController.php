<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ClassGroups;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class DataQualityController extends Controller
{
    public function __invoke(): Response
    {
        $students = Student::query()->where('is_active', true)->get(['id', 'class_name', 'class_group']);
        $unassigned = $students->filter(fn (Student $student) => ! in_array($student->class_group, ClassGroups::values(), true));
        $draftAssessmentIds = Assessment::query()->where('is_published', false)->pluck('id');

        $groupBreakdown = $students
            ->groupBy(fn (Student $student): string => ($student->class_name ?: 'Unassigned grade').'|'.($student->class_group ?: 'Unassigned group'))
            ->map(function ($rows, string $key): array {
                [$grade, $group] = explode('|', $key, 2);

                return ['grade' => $grade, 'class_group' => $group, 'students' => $rows->count()];
            })
            ->sortBy([['grade', 'asc'], ['class_group', 'asc']])
            ->values();

        return Inertia::render('admin/data-quality/Index', [
            'summary' => [
                'active_students' => $students->count(),
                'unassigned_group' => $unassigned->count(),
                'results_for_unassigned' => AssessmentResult::query()->whereIn('student_id', $unassigned->pluck('id'))->count(),
                'draft_results' => AssessmentResult::query()->whereIn('assessment_id', $draftAssessmentIds)->count(),
            ],
            'groupBreakdown' => $groupBreakdown,
            'recentActivity' => Activity::query()
                ->with('causer:id,name,email')
                ->latest()
                ->limit(25)
                ->get(['id', 'description', 'event', 'subject_type', 'subject_id', 'causer_type', 'causer_id', 'created_at']),
        ]);
    }
}
