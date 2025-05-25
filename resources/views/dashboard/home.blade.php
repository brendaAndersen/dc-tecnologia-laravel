@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="sales-stats bg-light p-4 rounded">
                        <h5 class="mb-4 text-success">
                            <i class="fas fa-chart-line me-2"></i>Estatísticas de Vendas
                        </h5>

                        <div class="stat-item mb-3">
                            <div class="d-flex justify-content-between">
                                <span><strong>Total de Vendas:</strong></span>
                                <span class="badge bg-primary">{{ $stats['total_sales'] }}</span>
                            </div>
                        </div>

                        <div class="stat-item mb-3">
                            <div class="d-flex justify-content-between">
                                <span><strong>Valor Total:</strong></span>
                                <span class="badge bg-success">R$
                                    {{ number_format($stats['total_amount'], 2, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="stat-item mb-3">
                            <div class="d-flex justify-content-between">
                                <span><strong>Média por Venda:</strong></span>
                                <span class="badge bg-info">R$
                                    {{ number_format($stats['average_sale'], 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .client-info {
        border-right: 1px solid #eee;
        padding-right: 30px;
    }

    .stat-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .card-title {
        font-size: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }
</style>
@endsection