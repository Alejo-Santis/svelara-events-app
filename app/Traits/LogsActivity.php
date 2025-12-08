<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot del trait
     */
    protected static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            $model->logActivity('created', 'Creado');
        });

        static::updated(function (Model $model) {
            if ($model->wasChanged() && !$model->wasRecentlyCreated) {
                $model->logActivity('updated', 'Actualizado');
            }
        });

        static::deleted(function (Model $model) {
            $model->logActivity('deleted', 'Eliminado');
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function (Model $model) {
                $model->logActivity('restored', 'Restaurado');
            });
        }
    }

    /**
     * Registrar actividad
     */
    public function logActivity(string $event, ?string $description = null): ActivityLog
    {
        $logName = $this->getLogNameForEvent($event);
        $description = $description ?? $this->getDescriptionForEvent($event);

        $properties = [];

        // Si fue actualizado, guardar los cambios
        if ($event === 'updated' && method_exists($this, 'getChanges')) {
            $properties['attributes'] = $this->getChanges();
            $properties['old'] = $this->getOriginal();
        }

        // Si fue creado, guardar los atributos
        if ($event === 'created') {
            $properties['attributes'] = $this->getAttributes();
        }

        return ActivityLog::log($logName)
            ->performedOn($this)
            ->causedByCurrentUser()
            ->event($event)
            ->description($description)
            ->withProperties($properties)
            ->log();
    }

    /**
     * Obtener el nombre del log para el evento
     */
    protected function getLogNameForEvent(string $event): string
    {
        // Puedes personalizar esto en tu modelo
        if (method_exists($this, 'getActivityLogName')) {
            return $this->getActivityLogName();
        }

        return strtolower(class_basename($this));
    }

    /**
     * Obtener descripción para el evento
     */
    protected function getDescriptionForEvent(string $event): string
    {
        $modelName = class_basename($this);
        $translations = [
            'created' => "{$modelName} creado",
            'updated' => "{$modelName} actualizado",
            'deleted' => "{$modelName} eliminado",
            'restored' => "{$modelName} restaurado",
        ];

        return $translations[$event] ?? "{$modelName} - {$event}";
    }

    /**
     * Relación con los logs de actividad
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Obtener el último log de actividad
     */
    public function lastActivityLog()
    {
        return $this->morphOne(ActivityLog::class, 'subject')->latestOfMany();
    }

    /**
     * Log manual de actividad personalizada
     */
    public function logCustomActivity(string $event, string $description, array $properties = []): ActivityLog
    {
        return ActivityLog::log($this->getLogNameForEvent($event))
            ->performedOn($this)
            ->causedByCurrentUser()
            ->event($event)
            ->description($description)
            ->withProperties($properties)
            ->log();
    }
}
