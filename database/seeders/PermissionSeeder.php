<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permisos de usuarios
        $userPermissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.manage_roles',
        ];

        // Permisos de eventos
        $eventPermissions = [
            'events.view',
            'events.view_any',
            'events.create',
            'events.edit',
            'events.edit_own',
            'events.delete',
            'events.delete_own',
            'events.publish',
            'events.cancel',
            'events.manage_attendees',
        ];

        // Permisos de categorías
        $categoryPermissions = [
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
        ];

        // Permisos de tags
        $tagPermissions = [
            'tags.view',
            'tags.create',
            'tags.edit',
            'tags.delete',
        ];

        // Permisos de tickets
        $ticketPermissions = [
            'tickets.view',
            'tickets.view_own',
            'tickets.create',
            'tickets.cancel',
            'tickets.check_in',
        ];

        // Permisos de pagos
        $paymentPermissions = [
            'payments.view',
            'payments.view_own',
            'payments.process',
            'payments.refund',
        ];

        // Permisos de invitaciones
        $invitationPermissions = [
            'invitations.send',
            'invitations.view',
            'invitations.cancel',
        ];

        // Permisos de reportes
        $reportPermissions = [
            'reports.view',
            'reports.export',
        ];

        // Permisos de activity logs
        $activityLogPermissions = [
            'activity_logs.view',
            'activity_logs.export',
        ];

        // Combinar todos los permisos
        $allPermissions = array_merge(
            $userPermissions,
            $eventPermissions,
            $categoryPermissions,
            $tagPermissions,
            $ticketPermissions,
            $paymentPermissions,
            $invitationPermissions,
            $reportPermissions,
            $activityLogPermissions
        );

        // Crear permisos
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }
    }
}
