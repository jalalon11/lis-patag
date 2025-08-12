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
        return $this->grade_level . ' - ' . $this->section_name;
    }
}
