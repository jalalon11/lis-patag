<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'teacher_id',
        'user_id',
        'birth_date',
        'gender',
        'address',
        'contact_number',
        'emergency_contact',
        'position',
        'department',
        'hire_date',
        'employment_status',
        'salary',
        'qualifications',
        'certifications'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    protected $appends = [
        'full_name',
        'first_name',
        'last_name',
        'email'
    ];

    /**
     * Get the user associated with this teacher.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacherSubjectSection()
    {
        return $this->belongsTo(TeacherSubjectSection::class);
    }

    // Accessor for full name (from related user)
    public function getFullNameAttribute()
    {
        if (!$this->user) {
            return '';
        }

        $name = $this->user->first_name;
        if ($this->user->middle_initial) {
            $name .= ' ' . $this->user->middle_initial;
        }
        $name .= ' ' . $this->user->last_name;
        if ($this->user->suffix) {
            $name .= ' ' . $this->user->suffix;
        }
        return $name;
    }

    // Accessor for first name (from related user)
    public function getFirstNameAttribute()
    {
        return $this->user?->first_name;
    }

    // Accessor for last name (from related user)
    public function getLastNameAttribute()
    {
        return $this->user?->last_name;
    }

    // Accessor for email (from related user)
    public function getEmailAttribute()
    {
        return $this->user?->email;
    }

    /**
     * Get sections where this teacher is an adviser.
     */
    public function advisedSections()
    {
        return $this->hasMany(Section::class, 'adviser_id');
    }


    /**
     * Get schedules assigned to this teacher.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }
}
