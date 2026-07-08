<?php

namespace App\Services;

use App\Models\Assessment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssessmentService
{
    public function paginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $className = $filters['class_name'] ?? null;
        $status = $filters['status'] ?? null;
        $sort = $filters['sort'] ?? null;

        return Assessment::query()
            ->when($search, function ($query, string $term) {
                $query->where(function ($assessmentQuery) use ($term) {
                    $assessmentQuery->where('title', 'like', "%{$term}%")
                        ->orWhere('class_name', 'like', "%{$term}%");
                });
            })
            ->when($className, fn ($query, string $value) => $query->where('class_name', $value))
            ->when($status === 'published', fn ($query) => $query->where('is_published', true))
            ->when($status === 'draft', fn ($query) => $query->where('is_published', false))
            ->when($sort === 'title_asc', fn ($query) => $query->orderBy('title'))
            ->when($sort === 'title_desc', fn ($query) => $query->orderByDesc('title'))
            ->when($sort === 'date_asc', fn ($query) => $query->orderBy('assessment_date')->orderBy('id'))
            ->when($sort === 'date_desc', fn ($query) => $query->orderByDesc('assessment_date')->orderByDesc('id'))
            ->when(
                ! in_array($sort, ['title_asc', 'title_desc', 'date_asc', 'date_desc'], true),
                fn ($query) => $query->latest('assessment_date')->latest('id')
            )
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

    public function publish(Assessment $assessment): Assessment
    {
        return DB::transaction(function () use ($assessment): Assessment {
            $assessment->forceFill(['is_published' => true])->save();

            return $assessment->fresh();
        });
    }

    public function unpublish(Assessment $assessment): Assessment
    {
        return DB::transaction(function () use ($assessment): Assessment {
            $assessment->forceFill(['is_published' => false])->save();

            return $assessment->fresh();
        });
    }

    public function options(): Collection
    {
        return Assessment::query()
            ->latest('assessment_date')
            ->latest('id')
            ->get(['id', 'title', 'class_name', 'assessment_date', 'total_marks', 'is_published']);
    }
}
