<?php

use App\Enums\Grades;
use App\Enums\Permissions;
use App\Enums\UserTypes;
use App\Mail\AccountCreatedMail;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

function createLmsUser(string $roleName = UserTypes::Admin->value): User
{
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    foreach (Permissions::cases() as $permission) {
        Permission::findOrCreate($permission->value, 'web');
    }

    $role = Role::findOrCreate($roleName, 'web');

    if ($roleName === UserTypes::Student->value) {
        $role->syncPermissions([
            Permissions::ViewDashboard->value,
            Permissions::ViewOwnResults->value,
            Permissions::ViewOwnPlacement->value,
        ]);
    } elseif ($roleName === UserTypes::Admin->value) {
        $role->syncPermissions(Permission::query()->pluck('name')->all());
    } else {
        $role->syncPermissions([
            Permissions::ViewDashboard->value,
            Permissions::ViewStudents->value,
            Permissions::ManageStudents->value,
            Permissions::ViewAssessments->value,
            Permissions::ManageAssessments->value,
            Permissions::ViewResults->value,
            Permissions::ManageResults->value,
            Permissions::ViewAnalysis->value,
        ]);
    }

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

test('admin can create update and delete students assessments and results', function () {
    $admin = createLmsUser();

    $this->actingAs($admin);

    $this->post('/admin/students', [
        'admission_no' => 'ADM-001',
        'first_name' => 'Alice',
        'last_name' => 'Perera',
        'email' => 'alice@example.com',
        'phone' => '0711111111',
        'class_name' => Grades::Grade10->value,
        'password' => 'secret123',
        'is_active' => true,
    ])->assertRedirect('/admin/students');

    $student = Student::query()->where('admission_no', 'ADM-001')->firstOrFail();

    $this->put("/admin/students/{$student->id}", [
        'admission_no' => 'ADM-001',
        'first_name' => 'Alicia',
        'last_name' => 'Perera',
        'email' => 'alice@example.com',
        'phone' => '0722222222',
        'class_name' => Grades::Grade11->value,
        'is_active' => true,
    ])->assertRedirect('/admin/students');

    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'first_name' => 'Alicia',
        'class_name' => Grades::Grade11->value,
    ]);

    $this->post('/admin/assessments', [
        'title' => 'Mid Term',
        'class_name' => Grades::Grade11->value,
        'assessment_date' => '2026-06-20',
        'total_marks' => 100,
        'is_published' => true,
    ])->assertRedirect('/admin/assessments');

    $assessment = Assessment::query()->where('title', 'Mid Term')->firstOrFail();

    $this->put("/admin/assessments/{$assessment->id}", [
        'title' => 'Mid Term Updated',
        'class_name' => Grades::Grade11->value,
        'assessment_date' => '2026-06-21',
        'total_marks' => 120,
        'is_published' => true,
    ])->assertRedirect('/admin/assessments');

    $this->assertDatabaseHas('assessments', [
        'id' => $assessment->id,
        'title' => 'Mid Term Updated',
        'total_marks' => 120,
    ]);

    $this->post('/admin/results', [
        'assessment_id' => $assessment->id,
        'student_id' => $student->id,
        'marks' => 95,
        'remarks' => 'Excellent',
    ])->assertRedirect("/admin/results?assessment_id={$assessment->id}");

    $result = AssessmentResult::query()->where('assessment_id', $assessment->id)->where('student_id', $student->id)->firstOrFail();

    $this->put("/admin/results/{$result->id}", [
        'assessment_id' => $assessment->id,
        'student_id' => $student->id,
        'marks' => 97,
        'remarks' => 'Regraded',
    ])->assertRedirect("/admin/results?assessment_id={$assessment->id}");

    $this->assertDatabaseHas('assessment_results', [
        'id' => $result->id,
        'marks' => '97.00',
        'remarks' => 'Regraded',
    ]);

    $this->delete("/admin/results/{$result->id}")
        ->assertRedirect("/admin/results?assessment_id={$assessment->id}");
    $this->assertSoftDeleted('assessment_results', ['id' => $result->id]);

    $this->delete("/admin/assessments/{$assessment->id}")
        ->assertRedirect('/admin/assessments');
    $this->assertSoftDeleted('assessments', ['id' => $assessment->id]);

    $this->delete("/admin/students/{$student->id}")
        ->assertRedirect('/admin/students');
    $this->assertSoftDeleted('students', ['id' => $student->id]);
});

