<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventImage extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'event_id',
        'path',
        'filename',
        'mime_type',
        'size',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'size' => 'integer',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    // ========== RELACIONES ==========

    /**
     * Evento de la imagen
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    // ========== SCOPES ==========

    /**
     * Scope para imágenes destacadas
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope para ordenar por campo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ========== HELPERS ==========

    /**
     * Obtener URL completa de la imagen
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Obtener tamaño legible
     */
    public function getReadableSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
