<?php

use App\Enums\Permissions;
use App\Enums\UserTypes;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Models\User;
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
        ]);
    } else {
        $role->syncPermissions([
            Permissions::ViewDashboard->value,
            Permissions::ManageStudents->value,
            Permissions::ManageAssessments->value,
            Permissions::ManageResults->value,
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
        'class_name' => 'Grade 10',
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
        'class_name' => 'Grade 11',
        'is_active' => true,
    ])->assertRedirect('/admin/students');

    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'first_name' => 'Alicia',
        'class_name' => 'Grade 11',
    ]);

    $this->post('/admin/assessments', [
        'title' => 'Mid Term',
        'class_name' => 'Grade 11',
        'assessment_date' => '2026-06-20',
        'total_marks' => 100,
        'is_published' => true,
    ])->assertRedirect('/admin/assessments');

    $assessment = Assessment::query()->where('title', 'Mid Term')->firstOrFail();

    $this->put("/admin/assessments/{$assessment->id}", [
        'title' => 'Mid Term Updated',
        'class_name' => 'Grade 11',
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
        'class_name' => 'Grade 12',
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
        'class_name' => 'Grade 9',
        'is_active' => true,
    ]);
    $assessment = Assessment::query()->create([
        'title' => 'Science Quiz',
        'class_name' => 'Grade 9',
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
        'class_name' => 'Grade 9',
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
        'class_name' => 'Grade 10',
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
