<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EventInvitation extends Model
{
    use HasFactory, HasUuid, LogsActivity;

    protected $fillable = [
        'event_id',
        'email',
        'token',
        'invited_by',
        'user_id',
        'status',
        'sent_at',
        'accepted_at',
        'expires_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            if (empty($invitation->token)) {
                $invitation->token = self::generateToken();
            }
        });
    }

    // ========== RELACIONES ==========

    /**
     * Evento de la invitación
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Usuario que envió la invitación
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Usuario invitado (si se registró)
     */
    public function invitee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ========== SCOPES ==========

    /**
     * Scope para invitaciones pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para invitaciones aceptadas
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope para invitaciones rechazadas
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope para invitaciones expiradas
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'expired')
              ->orWhere(function ($q2) {
                  $q2->where('status', 'pending')
                     ->where('expires_at', '<', now());
              });
        });
    }

    /**
     * Scope para invitaciones válidas
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'pending')
            ->where('expires_at', '>', now());
    }

    /**
     * Scope para buscar por email
     */
    public function scopeForEmail($query, string $email)
    {
        return $query->where('email', $email);
    }

    // ========== HELPERS ==========

    /**
     * Generar token único
     */
    public static function generateToken(): string
    {
        do {
            $token = Str::random(40);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Verificar si la invitación es válida
     */
    public function isValid(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        if (now()->greaterThan($this->expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Verificar si la invitación ha expirado
     */
    public function hasExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    /**
     * Aceptar invitación
     */
    public function accept(?User $user = null): void
    {
        $data = [
            'status' => 'accepted',
            'accepted_at' => now(),
        ];

        if ($user) {
            $data['user_id'] = $user->id;
        }

        $this->update($data);
    }

    /**
     * Rechazar invitación
     */
    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }

    /**
     * Marcar como expirada
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Obtener URL de aceptación
     */
    public function getAcceptUrlAttribute(): string
    {
        return route('invitations.accept', $this->token);
    }
}
