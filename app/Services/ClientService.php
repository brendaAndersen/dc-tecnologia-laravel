<?php

namespace App\Services;
use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientService
{
    public function getAllClients(int $perPage = 10): LengthAwarePaginator
    {
        return Client::paginate($perPage);
    }

    public function createClient(array $data): Client
    {
        return Client::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'cpf' => $data['cpf'],
            'phone' => $data['phone'],
        ]);
    }

    public function getClientById(int $id): Client
    {
        return Client::findOrFail($id);
    }

    public function updateClient(int $id, array $data): Client
    {
        $client = Client::findOrFail($id);
        
        $client->update([
            'name' => $data['name'] ?? $client->name,
            'email' => $data['email'] ?? $client->email,
            'phone' => $data['phone'] ?? $client->phone,
            'cpf' => $data['cpf'] ?? $client->cpf
        ]);

        return $client;
    }
   public function deleteClient(int $id): bool
    {
        $client = Client::findOrFail($id);
        return $client->delete();
    }

     public function searchClients(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return Client::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->paginate($perPage);
    }

    public function getClientSalesStats(int $clientId): array
    {
        $client = Client::with('sales')->find($clientId);
        
        if (!$client) {
            return [
                'error' => 'Client not found',
                'total_sales' => 0,
                'total_amount' => 0,
                'average_sale' => 0,
            ];
        }
        
        return [
            'total_sales' => $client->sales->count(),
            'total_amount' => $client->sales->sum('total_amount'),
            'average_sale' => $client->sales->avg('total_amount'),
        ];
    }
}