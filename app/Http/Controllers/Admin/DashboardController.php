<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserTypes;
use App\Enums\Grades;
use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService) {}

    public function __invoke(Request $request): Response
    {
        $validated = $request->validate([
            'grade' => ['nullable', 'string', 'max:120', Rule::in(Grades::values())],
        ]);
        $isAdmin = $request->user()?->hasRole(UserTypes::Admin->value) ?? false;

        return Inertia::render('admin/dashboard/Index', [
            'dashboard' => $this->dashboardService->snapshot(
                $validated['grade'] ?? null,
                $isAdmin,
            ),
        ]);
    }
}
