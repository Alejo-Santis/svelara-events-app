<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role\RoleName;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Admin Usuario',
            'email' => 'admin@events.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole(RoleName::SUPER_ADMIN->value);
        $this->command->info('✅ Admin user created: admin@events.com / password');

        // Create Organizer Users
        $organizer1 = User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Carlos Organizador',
            'email' => 'organizer@events.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $organizer1->assignRole(RoleName::ORGANIZER->value);

        $organizer2 = User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'María Events',
            'email' => 'maria@events.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $organizer2->assignRole(RoleName::ORGANIZER->value);

        $this->command->info('✅ Organizer users created');

        // Create Attendee Users
        $attendees = [
            ['name' => 'Juan Pérez', 'email' => 'juan@example.com'],
            ['name' => 'Ana García', 'email' => 'ana@example.com'],
            ['name' => 'Pedro López', 'email' => 'pedro@example.com'],
            ['name' => 'Laura Martínez', 'email' => 'laura@example.com'],
            ['name' => 'Diego Rodríguez', 'email' => 'diego@example.com'],
        ];

        foreach ($attendees as $attendeeData) {
            $attendee = User::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'name' => $attendeeData['name'],
                'email' => $attendeeData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
            $attendee->assignRole(RoleName::ATTENDEE->value);
        }

        $this->command->info('✅ Attendee users created');
        $this->command->info('📧 All users password: password');
    }
}
