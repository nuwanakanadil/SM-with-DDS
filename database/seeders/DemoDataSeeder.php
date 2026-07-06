<?php

namespace Database\Seeders;

use App\Enums\Grades;
use App\Enums\UserTypes;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@demo.test'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
        $admin->syncRoles([UserTypes::Admin->value]);

        $staff = User::query()->updateOrCreate(
            ['email' => 'staff@demo.test'],
            [
                'name' => 'Demo Staff',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
        $staff->syncRoles([UserTypes::Staff->value]);

        $studentAUser = User::query()->updateOrCreate(
            ['email' => 'nethmi@demo.test'],
            [
                'name' => 'Nethmi Perera',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
        $studentAUser->syncRoles([UserTypes::Student->value]);

        $studentBUser = User::query()->updateOrCreate(
            ['email' => 'kasun@demo.test'],
            [
                'name' => 'Kasun Silva',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
        $studentBUser->syncRoles([UserTypes::Student->value]);

        $studentCUser = User::query()->updateOrCreate(
            ['email' => 'amandi@demo.test'],
            [
                'name' => 'Amandi Fernando',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
        $studentCUser->syncRoles([UserTypes::Student->value]);

        $studentA = Student::query()->updateOrCreate(
            ['admission_no' => 'STU-001'],
            [
                'user_id' => $studentAUser->id,
                'first_name' => 'Nethmi',
                'last_name' => 'Perera',
                'email' => $studentAUser->email,
                'phone' => '0711234567',
                'class_name' => Grades::Grade10->value,
                'is_active' => true,
            ],
        );

        $studentB = Student::query()->updateOrCreate(
            ['admission_no' => 'STU-002'],
            [
                'user_id' => $studentBUser->id,
                'first_name' => 'Kasun',
                'last_name' => 'Silva',
                'email' => $studentBUser->email,
                'phone' => '0712345678',
                'class_name' => Grades::Grade10->value,
                'is_active' => true,
            ],
        );

        $studentC = Student::query()->updateOrCreate(
            ['admission_no' => 'STU-003'],
            [
                'user_id' => $studentCUser->id,
                'first_name' => 'Amandi',
                'last_name' => 'Fernando',
                'email' => $studentCUser->email,
                'phone' => '0713456789',
                'class_name' => Grades::Grade11->value,
                'is_active' => true,
            ],
        );

        $assessmentA = Assessment::query()->updateOrCreate(
            ['title' => Grades::Grade10->value.' Mid Term', 'class_name' => Grades::Grade10->value],
            [
                'assessment_date' => '2026-06-15',
                'total_marks' => 100,
                'is_published' => true,
            ],
        );

        $assessmentB = Assessment::query()->updateOrCreate(
            ['title' => Grades::Grade11->value.' Science Quiz', 'class_name' => Grades::Grade11->value],
            [
                'assessment_date' => '2026-06-22',
                'total_marks' => 50,
                'is_published' => true,
            ],
        );

        AssessmentResult::query()->updateOrCreate(
            ['assessment_id' => $assessmentA->id, 'student_id' => $studentA->id],
            ['marks' => 91, 'remarks' => 'Excellent consistency'],
        );
        AssessmentResult::query()->updateOrCreate(
            ['assessment_id' => $assessmentA->id, 'student_id' => $studentB->id],
            ['marks' => 84, 'remarks' => 'Strong effort'],
        );
        AssessmentResult::query()->updateOrCreate(
            ['assessment_id' => $assessmentB->id, 'student_id' => $studentC->id],
            ['marks' => 44, 'remarks' => 'Well done'],
        );
    }
}
