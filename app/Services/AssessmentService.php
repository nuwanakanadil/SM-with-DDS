<?php

namespace App\Services;

use App\Models\Assessment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssessmentService
{
    public function paginated(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Assessment::query()
            ->when($search, fn ($query, string $term) => $query
                ->where('title', 'like', "%{$term}%")
                ->orWhere('class_name', 'like', "%{$term}%"))
            ->latest('assessment_date')
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Assessment
    {
        return DB::transaction(fn (): Assessment => Assessment::query()->create($data));
    }

    public function update(Assessment $assessment, array $data): Assessment
    {
        return DB::transaction(function () use ($assessment, $data): Assessment {
            $assessment->update($data);

            return $assessment->fresh();
        });
    }

    public function delete(Assessment $assessment): void
    {
        DB::transaction(fn () => $assessment->delete());
    }

    public function options(): Collection
    {
        return Assessment::query()
            ->latest('assessment_date')
            ->latest('id')
            ->get(['id', 'title', 'class_name', 'assessment_date', 'total_marks', 'is_published']);
    }
}
