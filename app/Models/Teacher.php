<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class Teacher extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'teacher_id',
        'birth_date',
        'gender',
        'address',
        'contact_number',
        'email',
        'emergency_contact',
        'position',
        'department',
        'hire_date',
        'employment_status',
        'qualifications',
        'certifications'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    // Accessor for full name
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }
        return $name;
    }

    /**
     * Get sections where this teacher is an adviser.
     */
    public function advisedSections()
    {
        return $this->hasMany(Section::class, 'adviser_id');
    }
    public function teacher()
    {
        return $this->BelongsTo(User::class, 'teacher_id');
    }

    /**
     * Get schedules assigned to this teacher.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }
}
