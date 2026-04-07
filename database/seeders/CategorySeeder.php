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
            ['name' => 'Categorie Burgers ISI1 ',  'description' => 'Description de la catégorie Burgers ISI1'],
            ['name' => 'Categorie Burgers ISI2',  'description' => 'Description de la catégorie Burgers ISI2'],
            ['name' => 'Categorie Burgers ISI3',  'description' => 'Description de la catégorie Burgers ISI3'],
            ['name' => 'Categorie Burgers ISI4',  'description' => 'Description de la catégorie Burgers ISI4'],
            ['name' => 'Categorie Burgers ISI5',  'description' => 'Description de la catégorie Burgers ISI5'],
            ['name' => 'Categorie Burgers ISI6',  'description' => 'Description de la catégorie Burgers ISI6'],
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

        $this->command->info('Catégories créées : ' . count($categories));
    }
}