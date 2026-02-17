<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasRoles, HasUuid, LogsActivity, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'bio',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Eventos creados por el usuario
     */
    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Eventos en los que el usuario está registrado como asistente
     */
    public function attendingEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_user')
            ->withPivot(['status', 'checked_in_at', 'checked_in_by', 'registration_date', 'cancellation_date', 'cancellation_reason', 'notes']);
    }

    /**
     * Tickets del usuario
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Pagos realizados por el usuario
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Invitaciones enviadas por el usuario
     */
    public function sentInvitations(): HasMany
    {
        return $this->hasMany(EventInvitation::class, 'invited_by');
    }

    /**
     * Invitaciones recibidas por el usuario
     */
    public function receivedInvitations(): HasMany
    {
        return $this->hasMany(EventInvitation::class, 'user_id');
    }

    // ========== SCOPES ==========

    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para usuarios verificados
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope para buscar por nombre o email
     */
    public function scopeSearch($query, ?string $search)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'ILIKE', "%{$search}%")
                ->orWhere('email', 'ILIKE', "%{$search}%");
        });
    }

    /**
     * Scope para usuarios con rol específico
     */
    public function scopeWithRole($query, string $role)
    {
        return $query->role($role);
    }

    // ========== HELPERS ==========

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Verificar si el usuario es organizador
     */
    public function isOrganizer(): bool
    {
        return $this->hasRole(['organizer', 'super_admin']);
    }

    /**
     * Verificar si el usuario es asistente
     */
    public function isAttendee(): bool
    {
        return $this->hasRole('attendee');
    }

    /**
     * Obtener el nombre completo del usuario (alias de name)
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Obtener la URL del avatar o un avatar por defecto
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/'.$this->avatar);
        }

        // Gravatar como fallback
        $hash = md5(strtolower(trim($this->email)));

        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }

    /**
     * Verificar si el usuario está asistiendo a un evento
     */
    public function isAttendingEvent(Event $event): bool
    {
        return $this->attendingEvents()
            ->where('events.id', $event->id)
            ->wherePivot('status', 'registered')
            ->exists();
    }

    /**
     * Verificar si el usuario es dueño del evento
     */
    public function ownsEvent(Event $event): bool
    {
        return $this->id === $event->user_id;
    }
}