test('result entry prevents duplicate student assessment pairs', function () {
    $admin = createLmsUser(UserTypes::Staff->value);
    $student = Student::query()->create([
        'admission_no' => 'ADM-100',
        'first_name' => 'Nimal',
        'last_name' => 'Silva',
        'email' => 'nimal@example.com',
        'is_active' => true,
    ]);
    $assessment = Assessment::query()->create([
        'title' => 'Final Exam',
        'class_name' => Grades::Grade11->value,
        'assessment_date' => '2026-06-22',
        'total_marks' => 100,
        'is_published' => true,
    ]);

    AssessmentResult::query()->create([
        'assessment_id' => $assessment->id,
        'student_id' => $student->id,
        'marks' => 88,
    ]);

    $response = $this->actingAs($admin)->from('/admin/results/create')->post('/admin/results', [
        'assessment_id' => $assessment->id,
        'student_id' => $student->id,
        'marks' => 90,
        'remarks' => 'Duplicate attempt',
    ]);

    $response->assertRedirect('/admin/results/create');
    $response->assertSessionHasErrors('student_id');
    expect(AssessmentResult::query()->where('assessment_id', $assessment->id)->where('student_id', $student->id)->count())->toBe(1);
});

test('student pages render live database records', function () {
    $studentUser = createLmsUser(UserTypes::Student->value);
    $student = Student::query()->create([
        'user_id' => $studentUser->id,
        'admission_no' => 'ADM-200',
        'first_name' => 'Saman',
        'last_name' => 'Fernando',
        'email' => $studentUser->email,
        'class_name' => Grades::Grade9->value,
        'is_active' => true,
    ]);
    $assessment = Assessment::query()->create([
        'title' => 'Science Quiz',
        'class_name' => Grades::Grade9->value,
        'assessment_date' => '2026-06-23',
        'total_marks' => 50,
        'is_published' => true,
    ]);
    AssessmentResult::query()->create([
        'assessment_id' => $assessment->id,
        'student_id' => $student->id,
        'marks' => 42,
        'remarks' => 'Good work',
    ]);
    $otherStudentUser = createLmsUser(UserTypes::Student->value);
    $otherStudent = Student::query()->create([
        'user_id' => $otherStudentUser->id,
        'admission_no' => 'ADM-201',
        'first_name' => 'Kasun',
        'last_name' => 'Perera',
        'email' => $otherStudentUser->email,
        'class_name' => Grades::Grade9->value,
        'is_active' => true,
    ]);
    AssessmentResult::query()->create([
        'assessment_id' => $assessment->id,
        'student_id' => $otherStudent->id,
        'marks' => 48,
        'remarks' => 'Other student result',
    ]);
    $otherClassAssessment = Assessment::query()->create([
        'title' => 'History Test',
        'class_name' => Grades::Grade10->value,
        'assessment_date' => '2026-06-24',
        'total_marks' => 50,
        'is_published' => true,
    ]);
    AssessmentResult::query()->create([
        'assessment_id' => $otherClassAssessment->id,
        'student_id' => $student->id,
        'marks' => 10,
        'remarks' => 'Should stay hidden',
    ]);

    $this->actingAs($studentUser)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Science Quiz')
        ->assertSee('42')
        ->assertDontSee('History Test');

    $this->actingAs($studentUser)
        ->get('/my-results')
        ->assertOk()
        ->assertSee('Science Quiz')
        ->assertSee('Good work')
        ->assertDontSee('History Test')
        ->assertDontSee('Should stay hidden');

    $this->actingAs($studentUser)
        ->get('/my-placement')
        ->assertOk()
        ->assertSee('Science Quiz')
        ->assertSee('1')
        ->assertDontSee('History Test')
        ->assertDontSee('Kasun')
        ->assertDontSee('Other student result');
});

