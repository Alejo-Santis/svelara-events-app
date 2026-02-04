<?php

namespace App\Enums\Event;

enum EventStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Borrador',
            self::PUBLISHED => 'Publicado',
            self::CANCELLED => 'Cancelado',
            self::COMPLETED => 'Completado',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::PUBLISHED => 'green',
            self::CANCELLED => 'red',
            self::COMPLETED => 'blue',
        };
    }
}
