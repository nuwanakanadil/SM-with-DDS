<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalysisService;
use Inertia\Inertia;
use Inertia\Response;

class AnalysisController extends Controller
{
    public function __construct(private readonly AnalysisService $analysisService) {}

    public function __invoke(\Illuminate\Http\Request $request): Response
    {
        return Inertia::render('admin/analysis/Index', [
            'analysis' => $this->analysisService->snapshot(
                $request->string('grade')->toString(),
                $request->integer('assessment_id') ?: null,
            ),
        ]);
    }
}
