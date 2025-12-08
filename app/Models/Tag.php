<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Eventos con esta etiqueta
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_tag');
    }

    // ========== SCOPES ==========

    /**
     * Scope para buscar por nombre
     */
    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where('name', 'ILIKE', "%{$search}%");
    }

    /**
     * Scope para tags populares (con más eventos)
     */
    public function scopePopular($query, int $limit = 10)
    {
        return $query->withCount('events')
            ->orderBy('events_count', 'desc')
            ->limit($limit);
    }

    // ========== HELPERS ==========

    /**
     * Contar eventos con esta etiqueta
     */
    public function eventsCount(): int
    {
        return $this->events()->count();
    }
}
