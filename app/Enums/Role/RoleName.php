<?php

namespace App\Enums\Role;

enum RoleName: string
{
    case SUPER_ADMIN = 'super_admin';
    case ORGANIZER = 'organizer';
    case ATTENDEE = 'attendee';
    case GUEST = 'guest';

    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Administrador',
            self::ORGANIZER => 'Organizador',
            self::ATTENDEE => 'Asistente',
            self::GUEST => 'Invitado',
        };
    }
}
