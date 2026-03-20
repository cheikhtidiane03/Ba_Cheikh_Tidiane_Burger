<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $classique  = Category::where('name', 'Burgers Classiques')->first();
        $special    = Category::where('name', 'Burgers Spéciaux')->first();
        $veggie     = Category::where('name', 'Burgers Végétariens')->first();
        $boissons   = Category::where('name', 'Boissons')->first();

        $products = [
            // Burgers classiques
            [
                'category_id' => $classique->id,
                'name'        => 'ISI Classic',
                'description' => 'Steak haché, salade, tomate, oignon, sauce maison',
                'price'       => 2500,
                'stock'       => 50,
            ],
            [
                'category_id' => $classique->id,
                'name'        => 'Double Cheese',
                'description' => 'Double steak haché, double cheddar, cornichons, moutarde',
                'price'       => 3500,
                'stock'       => 40,
            ],
            [
                'category_id' => $classique->id,
                'name'        => 'Crispy Chicken',
                'description' => 'Poulet croustillant, salade iceberg, sauce ranch',
                'price'       => 3000,
                'stock'       => 35,
            ],

            // Burgers spéciaux
            [
                'category_id' => $special->id,
                'name'        => 'ISI Signature',
                'description' => 'Wagyu 180g, foie gras, truffe, brioche maison',
                'price'       => 7500,
                'stock'       => 15,
            ],
            [
                'category_id' => $special->id,
                'name'        => 'Spicy Inferno',
                'description' => 'Steak haché, jalapeños, sauce piment, gouda fumé',
                'price'       => 4000,
                'stock'       => 25,
            ],

            // Végétariens
            [
                'category_id' => $veggie->id,
                'name'        => 'Garden Burger',
                'description' => 'Steak de lentilles, avocat, tomate, pesto',
                'price'       => 2800,
                'stock'       => 20,
            ],

            // Boissons
            [
                'category_id' => $boissons->id,
                'name'        => 'Coca-Cola 33cl',
                'description' => 'Coca-Cola bien frais',
                'price'       => 700,
                'stock'       => 100,
            ],
            [
                'category_id' => $boissons->id,
                'name'        => 'Jus Bissap',
                'description' => 'Jus de bissap fait maison, sucré à la canne',
                'price'       => 500,
                'stock'       => 80,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        $this->command->info('✅ Produits créés : ' . count($products));
    }
}