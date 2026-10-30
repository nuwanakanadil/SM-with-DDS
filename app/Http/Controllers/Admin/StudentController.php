<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ClassGroups;
use App\Enums\Grades;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\CreateStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    public function __construct(private readonly StudentService $studentService) {}

    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'class_name' => ['nullable', 'string', 'max:120', Rule::in(Grades::values())],
            'class_group' => ['nullable', 'string', 'max:120', Rule::in([...ClassGroups::values(), 'unassigned'])],
            'status' => ['nullable', 'string', Rule::in(['active', 'inactive'])],
            'sort' => ['nullable', 'string', Rule::in(['name_asc', 'name_desc', 'admission_asc', 'admission_desc'])],
        ]);

        $filters = array_merge([
            'search' => '',
            'class_name' => '',
            'class_group' => '',
            'status' => '',
            'sort' => '',
        ], $filters);

        $students = $this->filteredStudentsQuery($filters)
            ->when($filters['sort'] === 'name_asc', fn ($query) => $query->orderBy('first_name')->orderBy('last_name'))
            ->when($filters['sort'] === 'name_desc', fn ($query) => $query->orderByDesc('first_name')->orderByDesc('last_name'))
            ->when($filters['sort'] === 'admission_asc', fn ($query) => $query->orderBy('admission_no'))
            ->when($filters['sort'] === 'admission_desc', fn ($query) => $query->orderByDesc('admission_no'))
            ->when(
                ! in_array($filters['sort'], ['name_asc', 'name_desc', 'admission_asc', 'admission_desc'], true),
                fn ($query) => $query->latest()
            )
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('admin/students/Index', [
            'students' => $students,
            'filters' => $filters,
            'gradeOptions' => Grades::values(),
            'classGroupOptions' => ClassGroups::values(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/students/Manage', [
            'gradeOptions' => Grades::values(),
            'classGroupOptions' => ClassGroups::values(),
        ]);
    }

    public function store(CreateStudentRequest $request): RedirectResponse
    {
        $result = $this->studentService->create($request->validated());
        $response = redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');

        if ($result['warning']) {
            $response->with('warning', $result['warning']);
        }

        return $response;
    }

    public function edit(Student $student): Response
    {
        return Inertia::render('admin/students/Manage', [
            'student' => $student,
            'gradeOptions' => Grades::values(),
            'classGroupOptions' => ClassGroups::values(),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $result = $this->studentService->update($student, $request->validated());
        $response = redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');

        if ($result['warning']) {
            $response->with('warning', $result['warning']);
        }

        return $response;
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }

    public function bulkGroup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_ids' => ['required', 'array', 'min:1', 'max:500'],
            'student_ids.*' => ['integer', 'distinct', 'exists:students,id'],
            'class_group' => ['required', 'string', Rule::in(ClassGroups::values())],
        ]);

        $updated = DB::transaction(fn (): int => Student::query()
            ->whereKey($validated['student_ids'])
            ->update(['class_group' => $validated['class_group']]));

        return back()->with('success', "{$updated} student(s) assigned to {$validated['class_group']}.");
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'class_name' => ['nullable', 'string', Rule::in(Grades::values())],
            'class_group' => ['nullable', 'string', Rule::in([...ClassGroups::values(), 'unassigned'])],
            'status' => ['nullable', 'string', Rule::in(['active', 'inactive'])],
        ]);

        $students = $this->filteredStudentsQuery($filters)
            ->orderBy('class_name')
            ->orderBy('class_group')
            ->orderBy('admission_no')
            ->get();

        return response()->streamDownload(function () use ($students): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Admission No', 'First Name', 'Last Name', 'School', 'Grade', 'Class Group', 'Status']);
            foreach ($students as $student) {
                fputcsv($output, [
                    $student->admission_no,
                    $student->first_name,
                    $student->last_name,
                    $student->school,
                    $student->class_name,
                    $student->class_group,
                    $student->is_active ? 'Active' : 'Inactive',
                ]);
            }
            fclose($output);
        }, 'students-'.now()->format('Y-m-d-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filteredStudentsQuery(array $filters): Builder
    {
        return Student::query()
            ->when($filters['search'] ?? '', function ($query, string $search) {
                $query->where(function ($studentQuery) use ($search) {
                    $studentQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('admission_no', 'like', "%{$search}%")
                        ->orWhere('school', 'like', "%{$search}%");
                });
            })
            ->when($filters['class_name'] ?? '', fn ($query, string $className) => $query->where('class_name', $className))
            ->when(($filters['class_group'] ?? '') === 'unassigned', fn ($query) => $query->whereNull('class_group'))
            ->when(
                ($filters['class_group'] ?? '') && ($filters['class_group'] ?? '') !== 'unassigned',
                fn ($query) => $query->where('class_group', $filters['class_group'])
            )
            ->when(($filters['status'] ?? '') === 'active', fn ($query) => $query->where('is_active', true))
            ->when(($filters['status'] ?? '') === 'inactive', fn ($query) => $query->where('is_active', false));
    }
}
