<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalysisService;
use Inertia\Inertia;
use Inertia\Response;

class AnalysisController extends Controller
{
    public function __construct(private readonly AnalysisService $analysisService) {}

    public function __invoke(): Response
    {
        return Inertia::render('admin/analysis/Index', [
            'analysis' => $this->analysisService->dashboardSummary(),
        ]);
    }
}
