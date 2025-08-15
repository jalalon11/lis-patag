<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_subject_section_id',
        'section_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'school_year',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function teacherSubjectSection()
    {
        return $this->belongsTo(TeacherSubjectSection::class);
    }
        public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function getDayNameAttribute()
    {
        $days = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        return $days[$this->day_of_week] ?? 'Unknown';
    }

    public function getTimeRangeAttribute()
    {
        return $this->start_time . ' - ' . $this->end_time;
    }
}