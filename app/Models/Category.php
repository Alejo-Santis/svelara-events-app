<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, HasUuid, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Eventos de esta categoría
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    // ========== SCOPES ==========

    /**
     * Scope para categorías activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenar por campo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

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

    // ========== HELPERS ==========

    /**
     * Obtener la clase de icono completa
     */
    public function getIconClassAttribute(): string
    {
        return $this->icon ?: 'bi-calendar-event';
    }

    /**
     * Obtener el color hexadecimal o un color por defecto
     */
    public function getColorHexAttribute(): string
    {
        return $this->color ?: '#6366f1';
    }

    /**
     * Contar eventos activos en esta categoría
     */
    public function activeEventsCount(): int
    {
        return $this->events()
            ->where('is_published', true)
            ->where('status', 'published')
            ->count();
    }
}
