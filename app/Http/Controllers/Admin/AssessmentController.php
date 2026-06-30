<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\SaveAssessmentRequest;
use App\Models\Assessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssessmentController extends Controller
{
    public function index(Request $request): Response
    {
        $assessments = Assessment::query()
            ->when($request->search, fn ($query, string $search) => $query->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('admin/assessments/Index', [
            'assessments' => $assessments,
            'filters' => $request->only('search'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/assessments/Manage');
    }

    public function store(SaveAssessmentRequest $request): RedirectResponse
    {
        Assessment::create($request->validated());

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment created successfully.');
    }

    public function edit(Assessment $assessment): Response
    {
        return Inertia::render('admin/assessments/Manage', ['assessment' => $assessment]);
    }

    public function update(SaveAssessmentRequest $request, Assessment $assessment): RedirectResponse
    {
        $assessment->update($request->validated());

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment updated successfully.');
    }

    public function destroy(Assessment $assessment): RedirectResponse
    {
        $assessment->delete();

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment deleted successfully.');
    }
}
