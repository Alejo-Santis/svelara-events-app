<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No hay usuarios. Ejecuta UserSeeder primero.');
            return;
        }

        $events = [
            [
                'title' => 'Conferencia Laravel Colombia 2026',
                'description' => '<p>Únete a la conferencia de Laravel más grande de Colombia. Aprende de los mejores desarrolladores y conecta con la comunidad.</p><p>Incluye talleres prácticos, charlas inspiradoras y networking.</p>',
                'short_description' => 'La conferencia de Laravel más grande de Colombia con talleres y networking',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(32),
                'location' => 'Centro de Convenciones Gonzalo Jiménez de Quesada, Bogotá',
                'venue_name' => 'Centro de Convenciones',
                'is_online' => false,
                'is_public' => true,
                'is_published' => true,
                'is_free' => false,
                'price' => 150000,
                'capacity' => 500,
                'status' => 'published',
                'views_count' => 1245,
            ],
            [
                'title' => 'Workshop: Introducción a Svelte 5',
                'description' => '<p>Aprende los fundamentos de Svelte 5 y sus nuevas runes en este taller práctico.</p><p>Requisitos: Conocimientos básicos de JavaScript.</p>',
                'short_description' => 'Taller práctico de Svelte 5 para principiantes',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(15)->addHours(4),
                'is_online' => true,
                'online_url' => 'https://meet.google.com/xxx-xxxx-xxx',
                'is_public' => true,
                'is_published' => true,
                'is_free' => true,
                'capacity' => 100,
                'status' => 'published',
                'views_count' => 856,
            ],
            [
                'title' => 'Hackathon Innovation 2026',
                'description' => '<p>48 horas de programación intensa. Forma tu equipo y construye algo increíble.</p><p>Premios en efectivo y oportunidades laborales.</p>',
                'short_description' => '48 horas de programación con premios increíbles',
                'start_date' => Carbon::now()->addDays(45),
                'end_date' => Carbon::now()->addDays(47),
                'location' => 'Universidad Nacional de Colombia, Bogotá',
                'venue_name' => 'Auditorio Principal UNAL',
                'is_online' => false,
                'is_public' => true,
                'is_published' => true,
                'is_free' => true,
                'capacity' => 200,
                'status' => 'published',
                'views_count' => 2103,
            ],
            [
                'title' => 'Concierto Sinfónico Nacional',
                'description' => '<p>Una noche mágica con la Orquesta Sinfónica Nacional de Colombia.</p>',
                'short_description' => 'Noche mágica con la Orquesta Sinfónica Nacional',
                'start_date' => Carbon::now()->addDays(20),
                'end_date' => Carbon::now()->addDays(20)->addHours(3),
                'location' => 'Teatro Colón, Bogotá',
                'venue_name' => 'Teatro Colón',
                'is_online' => false,
                'is_public' => true,
                'is_published' => true,
                'is_free' => false,
                'price' => 80000,
                'capacity' => 800,
                'status' => 'published',
                'views_count' => 567,
            ],
            [
                'title' => 'Maratón Bogotá 2026',
                'description' => '<p>Participa en la maratón más importante de la ciudad. 42km de pura adrenalina.</p>',
                'short_description' => 'La maratón más importante de Bogotá',
                'start_date' => Carbon::now()->addDays(60),
                'end_date' => Carbon::now()->addDays(60)->addHours(6),
                'location' => 'Parque Simón Bolívar, Bogotá',
                'venue_name' => 'Parque Simón Bolívar',
                'is_online' => false,
                'is_public' => true,
                'is_published' => true,
                'is_free' => false,
                'price' => 120000,
                'capacity' => 5000,
                'status' => 'published',
                'views_count' => 3421,
            ],
            [
                'title' => 'Exposición Arte Digital',
                'description' => '<p>Explora el futuro del arte con nuestra exposición de arte digital y NFTs.</p>',
                'short_description' => 'Exposición de arte digital y NFTs',
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(17),
                'location' => 'Museo de Arte Moderno, Bogotá',
                'venue_name' => 'MAMBO',
                'is_online' => false,
                'is_public' => true,
                'is_published' => true,
                'is_free' => true,
                'status' => 'published',
                'views_count' => 432,
            ],
            [
                'title' => 'Cumbre de Emprendimiento 2026',
                'description' => '<p>Conecta con inversionistas, mentores y otros emprendedores.</p>',
                'short_description' => 'Cumbre de emprendimiento con inversionistas y mentores',
                'start_date' => Carbon::now()->addDays(25),
                'end_date' => Carbon::now()->addDays(25)->addHours(8),
                'location' => 'Centro Empresarial Tequendama, Bogotá',
                'venue_name' => 'Centro Empresarial Tequendama',
                'is_online' => false,
                'is_public' => true,
                'is_published' => true,
                'is_free' => false,
                'price' => 200000,
                'capacity' => 300,
                'status' => 'published',
                'views_count' => 1876,
            ],
            [
                'title' => 'Curso: Fotografía para Principiantes',
                'description' => '<p>Aprende los fundamentos de la fotografía en 4 semanas.</p>',
                'short_description' => 'Curso de fotografía básica de 4 semanas',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(33),
                'is_online' => true,
                'online_url' => 'https://zoom.us/j/xxx',
                'is_public' => true,
                'is_published' => true,
                'is_free' => false,
                'price' => 350000,
                'capacity' => 30,
                'status' => 'published',
                'views_count' => 234,
            ],
        ];

        foreach ($events as $eventData) {
            // Assign random category
            $category = $categories->random();
            $eventData['category_id'] = $category->id;

            // Assign random organizer
            $eventData['user_id'] = $users->random()->id;

            // Generate slug
            $eventData['slug'] = Str::slug($eventData['title']) . '-' . Str::random(4);
            $eventData['uuid'] = Str::uuid();

            Event::create($eventData);
        }

        $this->command->info('✅ Created ' . count($events) . ' events');
    }
}
