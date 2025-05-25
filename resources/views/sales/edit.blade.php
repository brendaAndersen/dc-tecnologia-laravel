@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-lilac text-white">
            <h4><i class="fas fa-edit me-2"></i>Editar Venda #{{ $sale->id }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('sales.update', $sale) }}">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="client_id" class="form-label">Cliente</label>
                        <select name="client_id" id="client_id" class="form-select" required>
                            <option value="">Selecione um cliente</option>
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}" 
                                {{ $sale->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                  
                </div>

                <div class="mb-3">
                    <label class="form-label">Produtos</label>
                    <table class="table" id="products-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unitário</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->products as $product)
                            <tr>
                                <td>
                                    <select name="products[{{ $loop->index }}][id]" class="form-select product-select" required>
                                        <option value="">Selecione</option>
                                        @foreach($products as $p)
                                        <option value="{{ $p->id }}" 
                                            {{ $product->id == $p->id ? 'selected' : '' }}
                                            data-price="{{ $p->price }}">
                                            {{ $p->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="products[{{ $loop->index }}][quantity]" 
                                           class="form-control quantity" min="1" 
                                           value="{{ $product->pivot->quantity }}" required>
                                </td>
                                <td>
                                    <input type="number" name="products[{{ $loop->index }}][unit_price]" 
                                           class="form-control unit-price" step="0.01" 
                                           value="{{ $product->pivot->unit_price }}" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control subtotal" 
                                           value="{{ $product->pivot->subtotal }}" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-product">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" id="add-product" class="btn btn-secondary">
                        <i class="fas fa-plus"></i> Adicionar Produto
                    </button>
                </div>

                <div class="mb-3">
                    <label for="total_amount" class="form-label">Total</label>
                    <input type="number" step="0.01" name="total_amount" id="total_amount" 
                           class="form-control" value="{{ $sale->total_amount }}" readonly required>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Atualizar Venda
                    </button>
                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsTable = document.getElementById('products-table');
        const addProductBtn = document.getElementById('add-product');
        let productIndex = {{ $sale->products->count() }};

        addProductBtn.addEventListener('click', function() {
            const newRow = `
                <tr>
                    <td>
                        <select name="products[${productIndex}][id]" class="form-select product-select" required>
                            <option value="">Selecione</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="products[${productIndex}][quantity]" 
                               class="form-control quantity" min="1" value="1" required>
                    </td>
                    <td>
                        <input type="number" name="products[${productIndex}][unit_price]" 
                               class="form-control unit-price" step="0.01" required>
                    </td>
                    <td>
                        <input type="number" class="form-control subtotal" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-product">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            productsTable.querySelector('tbody').insertAdjacentHTML('beforeend', newRow);
            productIndex++;
        });

        productsTable.addEventListener('click', function(e) {
            if (e.target.closest('.remove-product')) {
                e.target.closest('tr').remove();
                updateTotal();
            }
        });

        productsTable.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const unitPriceInput = e.target.closest('tr').querySelector('.unit-price');
                unitPriceInput.value = selectedOption.dataset.price || '';
                updateSubtotal(e.target.closest('tr'));
            }
        });

        productsTable.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity') || e.target.classList.contains('unit-price')) {
                updateSubtotal(e.target.closest('tr'));
            }
        });

        function updateSubtotal(row) {
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            const subtotal = quantity * unitPrice;
            row.querySelector('.subtotal').value = subtotal.toFixed(2);
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total_amount').value = total.toFixed(2);
        }

        document.querySelectorAll('#products-table tbody tr').forEach(row => {
            updateSubtotal(row);
        });
    });
</script>
@endpush