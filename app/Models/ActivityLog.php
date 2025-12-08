<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    protected $fillable = [
        'log_name',
        'description',
        'causer_id',
        'causer_type',
        'subject_type',
        'subject_id',
        'event',
        'properties',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'batch_uuid',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Usuario que causó la acción
     */
    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    /**
     * Entidad afectada (polimórfica)
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope para filtrar por nombre de log
     */
    public function scopeInLog($query, string|array $logNames)
    {
        if (!is_array($logNames)) {
            $logNames = [$logNames];
        }

        return $query->whereIn('log_name', $logNames);
    }

    /**
     * Scope para filtrar por usuario causante
     */
    public function scopeCausedBy($query, Model $causer)
    {
        return $query
            ->where('causer_type', $causer->getMorphClass())
            ->where('causer_id', $causer->getKey());
    }

    /**
     * Scope para filtrar por entidad afectada
     */
    public function scopeForSubject($query, Model $subject)
    {
        return $query
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }

    /**
     * Scope para filtrar por tipo de evento
     */
    public function scopeForEvent($query, string|array $events)
    {
        if (!is_array($events)) {
            $events = [$events];
        }

        return $query->whereIn('event', $events);
    }

    /**
     * Scope para filtrar por batch UUID
     */
    public function scopeInBatch($query, string $batchUuid)
    {
        return $query->where('batch_uuid', $batchUuid);
    }

    /**
     * Método estático para crear un log fácilmente
     */
    public static function log(?string $logName = null): ActivityLogBuilder
    {
        return new ActivityLogBuilder(new static, $logName);
    }

    /**
     * Obtener los cambios del log
     */
    public function getChanges(): array
    {
        return $this->properties['attributes'] ?? [];
    }

    /**
     * Obtener los valores anteriores
     */
    public function getOldValues(): array
    {
        return $this->properties['old'] ?? [];
    }
}

/**
 * Builder para crear logs de forma fluida
 */
class ActivityLogBuilder
{
    protected ActivityLog $log;
    protected array $properties = [];

    public function __construct(ActivityLog $log, ?string $logName = null)
    {
        $this->log = $log;
        $this->log->log_name = $logName;
        $this->log->batch_uuid = (string) Str::uuid();

        // Capturar contexto de la petición automáticamente
        if (request()) {
            $this->log->ip_address = request()->ip();
            $this->log->user_agent = request()->userAgent();
            $this->log->url = request()->fullUrl();
            $this->log->method = request()->method();
        }
    }

    public function performedOn(Model $model): self
    {
        $this->log->subject_type = $model->getMorphClass();
        $this->log->subject_id = $model->getKey();

        return $this;
    }

    public function causedBy(?Model $causer): self
    {
        if ($causer) {
            $this->log->causer_type = $causer->getMorphClass();
            $this->log->causer_id = $causer->getKey();
        }

        return $this;
    }

    public function causedByCurrentUser(): self
    {
        $user = Auth::check() ? Auth::user() : null;
        return $this->causedBy($user);
    }

    public function withProperties(array $properties): self
    {
        $this->properties = array_merge($this->properties, $properties);

        return $this;
    }

    public function event(string $event): self
    {
        $this->log->event = $event;

        return $this;
    }

    public function description(string $description): self
    {
        $this->log->description = $description;

        return $this;
    }

    public function log(): ActivityLog
    {
        $this->log->properties = $this->properties;
        $this->log->save();

        return $this->log;
    }
}
