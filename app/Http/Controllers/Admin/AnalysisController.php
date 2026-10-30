<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ClassGroups;
use App\Enums\Grades;
use App\Http\Controllers\Controller;
use App\Services\AnalysisService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AnalysisController extends Controller
{
    public function __construct(private readonly AnalysisService $analysisService) {}

    public function __invoke(Request $request): Response
    {
        $validated = $request->validate([
            'grade' => ['nullable', 'string', 'max:120', Rule::in(Grades::values())],
            'class_group' => ['nullable', 'string', 'max:120', Rule::in(ClassGroups::values())],
            'assessment_id' => ['nullable', 'integer', 'exists:assessments,id'],
        ]);

        return Inertia::render('admin/analysis/Index', [
            'analysis' => $this->analysisService->snapshot(
                $validated['grade'] ?? null,
                $validated['class_group'] ?? null,
                $validated['assessment_id'] ?? null,
            ),
        ]);
    }
}
