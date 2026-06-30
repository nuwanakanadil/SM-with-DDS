<?php

namespace App\Services;

use App\Models\AssessmentResult;
use Illuminate\Support\Facades\DB;

class AssessmentResultService
{
    public function saveResult(array $data): AssessmentResult
    {
        return DB::transaction(function () use ($data): AssessmentResult {
            return AssessmentResult::query()->create([
                'assessment_id' => $data['assessment_id'],
                'student_id' => $data['student_id'],
                'marks' => $data['marks'],
                'remarks' => $data['remarks'] ?? null,
            ]);
        });
    }

    public function updateResult(AssessmentResult $result, array $data): AssessmentResult
    {
        return DB::transaction(function () use ($result, $data): AssessmentResult {
            $result->fill([
                'assessment_id' => $data['assessment_id'],
                'student_id' => $data['student_id'],
                'marks' => $data['marks'],
                'remarks' => $data['remarks'] ?? null,
            ]);
            $result->save();

            return $result->fresh(['assessment', 'student']);
        });
    }
}
