<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'subject_code',
        'subject_name',
        'description',
        'grade_levels',
        'units',
        'subject_type',
        'curriculum_year',
        'is_active',
    ];

    protected $casts = [
        'grade_levels' => 'array',
        'is_active' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
    public function teacherSubjectSection()
    {
        return $this->hasMany(teacherSubjectSection::class);
    }
}
