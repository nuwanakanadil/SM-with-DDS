<?php

use App\Enums\ClassGroups;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->string('class_group', 30)->nullable()->after('class_name')->index();
            $table->unsignedSmallInteger('academic_year')->nullable()->after('class_group')->index();
            $table->string('term', 30)->nullable()->after('academic_year')->index();
            $table->string('exam_type', 60)->nullable()->after('term')->index();
        });

        DB::table('assessments')
            ->select(['id', 'assessment_date'])
            ->orderBy('id')
            ->each(function (object $assessment): void {
                $year = $assessment->assessment_date
                    ? (int) substr((string) $assessment->assessment_date, 0, 4)
                    : null;

                DB::table('assessments')->where('id', $assessment->id)->update([
                    'academic_year' => $year,
                ]);
            });

        DB::table('assessments')->select('id')->orderBy('id')->each(function (object $assessment): void {
            $groups = DB::table('assessment_results')
                ->join('students', 'students.id', '=', 'assessment_results.student_id')
                ->where('assessment_results.assessment_id', $assessment->id)
                ->whereIn('students.class_group', ClassGroups::values())
                ->distinct()
                ->pluck('students.class_group');

            if ($groups->count() === 1) {
                DB::table('assessments')->where('id', $assessment->id)->update([
                    'class_group' => $groups->first(),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropIndex(['class_group']);
            $table->dropIndex(['academic_year']);
            $table->dropIndex(['term']);
            $table->dropIndex(['exam_type']);
            $table->dropColumn(['class_group', 'academic_year', 'term', 'exam_type']);
        });
    }
};
