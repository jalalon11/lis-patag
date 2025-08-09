<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'model',
        'model_id',
        'title',
        'description',
        'icon',
        'user_id',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who performed the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was acted upon.
     */
    public function subject()
    {
        return $this->morphTo('model');
    }

    /**
     * Create a new activity log entry.
     */
    public static function log($type, $model, $title, $description, $icon = null, $changes = null)
    {
        return static::create([
            'type' => $type,
            'model' => get_class($model),
            'model_id' => $model->id,
            'title' => $title,
            'description' => $description,
            'icon' => $icon ?? static::getDefaultIcon($type),
            'user_id' => auth()->id(),
            'changes' => $changes,
        ]);
    }

    /**
     * Get default icon based on activity type.
     */
    protected static function getDefaultIcon($type)
    {
        return match($type) {
            'created' => 'fas fa-plus-circle',
            'updated' => 'fas fa-edit',
            'deleted' => 'fas fa-trash',
            default => 'fas fa-info-circle',
        };
    }

    /**
     * Get formatted time ago.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
