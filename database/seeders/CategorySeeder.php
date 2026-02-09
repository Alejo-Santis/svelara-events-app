<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tecnología',
                'slug' => 'tecnologia',
                'description' => 'Eventos relacionados con tecnología, programación, desarrollo de software y más',
                'icon' => 'ti-device-laptop',
                'color' => '#5D87FF',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Negocios',
                'slug' => 'negocios',
                'description' => 'Conferencias y eventos empresariales',
                'icon' => 'ti-briefcase',
                'color' => '#49BEFF',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Educación',
                'slug' => 'educacion',
                'description' => 'Talleres, cursos y eventos educativos',
                'icon' => 'ti-book',
                'color' => '#13DEB9',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Entretenimiento',
                'slug' => 'entretenimiento',
                'description' => 'Conciertos, obras de teatro, cine y más',
                'icon' => 'ti-music',
                'color' => '#FFAE1F',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Deportes',
                'slug' => 'deportes',
                'description' => 'Eventos deportivos y competiciones',
                'icon' => 'ti-ball-football',
                'color' => '#FA896B',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'Arte y Cultura',
                'slug' => 'arte-cultura',
                'description' => 'Exposiciones, galerías y eventos culturales',
                'icon' => 'ti-palette',
                'color' => '#539BFF',
                'is_active' => true,
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            $category['uuid'] = \Illuminate\Support\Str::uuid();
            Category::create($category);
        }
    }
}
