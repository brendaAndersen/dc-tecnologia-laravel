@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">
            <i class="fas fa-cash-register me-2"></i>Detalhes da Venda #{{ $sale->id }}
        </h1>

        {{-- Informações do Cliente --}}
        <div class="card mb-4 mt-3">
            <div class="card-header bg-lilac text-white">
                <h5 class="mb-0">Informações do Cliente</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($sale->client)
                        <div class="col-md-6">
                            <p><strong>Nome:</strong> {{ $sale->client->name }}</p>
                            <p><strong>Email:</strong> {{ $sale->client->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Telefone:</strong> {{ $sale->client->phone }}</p>
                        </div>
                    @else
                        <div class="col-12">
                            <p class="text-danger"><strong>Cliente não encontrado.</strong></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Produtos Vendidos --}}
        <div class="card mb-4">
            <div class="card-header bg-lilac text-white">
                <h5 class="mb-0">Produtos Vendidos</h5>
            </div>
            <div class="card-body">
                @if($sale->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unitário</th>
                                    <th>Subtotal</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->products as $product)
                                    @if($product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->pivot->quantity }}</td>
                                            <td>R$ {{ number_format($product->pivot->unit_price, 2, ',', '.') }}</td>
                                            <td>R$ {{ number_format($product->pivot->subtotal, 2, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info"
                                                    title="Ver Produto">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-danger">Produto não encontrado</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th colspan="2">R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Nenhum produto vinculado a esta venda.</p>
                @endif
            </div>
        </div>

        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar para Vendas
        </a>
    </div>
@endsection