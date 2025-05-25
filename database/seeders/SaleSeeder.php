<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;

class SaleSeeder extends Seeder
{
    public function run()
    {
        $client = Client::firstOrCreate(
            ['cpf' => '01234567891'],
            [
                'name' => 'Test user',
                'email' => 'cliente01@exemplo.com',
                'phone' => '12 34567-8910',
                'cpf' => '01234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $product = Product::firstOrCreate(
            ['name' => 'Produto teste'],
            [
                'name' => 'Produto teste',
                'quantity' => 20,
                'unity_value' => 42,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        for ($i = 0; $i < 2; $i++) {
            $sale = Sale::create([
                'client_id' => $client->id,
                'total_amount' => 0,
            ]);

            $quantity = rand(1, 5);
            $unitPrice = $product->unity_value;
            $subtotal = $quantity * $unitPrice;

            $sale->products()->attach($product->id, [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);

            $sale->update(['total_amount' => $subtotal]);
        }
    }
}