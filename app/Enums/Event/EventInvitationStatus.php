<?php

namespace App\Enums\Event;

enum EventInvitationStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::ACCEPTED => 'Aceptada',
            self::REJECTED => 'Rechazada',
            self::EXPIRED => 'Expirada',
        };
    }
}
