<?php

namespace App\Services;

use App\Models\AssessmentResult;
use Illuminate\Support\Facades\DB;

class AssessmentResultService
{
    public function saveResult(array $data): AssessmentResult
    {
        return DB::transaction(function () use ($data): AssessmentResult {
            /** @var AssessmentResult|null $result */
            $result = AssessmentResult::withTrashed()
                ->where('assessment_id', $data['assessment_id'])
                ->where('student_id', $data['student_id'])
                ->first();

            if ($result) {
                if ($result->trashed()) {
                    $result->restore();
                }

                $result->fill([
                    'marks' => $data['marks'],
                    'remarks' => $data['remarks'] ?? null,
                ])->save();

                return $result->fresh(['assessment', 'student']);
            }

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
