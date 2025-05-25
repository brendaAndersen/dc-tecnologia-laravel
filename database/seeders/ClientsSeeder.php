<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::firstOrCreate(
            ['email' => 'cliente@exemplo.com'],
            [
                'name' => 'Brenda',
                'email' => 'cliente@exemplo.com',
                'phone' => '(12) 34567-8910',
                'cpf' => '12345678910',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