test('admin can filter student and assessment listings', function () {
    $admin = createLmsUser();

    Student::query()->create([
        'admission_no' => 'ADM-301',
        'first_name' => 'Anji',
        'last_name' => 'Perera',
        'class_name' => Grades::Grade8->value,
        'is_active' => true,
    ]);

    Student::query()->create([
        'admission_no' => 'ADM-302',
        'first_name' => 'Bimal',
        'last_name' => 'Silva',
        'class_name' => Grades::Grade10->value,
        'is_active' => false,
    ]);

    Assessment::query()->create([
        'title' => 'Maths Drill',
        'class_name' => Grades::Grade8->value,
        'assessment_date' => '2026-07-01',
        'total_marks' => 50,
        'is_published' => true,
    ]);

    Assessment::query()->create([
        'title' => 'Science Mock',
        'class_name' => Grades::Grade10->value,
        'assessment_date' => '2026-07-02',
        'total_marks' => 75,
        'is_published' => false,
    ]);

    $this->actingAs($admin)
        ->get('/admin/students?class_name='.urlencode(Grades::Grade8->value).'&status=active&search=Anji')
        ->assertOk()
        ->assertSee('Anji')
        ->assertDontSee('Bimal');

    $this->actingAs($admin)
        ->get('/admin/assessments?class_name='.urlencode(Grades::Grade8->value).'&status=published&search=Maths')
        ->assertOk()
        ->assertSee('Maths Drill')
        ->assertDontSee('Science Mock');
});

test('account creation emails are sent for new student and staff accounts', function () {
    Mail::fake();

    $admin = createLmsUser();

    $this->actingAs($admin)
        ->post('/admin/students', [
            'admission_no' => 'ADM-900',
            'first_name' => 'Maya',
            'last_name' => 'Silva',
            'email' => 'maya@example.com',
            'phone' => '0710000000',
            'class_name' => Grades::Grade10->value,
            'password' => 'StudentPass123',
            'is_active' => true,
        ])
        ->assertRedirect('/admin/students');

    $studentUser = User::query()->where('email', 'maya@example.com')->firstOrFail();

    expect($studentUser->hasRole(UserTypes::Student->value))->toBeTrue();
    Mail::assertSent(AccountCreatedMail::class, function (AccountCreatedMail $mail) use ($studentUser) {
        return $mail->user->is($studentUser)
            && $mail->plainPassword === 'StudentPass123'
            && $mail->role === UserTypes::Student;
    });

    $this->actingAs($admin)
        ->post('/admin/staff', [
            'name' => 'Teacher One',
            'email' => 'teacher@example.com',
            'password' => 'StaffPass123',
        ])
        ->assertRedirect('/admin/staff');

    $staffUser = User::query()->where('email', 'teacher@example.com')->firstOrFail();

    expect($staffUser->hasRole(UserTypes::Staff->value))->toBeTrue();
    Mail::assertSent(AccountCreatedMail::class, function (AccountCreatedMail $mail) use ($staffUser) {
        return $mail->user->is($staffUser)
            && $mail->plainPassword === 'StaffPass123'
            && $mail->role === UserTypes::Staff;
    });
});

