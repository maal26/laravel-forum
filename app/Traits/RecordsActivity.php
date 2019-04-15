<?php

namespace App\Traits;

use App\Activity;

trait RecordsActivity
{
    public static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return;
        }

        static::getRecordableEvents()
            ->each(function ($event) {
                static::triggerEvent($event);
            });
    }

    public static function triggerEvent($event)
    {
        static::$event(function ($model) use ($event) {
            $model->recordActivity($event);
        });

        if ($event === 'deleting') {
            static::deleting(function ($model) {
                $model->activity()->delete();
            });
        }
    }

    public static function getRecordableEvents()
    {
        return isset(static::$recordableEvents)
            ? collect(static::$recordableEvents)
            : collect(['created']);
    }

    public function recordActivity($event)
    {
        $this->activity()->create([
            'type'    => $this->getActivityType($event),
            'user_id' => auth()->id(),
        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function getActivityType(string $event)
    {
        $type = strtolower(class_basename($this));

        return "{$event}_{$type}";
    }
}
