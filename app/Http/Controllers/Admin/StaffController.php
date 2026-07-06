<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\CreateStaffRequest;
use App\Http\Requests\Staff\UpdateStaffRequest;
use App\Models\User;
use App\Services\StaffService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StaffController extends Controller
{
    public function __construct(
        private readonly StaffService $staffService,
    ) {}

    public function index(Request $request): Response
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'sort' => $request->string('sort')->toString(),
        ];

        return Inertia::render('admin/staff/Index', [
            'staffMembers' => $this->staffService->paginated($filters),
            'filters' => $filters,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/staff/Manage');
    }

    public function store(CreateStaffRequest $request): RedirectResponse
    {
        $this->staffService->create($request->validated());

        return redirect()->route('admin.staff.index')->with('success', 'Staff account created and login details emailed.');
    }

    public function edit(User $staff): Response
    {
        $this->ensureStaffAccount($staff);

        return Inertia::render('admin/staff/Manage', [
            'staff' => $staff,
        ]);
    }

    public function update(UpdateStaffRequest $request, User $staff): RedirectResponse
    {
        $this->ensureStaffAccount($staff);

        $this->staffService->update($staff, $request->validated());

        return redirect()->route('admin.staff.index')->with('success', 'Staff account updated successfully.');
    }

    public function destroy(User $staff): RedirectResponse
    {
        $this->ensureStaffAccount($staff);

        if ($staff->is(auth()->user())) {
            return back()->with('error', 'You cannot delete your own staff account.');
        }

        $this->staffService->delete($staff);

        return redirect()->route('admin.staff.index')->with('success', 'Staff account deleted successfully.');
    }

    private function ensureStaffAccount(User $staff): void
    {
        abort_unless($staff->hasRole('staff'), 404);
    }
}
