<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
        'section_name',
        'grade_level',
        'school_year_id',
        'adviser_id',
        'capacity',
        'current_enrollment',
        'room_number',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }
    public function adviser(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'adviser_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Enrollment::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->getGradeLevelDisplayAttribute() . ' - ' . $this->section_name;
    }

    public function getGradeLevelDisplayAttribute(): string
    {
        $gradeMap = [
            0 => 'Kindergarten',
            1 => 'Grade 1',
            2 => 'Grade 2',
            3 => 'Grade 3',
            4 => 'Grade 4',
            5 => 'Grade 5',
            6 => 'Grade 6'
        ];

        // Handle both integer and string grade levels
        if (is_numeric($this->grade_level)) {
            return $gradeMap[(int)$this->grade_level] ?? 'Unknown';
        }
        
        return $this->grade_level; // Return as-is if already a string
    }
}
