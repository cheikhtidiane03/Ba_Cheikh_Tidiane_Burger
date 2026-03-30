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
            ['name' => 'Burgers',  'description' => 'Categorie principale pour tous les burgers classiques'],
            ['name' => 'Burgers',  'description' => 'Categorie principale pour tous les burgers classiques'],
            ['name' => 'Burgers',  'description' => 'Categorie principale pour tous les burgers classiques'],
            ['name' => 'Burgers',  'description' => 'Categorie principale pour tous les burgers classiques'],
            ['name' => 'Burgers',  'description' => 'Categorie principale pour tous les burgers classiques'],
            ['name' => 'Burgers',  'description' => 'Categorie principale pour tous les burgers classiques'],
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

        //$this->command->info('Catégories créées : ' . count($categories));
    }
}