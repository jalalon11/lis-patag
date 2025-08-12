<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class Guardian extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'phone',
        'relationship',
        'address',
    ];

    protected $appends = [
        'full_name',
        'first_name',
        'last_name',
        'email'
    ];

    /**
     * Get the user associated with this guardian.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

    // Accessor for name (from related user) - for backward compatibility
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    /**
     * Get the students associated with this guardian.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'guardian_id');
    }

    /**
     * Get the count of students for this guardian.
     */
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    /**
     * Get the count of active students for this guardian.
     */
    public function getActiveStudentsCountAttribute()
    {
        return $this->students()->where('status', 'active')->count();
    }

    /**
     * Get the count of sections through students.
     */
    public function getSectionsCountAttribute()
    {
        return $this->students()->whereHas('section')->distinct('section_id')->count();
    }
}
