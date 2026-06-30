<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Assessment extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'class_name',
        'assessment_date',
        'total_marks',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'assessment_date' => 'date',
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
        if (! $student?->class_name) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('class_name', $student->class_name);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }
}
