@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mt-4">
                <i class="fas fa-cash-register me-2"></i>Vendas
            </h1>
            <a href="{{ route('sales.create') }}" class="btn btn-lilac bg-lilac">
                <i class="fas fa-plus me-2 text-white"></i>
                <span class="text-white">
                    Nova Venda
                </span>
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-lilac text-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Histórico de Vendas
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('sales.index') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Buscar vendas..."
                                    value="{{ request('search') }}">
                                <button class="btn btn-light" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Produtos</th>
                                <th>Total</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>
                                        @if($sale->client)
                                            <a href="{{ route('clients.index', $sale) }}">
                                                {{ $sale->client->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Cliente não informado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-lilac-light text-dark">
                                            {{ $sale->products->count() }} itens
                                        </span>
                                    </td>
                                    <td class="fw-bold">R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm gap-2" role="group">
                                            <a href="{{ route('sales.download', $sale) }}" class="rounded btn btn-danger"
                                                title="Gerar PDF">
                                                <i class="fas fa-file-pdf me-1"></i>
                                            </a>
                                            <a href="{{ route('sales.edit', $sale) }}"
                                                class="rounded btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('sales.show', $sale) }}" class="rounded btn btn-outline-primary"
                                                title="Detalhes">
                                                <i class="fas fa-eye me-1"></i>
                                            </a>
                                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Excluir"
                                                    onclick="return confirm('Tem certeza que deseja excluir esta venda?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-cash-register fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Nenhuma venda registrada</h5>
                                            <a href="{{ route('sales.create') }}" class="btn btn-lilac mt-2 bg-lilac text-white">
                                                Realizar primeira venda
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($sales->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Mostrando {{ $sales->firstItem() }} a {{ $sales->lastItem() }} de {{ $sales->total() }} registros
                        </div>
                        <nav>
                            {{ $sales->withQueryString()->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection