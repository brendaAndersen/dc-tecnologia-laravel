@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
        <h2 class="mb-0">{{ $product->name }}</h2>
        <div class="actions">
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-box-open me-2"></i>Informações do Produto
                </div>
                <div class="card-body">
                    <div class="product-info">
                        <div class="mb-3">
                            <p class="text-muted mb-1">Código</p>
                            <h5>{{ $product->id ?? 'N/A' }}</h5>
                        </div>
                        
                        <div class="mb-3">
                            <p class="text-muted mb-1">Preço</p>
                            <h4 class="text-success">R$ {{ number_format($product->unity_value, 2, ',', '.') }}</h4>
                        </div>
                        
                        <div class="mb-3">
                            <p class="text-muted mb-1">Estoque Disponível</p>
                            <h5>
                                <span class="badge bg-{{ $product->quantity > 0 ? 'success' : 'danger' }}">
                                    {{ $product->quantity }} unidades
                                </span>
                            </h5>
                        </div>
                        
                        <div class="mb-3">
                            <p class="text-muted mb-1">Cadastrado em</p>
                            <h5>{{ $product->created_at->format('d/m/Y H:i') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-chart-line me-2"></i>Estatísticas de Vendas
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="stat-card p-3 border rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-circle bg-primary-light text-primary me-3">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Total de Vendas</p>
                                        <h3 class="mb-0">{{ $stats['total_sales'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="stat-card p-3 border rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-circle bg-success-light text-success me-3">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Faturamento Total</p>
                                        <h3 class="mb-0">R$ {{ number_format($stats['total_amount'], 2, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="stat-card p-3 border rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-circle bg-info-light text-info me-3">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Média por Venda</p>
                                        <h3 class="mb-0">R$ {{ number_format($stats['average_sale'], 2, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                      
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-info h5 {
        font-weight: 500;
    }
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
    }
    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1);
    }
    .bg-info-light {
        background-color: rgba(13, 202, 240, 0.1);
    }
    .bg-secondary-light {
        background-color: rgba(108, 117, 125, 0.1);
    }
    .stat-card {
        transition: transform 0.3s;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection