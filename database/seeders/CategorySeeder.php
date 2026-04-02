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
            ['name' => 'Burgers Classiques',  'description' => 'Nos incontournables burgers traditionnels'],
            ['name' => 'Burgers Spéciaux',    'description' => 'Créations exclusives du chef'],
            ['name' => 'Burgers Végétariens', 'description' => 'Burgers sans viande, 100% savoureux'],
            ['name' => 'Menus',               'description' => 'Burger + frites + boisson'],
            ['name' => 'Boissons',            'description' => 'Sodas, jus et boissons fraîches'],
            ['name' => 'Desserts',            'description' => 'Pour finir en beauté'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                [
                    'slug'        => Str::slug($cat['name']),
                    'description' => $cat['description'],
                ]
            );
        }

        $this->command->info('✅ Catégories créées : ' . count($categories));
    }
}