<?php

namespace App\Traits;

use App\Models\Activity;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            if (auth()->check()) {
                Activity::log(
                    'created',
                    $model,
                    static::getCreatedTitle($model),
                    static::getCreatedDescription($model),
                    static::getCreatedIcon($model)
                );
            }
        });

        static::updated(function ($model) {
            if (auth()->check() && $model->wasChanged()) {
                Activity::log(
                    'updated',
                    $model,
                    static::getUpdatedTitle($model),
                    static::getUpdatedDescription($model),
                    static::getUpdatedIcon($model),
                    $model->getChanges()
                );
            }
        });

        static::deleted(function ($model) {
            if (auth()->check()) {
                Activity::log(
                    'deleted',
                    $model,
                    static::getDeletedTitle($model),
                    static::getDeletedDescription($model),
                    static::getDeletedIcon($model)
                );
            }
        });
    }

    protected static function getCreatedTitle($model)
    {
        $modelName = class_basename($model);
        return "New {$modelName} Created";
    }

    protected static function getCreatedDescription($model)
    {
        $modelName = class_basename($model);
        $name = $model->name ?? $model->title ?? "#{$model->id}";
        return "{$name} has been added to the system";
    }

    protected static function getCreatedIcon($model)
    {
        return match(class_basename($model)) {
            'Teacher' => 'fas fa-chalkboard-teacher',
            'Guardian' => 'fas fa-user-friends',
            'Student' => 'fas fa-graduation-cap',
            'Subject' => 'fas fa-book-open',
            'Section' => 'fas fa-layer-group',
            default => 'fas fa-plus-circle',
        };
    }

    protected static function getUpdatedTitle($model)
    {
        $modelName = class_basename($model);
        return "{$modelName} Updated";
    }

    protected static function getUpdatedDescription($model)
    {
        $modelName = class_basename($model);
        $name = $model->name ?? $model->title ?? "#{$model->id}";
        return "{$name} information has been updated";
    }

    protected static function getUpdatedIcon($model)
    {
        return match(class_basename($model)) {
            'Teacher' => 'fas fa-chalkboard-teacher',
            'Guardian' => 'fas fa-user-friends',
            'Student' => 'fas fa-graduation-cap',
            'Subject' => 'fas fa-book-open',
            'Section' => 'fas fa-layer-group',
            default => 'fas fa-edit',
        };
    }

    protected static function getDeletedTitle($model)
    {
        $modelName = class_basename($model);
        return "{$modelName} Deleted";
    }

    protected static function getDeletedDescription($model)
    {
        $modelName = class_basename($model);
        $name = $model->name ?? $model->title ?? "#{$model->id}";
        return "{$name} has been removed from the system";
    }

    protected static function getDeletedIcon($model)
    {
        return 'fas fa-trash';
    }
}
