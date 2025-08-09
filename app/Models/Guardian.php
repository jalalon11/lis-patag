<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsActivity;

class Guardian extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'relationship',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the students associated with this guardian.
     */
    public function students()
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
