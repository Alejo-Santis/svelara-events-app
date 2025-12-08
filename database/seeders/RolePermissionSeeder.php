<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin - Todos los permisos
        $superAdmin = Role::findByName('super_admin');
        $superAdmin->givePermissionTo(Permission::all());

        // Organizer - Gestión de eventos propios
        $organizer = Role::findByName('organizer');
        $organizer->givePermissionTo([
            // Eventos
            'events.view',
            'events.view_any',
            'events.create',
            'events.edit_own',
            'events.delete_own',
            'events.publish',
            'events.cancel',
            'events.manage_attendees',

            // Categorías y tags (solo ver)
            'categories.view',
            'tags.view',

            // Tickets
            'tickets.view',
            'tickets.create',
            'tickets.cancel',
            'tickets.check_in',

            // Pagos
            'payments.view',
            'payments.process',
            'payments.refund',

            // Invitaciones
            'invitations.send',
            'invitations.view',
            'invitations.cancel',

            // Reportes
            'reports.view',
            'reports.export',
        ]);

        // Attendee - Asistente a eventos
        $attendee = Role::findByName('attendee');
        $attendee->givePermissionTo([
            // Eventos
            'events.view',
            'events.view_any',

            // Categorías y tags
            'categories.view',
            'tags.view',

            // Tickets propios
            'tickets.view_own',

            // Pagos propios
            'payments.view_own',
        ]);

        // Guest - Solo lectura básica
        $guest = Role::findByName('guest');
        $guest->givePermissionTo([
            // Eventos públicos
            'events.view',
            'events.view_any',

            // Categorías y tags
            'categories.view',
            'tags.view',
        ]);
    }
}
