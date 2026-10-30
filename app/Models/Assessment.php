<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Assessment extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'class_name',
        'class_group',
        'academic_year',
        'term',
        'exam_type',
        'assessment_date',
        'total_marks',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'assessment_date' => 'date',
            'academic_year' => 'integer',
            'total_marks' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function results(): HasMany
    {
        return $this->hasMany(AssessmentResult::class);
    }

    public function scopeRelevantToStudent(Builder $query, ?Student $student): Builder
    {
        if (! $student?->class_name || ! $student?->class_group) {
            return $query->whereKey(null);
        }

        return $query
            ->where(function (Builder $gradeQuery) use ($student): void {
                $gradeQuery->where('class_name', $student->class_name)
                    ->orWhereNull('class_name');
            })
            ->where(function (Builder $groupQuery) use ($student): void {
                $groupQuery->whereNull('class_group')
                    ->orWhere('class_group', $student->class_group);
            });
    }

    public function scopeEligibleForGroup(Builder $query, ?string $classGroup): Builder
    {
        if (! $classGroup) {
            return $query->whereNull('class_group');
        }

        return $query->where(function (Builder $groupQuery) use ($classGroup): void {
            $groupQuery->whereNull('class_group')->orWhere('class_group', $classGroup);
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }
}
