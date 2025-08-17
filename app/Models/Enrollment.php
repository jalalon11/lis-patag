<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'section_id',
        'school_year_id',
        'enrollment_date',
        'status',
        'enrollment_type',
        'date_dropped',
        'reason_for_status_change',
        'remarks',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'date_dropped' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