test('admin dashboard shows grade aware management counts', function () {
    $admin = createLmsUser();
    createLmsUser(UserTypes::Staff->value);

    Student::query()->create([
        'admission_no' => 'ADM-401',
        'first_name' => 'Ruwan',
        'last_name' => 'Perera',
        'class_name' => Grades::Grade10->value,
        'is_active' => true,
    ]);
    Student::query()->create([
        'admission_no' => 'ADM-402',
        'first_name' => 'Nadee',
        'last_name' => 'Silva',
        'class_name' => Grades::Grade10->value,
        'is_active' => true,
    ]);
    Student::query()->create([
        'admission_no' => 'ADM-403',
        'first_name' => 'Asha',
        'last_name' => 'Fernando',
        'class_name' => Grades::Grade11->value,
        'is_active' => true,
    ]);

    $gradeTenExam = Assessment::query()->create([
        'title' => 'Grade 10 Maths',
        'class_name' => Grades::Grade10->value,
        'assessment_date' => '2026-07-03',
        'total_marks' => 100,
        'is_published' => true,
    ]);
    $sharedExam = Assessment::query()->create([
        'title' => 'General Aptitude',
        'class_name' => null,
        'assessment_date' => '2026-07-04',
        'total_marks' => 100,
        'is_published' => true,
    ]);
    $gradeElevenExam = Assessment::query()->create([
        'title' => 'Grade 11 Science',
        'class_name' => Grades::Grade11->value,
        'assessment_date' => '2026-07-05',
        'total_marks' => 100,
        'is_published' => true,
    ]);

    $gradeTenStudents = Student::query()->where('class_name', Grades::Grade10->value)->get();
    $gradeElevenStudent = Student::query()->where('class_name', Grades::Grade11->value)->firstOrFail();

    AssessmentResult::query()->create([
        'assessment_id' => $gradeTenExam->id,
        'student_id' => $gradeTenStudents[0]->id,
        'marks' => 80,
    ]);
    AssessmentResult::query()->create([
        'assessment_id' => $sharedExam->id,
        'student_id' => $gradeTenStudents[0]->id,
        'marks' => 75,
    ]);
    AssessmentResult::query()->create([
        'assessment_id' => $gradeElevenExam->id,
        'student_id' => $gradeElevenStudent->id,
        'marks' => 90,
    ]);

    $this->actingAs($admin)
        ->get('/admin?grade='.urlencode(Grades::Grade10->value))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/dashboard/Index')
            ->where('dashboard.viewer', UserTypes::Admin->value)
            ->where('dashboard.selected_grade', Grades::Grade10->value)
            ->where('dashboard.summary.total_students', 2)
            ->where('dashboard.summary.total_staff', 1)
            ->where('dashboard.summary.total_exams', 2)
            ->where('dashboard.summary.pending_results', 2)
            ->has('dashboard.overview', 2)
        );
});

test('analysis filters scope performance data by grade and exam', function () {
    $admin = createLmsUser();

    $gradeTenStudent = Student::query()->create([
        'admission_no' => 'ADM-501',
        'first_name' => 'Milan',
        'last_name' => 'Perera',
        'class_name' => Grades::Grade10->value,
        'is_active' => true,
    ]);
    $gradeElevenStudent = Student::query()->create([
        'admission_no' => 'ADM-502',
        'first_name' => 'Sachi',
        'last_name' => 'Silva',
        'class_name' => Grades::Grade11->value,
        'is_active' => true,
    ]);

    $gradeTenExam = Assessment::query()->create([
        'title' => 'Grade 10 Mock',
        'class_name' => Grades::Grade10->value,
        'assessment_date' => '2026-07-06',
        'total_marks' => 100,
        'is_published' => true,
    ]);
    $gradeElevenExam = Assessment::query()->create([
        'title' => 'Grade 11 Mock',
        'class_name' => Grades::Grade11->value,
        'assessment_date' => '2026-07-07',
        'total_marks' => 100,
        'is_published' => true,
    ]);

    AssessmentResult::query()->create([
        'assessment_id' => $gradeTenExam->id,
        'student_id' => $gradeTenStudent->id,
        'marks' => 84,
    ]);
    AssessmentResult::query()->create([
        'assessment_id' => $gradeElevenExam->id,
        'student_id' => $gradeElevenStudent->id,
        'marks' => 63,
    ]);

    $this->actingAs($admin)
        ->get('/admin/analysis?grade='.urlencode(Grades::Grade10->value)."&assessment_id={$gradeTenExam->id}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/analysis/Index')
            ->where('analysis.selected_grade', Grades::Grade10->value)
            ->where('analysis.selected_assessment_id', $gradeTenExam->id)
            ->where('analysis.summary.highest_mark', 84)
            ->where('analysis.summary.lowest_mark', 84)
            ->where('analysis.summary.average_mark', 84)
            ->where('analysis.summary.results_count', 1)
            ->where('analysis.summary.students_ranked', 1)
            ->has('analysis.ranking', 1)
        );
});
