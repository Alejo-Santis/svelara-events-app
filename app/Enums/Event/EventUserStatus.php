<?php

namespace App\Enums\Event;

enum EventUserStatus: string
{
    case REGISTERED = 'registered';
    case CANCELLED = 'cancelled';
    case ATTENDED = 'attended';
    case WAITLIST = 'waitlist';

    public function label(): string
    {
        return match($this) {
            self::REGISTERED => 'Registrado',
            self::CANCELLED => 'Cancelado',
            self::ATTENDED => 'Asistió',
            self::WAITLIST => 'Lista de espera',
        };
    }
}
