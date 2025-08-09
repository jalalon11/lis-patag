<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birth_date',
        'gender',
        'address',
        'contact_number',
        'email',
        'position',
        'department',
        'hire_date',
        'status',
        'emergency_contact',
        'qualifications'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
    ];

    /**
     * Get the teacher's full name.
     */
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

    /**
     * Get schedules assigned to this teacher.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }
}
