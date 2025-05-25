@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center text-white bg-lilac-dark">
            <h5 class="mb-0">Lista de Produtos</h5>
            <a href="{{ route('products.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Novo Produto
            </a>
        </div>
        <div class="card-body">
            <div class="mb-3">
                {{-- <div class="input-group">
                    <input type="text" id="search-input" class="form-control" placeholder="Pesquisar produtos...">
                    <button id="search-btn" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div> --}}
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Quantidade</th>
                            <th>Valor Unitário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="products-table-body">
                        @foreach($products as $product)
                            <tr id="product-{{ $product->id }}">
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>R$ {{ number_format($product->unity_value, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir este produto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Excluir</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            let productIdToDelete;

            function searchProducts(query) {
                $.ajax({
                    url: "{{ route('products.index') }}",
                    method: 'GET',
                    data: { search: query },
                    success: function (response) {
                        $('#products-table-body').html($(response).find('#products-table-body').html());
                    }
                });
            }

            $('#search-btn').click(function () {
                const query = $('#search-input').val();
                searchProducts(query);
            });

            $('#search-input').keypress(function (e) {
                if (e.which === 13) {
                    const query = $(this).val();
                    searchProducts(query);
                }
            });

            $('.delete-btn').click(function () {
                productIdToDelete = $(this).data('id');
                $('#confirmModal').modal('show');
            });

            $('#confirm-delete').click(function () {
                $.ajax({
                    url: `/products/${productIdToDelete}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $('#product-' + productIdToDelete).remove();
                        $('#confirmModal').modal('hide');
                        showAlert('success', 'Produto excluído com sucesso!');
                    },
                    error: function () {
                        showAlert('danger', 'Erro ao excluir produto!');
                    }
                });
            });

            function showAlert(type, message) {
                const alert = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                                ${message}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>`;
                $('.container').prepend(alert);

                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }
        });
    </script>
@endsection