<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {

        $products = [
            [
                'name' => 'Boné',
                'quantity' => 50,
                'unity_value' => 30.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sapato',
                'quantity' => 30,
                'unity_value' => 299.40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Calça',
                'quantity' => 40,
                'unity_value' => 100.70,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Camiseta',
                'quantity' => 100,
                'unity_value' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Colar',
                'quantity' => 20,
                'unity_value' => 59.40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Óculos',
                'quantity' => 15,
                'unity_value' => 120.90,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        Product::insert($products);
    }
}