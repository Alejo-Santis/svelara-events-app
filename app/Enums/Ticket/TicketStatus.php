<?php

namespace App\Enums\Ticket;

enum TicketStatus: string
{

    case ACTIVE = 'active';
    case USED = 'used';
    case CANCELLED = 'cancelled';
    case REFUNDERD = 'refunded';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Activo',
            self::USED => 'Usado',
            self::CANCELLED => 'Cancelado',
            self::REFUNDERD => 'Reembolsado',
            self::EXPIRED => 'Expirado',
        };
    }
}
