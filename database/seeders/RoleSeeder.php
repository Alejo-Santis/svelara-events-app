<?php

namespace Database\Seeders;

use App\Enums\Role\RoleName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles del sistema
        $roles = [
            [
                'name' => RoleName::SUPER_ADMIN,
                'guard_name' => 'web',
            ],
            [
                'name' => RoleName::ORGANIZER,
                'guard_name' => 'web',
            ],
            [
                'name' => RoleName::ATTENDEE,
                'guard_name' => 'web',
            ],
            [
                'name' => RoleName::GUEST,
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
