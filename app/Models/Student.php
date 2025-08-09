<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class Student extends Model
{
    use LogsActivity;
    protected $fillable = [
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birth_date',
        'gender',
        'address',
        'contact_number',
        'email',
        'parent_guardian_name',
        'parent_guardian_contact',
        'parent_guardian_email',
        'relationship_to_student',
        'guardian_id',
        'grade_level',
        'status',
        'medical_conditions',
        'emergency_contact',
        'enrollment_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'enrollment_date' => 'date',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function currentEnrollment()
    {
        return $this->enrollments()
            ->whereHas('schoolYear', function ($query) {
                $query->where('is_current', true);
            })
            ->where('status', 'Enrolled')
            ->first();
    }

    public function getFullNameAttribute(): string
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
}
