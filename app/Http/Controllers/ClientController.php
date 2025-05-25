<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(Request $request)
    {
        $query = $request->input('query');
        
        $clients = $query 
            ? $this->clientService->searchClients($query)
            : $this->clientService->getAllClients();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|string|max:20',
            'cpf' => 'required|string|max:255'
        ]);

        $client = $this->clientService->createClient($validated);

        return redirect()->route('clients.index', $client->id)
            ->with('success', 'Client created successfully.');
    }

    public function show(int $id)
    {
        $client = $this->clientService->getClientById($id);
        $stats = $this->clientService->getClientSalesStats($id);

        return view('clients.show', compact('client', 'stats'));
    }

    public function edit(int $id)
    {
        $client = $this->clientService->getClientById($id);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:clients,email,' . $id,
            'phone' => 'sometimes|string|max:20',
            'cpf' => 'sometimes|string|max:255'
        ]);

        $client = $this->clientService->updateClient($id, $validated);

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->clientService->deleteClient($id);

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    public function sales(int $id)
    {
        $client = $this->clientService->getClientById($id);
        $sales = $client->sales()->with('products')->paginate(10);

        return view('clients.sales', compact('client', 'sales'));
    }
}