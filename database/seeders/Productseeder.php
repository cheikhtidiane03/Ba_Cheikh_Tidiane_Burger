<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $classique = Category::where('name', 'Burgers Classiques')->first();
        $special   = Category::where('name', 'Burgers Spéciaux')->first();
        $veggie    = Category::where('name', 'Burgers Végétariens')->first();
        $boissons  = Category::where('name', 'Boissons')->first();

        $products = [
            ['category_id' => $classique->id, 'name' => 'ISI Classic',    'price' => 2500, 'stock' => 50,  'description' => 'Steak haché, salade, tomate, oignon, sauce maison'],
            ['category_id' => $classique->id, 'name' => 'Double Cheese',  'price' => 3500, 'stock' => 40,  'description' => 'Double steak haché, double cheddar, cornichons'],
            ['category_id' => $classique->id, 'name' => 'Crispy Chicken', 'price' => 3000, 'stock' => 35,  'description' => 'Poulet croustillant, salade iceberg, sauce ranch'],
            ['category_id' => $special->id,   'name' => 'ISI Signature',  'price' => 7500, 'stock' => 15,  'description' => 'Wagyu 180g, foie gras, truffe, brioche maison'],
            ['category_id' => $special->id,   'name' => 'Spicy Inferno',  'price' => 4000, 'stock' => 25,  'description' => 'Steak haché, jalapeños, sauce piment, gouda fumé'],
            ['category_id' => $veggie->id,    'name' => 'Garden Burger',  'price' => 2800, 'stock' => 20,  'description' => 'Steak de lentilles, avocat, tomate, pesto'],
            ['category_id' => $boissons->id,  'name' => 'Coca-Cola 33cl', 'price' => 700,  'stock' => 100, 'description' => 'Coca-Cola bien frais'],
            ['category_id' => $boissons->id,  'name' => 'Jus Bissap',     'price' => 500,  'stock' => 80,  'description' => 'Jus de bissap fait maison'],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['slug' => Str::slug($data['name'])])
            );
        }

        //$this->command->info('✅ Produits créés : ' . count($products));
    }
}