<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes, HasUuid, LogsActivity;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'short_description',
        'start_date',
        'end_date',
        'timezone',
        'location',
        'venue_name',
        'is_online',
        'online_url',
        'is_public',
        'is_published',
        'is_free',
        'price',
        'currency',
        'capacity',
        'allow_waitlist',
        'registration_deadline',
        'featured_image',
        'status',
        'views_count',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'is_online' => 'boolean',
        'is_public' => 'boolean',
        'is_published' => 'boolean',
        'is_free' => 'boolean',
        'allow_waitlist' => 'boolean',
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'views_count' => 'integer',
    ];

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    // ========== RELACIONES ==========

    /**
     * Usuario creador del evento
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Categoría del evento
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Asistentes del evento
     */
    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withPivot(['status', 'checked_in_at', 'checked_in_by', 'registration_date', 'cancellation_date', 'cancellation_reason', 'notes'])
            ->withTimestamps();
    }

    /**
     * Etiquetas del evento
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'event_tag');
    }

    /**
     * Tickets del evento
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Imágenes del evento
     */
    public function images(): HasMany
    {
        return $this->hasMany(EventImage::class);
    }

    /**
     * Invitaciones del evento
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(EventInvitation::class);
    }

    /**
     * Pagos del evento
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // ========== SCOPES ==========

    /**
     * Scope para eventos publicados
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('status', 'published');
    }

    /**
     * Scope para eventos públicos
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope para eventos privados
     */
    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    /**
     * Scope para eventos gratuitos
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Scope para eventos de pago
     */
    public function scopePaid($query)
    {
        return $query->where('is_free', false);
    }

    /**
     * Scope para eventos próximos
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    /**
     * Scope para eventos pasados
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }

    /**
     * Scope para eventos en curso
     */
    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * Scope para buscar por título o descripción
     */
    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'ILIKE', "%{$search}%")
              ->orWhere('description', 'ILIKE', "%{$search}%");
        });
    }

    /**
     * Scope para filtrar por categoría
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope para filtrar por rango de fechas
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }

    /**
     * Scope para ordenar por proximidad
     */
    public function scopeOrderByNearest($query)
    {
        return $query->orderBy('start_date', 'asc');
    }

    /**
     * Scope para eventos populares (más vistas)
     */
    public function scopePopular($query, int $limit = 10)
    {
        return $query->orderBy('views_count', 'desc')->limit($limit);
    }

    /**
     * Scope para eventos con disponibilidad
     */
    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('capacity')
              ->orWhereRaw('capacity > (SELECT COUNT(*) FROM event_user WHERE event_id = events.id AND status = ?)', ['registered']);
        });
    }

    // ========== HELPERS ==========

    /**
     * Obtener URL de la imagen destacada
     */
    public function getFeaturedImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }

        return asset('images/default-event.jpg');
    }

    /**
     * Verificar si el evento está lleno
     */
    public function isFull(): bool
    {
        if (!$this->capacity) {
            return false;
        }

        return $this->registeredAttendeesCount() >= $this->capacity;
    }

    /**
     * Contar asistentes registrados
     */
    public function registeredAttendeesCount(): int
    {
        return $this->attendees()
            ->wherePivot('status', 'registered')
            ->count();
    }

    /**
     * Contar asistentes que asistieron (check-in)
     */
    public function attendedAttendeesCount(): int
    {
        return $this->attendees()
            ->wherePivot('status', 'attended')
            ->count();
    }

    /**
     * Obtener espacios disponibles
     */
    public function availableSpots(): ?int
    {
        if (!$this->capacity) {
            return null;
        }

        return max(0, $this->capacity - $this->registeredAttendeesCount());
    }

    /**
     * Verificar si el evento ha iniciado
     */
    public function hasStarted(): bool
    {
        return now()->greaterThanOrEqualTo($this->start_date);
    }

    /**
     * Verificar si el evento ha finalizado
     */
    public function hasEnded(): bool
    {
        return now()->greaterThan($this->end_date);
    }

    /**
     * Verificar si el evento está en curso
     */
    public function isOngoing(): bool
    {
        return $this->hasStarted() && !$this->hasEnded();
    }

    /**
     * Verificar si aún acepta registros
     */
    public function acceptsRegistrations(): bool
    {
        if ($this->hasStarted()) {
            return false;
        }

        if ($this->registration_deadline && now()->greaterThan($this->registration_deadline)) {
            return false;
        }

        if ($this->isFull() && !$this->allow_waitlist) {
            return false;
        }

        return true;
    }

    /**
     * Incrementar contador de vistas
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Obtener duración del evento en horas
     */
    public function getDurationInHours(): float
    {
        return $this->start_date->diffInHours($this->end_date);
    }

    /**
     * Obtener duración formateada
     */
    public function getFormattedDuration(): string
    {
        $hours = $this->getDurationInHours();
        
        if ($hours < 1) {
            return $this->start_date->diffInMinutes($this->end_date) . ' minutos';
        }
        
        if ($hours < 24) {
            return round($hours, 1) . ' horas';
        }
        
        return $this->start_date->diffInDays($this->end_date) . ' días';
    }
}
