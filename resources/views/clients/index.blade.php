@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mt-4">
                <i class="fas fa-users me-2"></i>Clientes
            </h1>
            <a href="{{ route('clients.create') }}" class="btn btn-lilac bg-success">
                <span class="text-white">
                    <i class="fas fa-plus me-2"></i>
                    Novo Cliente
                </span>
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-lilac text-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Lista de Clientes
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('clients.index') }}">
                            {{-- <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Buscar clientes..."
                                    value="{{ request('search') }}">
                                <button class="btn btn-light" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('search'))
                                <a href="{{ route('clients.index') }}" class="btn btn-outline-light">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>CPF</th>
                                <th width="15%">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr>
                                    <td>{{ $client->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $client->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->phone }}</td>
                                    <td>{{ $client->cpf }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir"
                                                    onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
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
                                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Nenhum cliente encontrado</h5>
                                            @if(request('search'))
                                                <a href="{{ route('clients.index') }}" class="btn btn-lilac mt-2">
                                                    Limpar busca
                                                </a>
                                            @else
                                                <a href="{{ route('clients.create') }}" class="btn btn-lilac mt-2">
                                                    Cadastrar primeiro cliente
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($clients->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Mostrando {{ $clients->firstItem() }} a {{ $clients->lastItem() }} de {{ $clients->total() }}
                            registros
                        </div>
                        <nav>
                            {{ $clients->withQueryString()->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .avatar-sm {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(var(--bs-lilac-rgb), 0.1);
        }

        .btn-lilac {
            background-color: var(--bs-lilac);
            color: white;
        }

        .btn-lilac:hover {
            background-color: var(--bs-lilac-dark);
            color: white;
        }

        .bg-lilac-light {
            background-color: var(--bs-lilac-light);
        }

        .text-lilac {
            color: var(--bs-lilac);
        }

        .page-item.active .page-link {
            background-color: var(--bs-lilac);
            border-color: var(--bs-lilac);
        }

        .page-link {
            color: var(--bs-lilac);
        }
    </style>
@endsection