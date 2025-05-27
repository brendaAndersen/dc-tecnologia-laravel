@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-lilac text-white">
            <h4><i class="fas fa-edit me-2"></i>Editar Venda #{{ $sale->id }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('sales.update', $sale) }}" id="sale-form">
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
                                    <input type="number" name="products[{{ $loop->index }}][subtotal]" 
                                           class="form-control subtotal" step="0.01" 
                                           value="{{ $product->pivot->subtotal }}" readonly required>
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
                    <div class="card-header">Pagamento</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo de Pagamento</label>
                                    <select class="form-control" name="payment_type" id="payment-type">
                                        <option value="01" {{ $sale->payment_type == '01' ? 'selected' : '' }}>Dinheiro</option>
                                        <option value="02" {{ $sale->payment_type == '02' ? 'selected' : '' }}>Cartão de Crédito</option>
                                        <option value="03" {{ $sale->payment_type == '03' ? 'selected' : '' }}>Cartão de Débito</option>
                                        <option value="04" {{ $sale->payment_type == '04' ? 'selected' : '' }}>PIX</option>
                                        <option value="05" {{ $sale->payment_type == '05' ? 'selected' : '' }}>Boleto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Forma de Pagamento</label>
                                    <select class="form-control" name="payment_method" id="payment-method">
                                        <option value="personalizado" {{ $sale->payment_method == 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                                        <option value="avista" {{ $sale->payment_method == 'avista' ? 'selected' : '' }}>À Vista</option>
                                        <option value="parcelado" {{ $sale->payment_method == 'parcelado' ? 'selected' : '' }}>Parcelado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Qtd. de Parcelas</label>
                                    <input type="number" class="form-control" name="installments_count"
                                        id="installments-count" min="1" value="{{ $sale->installments->count() ?? 1 }}">
                                </div>
                            </div>
                        </div>

                        <div id="installments-container" class="mt-3">
                            @foreach($sale->installments as $installment)
                            <div class="installment-card mb-3 p-3 border rounded">
                                <h5>Parcela {{ $loop->iteration }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Data de Vencimento</label>
                                            <input type="date" name="installments[{{ $loop->index }}][due_date]" 
                                                   class="form-control" value="{{ $installment->due_date->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Valor</label>
                                            <input type="number" name="installments[{{ $loop->index }}][amount]" 
                                                   class="form-control installment-amount" 
                                                   step="0.01" value="{{ $installment->amount }}" required>
                                            <input type="hidden" name="installments[{{ $loop->index }}][payment_type]" value="{{ $installment->payment_type }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total da Venda</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control" id="total-sale"
                                            value="{{ number_format($sale->total_amount, 2, ',', '.') }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <div id="installments-summary" class="alert">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <strong>Soma das Parcelas:</strong> 
                                    <span id="total-installments-display">R$ {{ number_format($sale->installments->sum('amount'), 2, ',', '.') }}</span>
                                </span>
                                <small id="installments-difference" class="fw-bold"></small>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="total_amount" id="total_amount" value="{{ $sale->total_amount }}">

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
    document.addEventListener('DOMContentLoaded', function () {
        const availableProducts = @json($products);
        let productIndex = {{ $sale->products->count() }};
        let totalSale = parseFloat({{ $sale->total_amount }});

        $('#add-product').click(function () {
            let options = '<option value="">Selecione</option>';
            availableProducts.forEach(product => {
                options += `<option value="${product.id}" data-price="${product.price}">${product.name}</option>`;
            });

            const productRow = `
                <tr>
                    <td>
                        <select name="products[${productIndex}][id]" class="form-select product-select" required>
                            ${options}
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
                        <input type="number" name="products[${productIndex}][subtotal]" 
                                class="form-control subtotal" step="0.01" readonly required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-product">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;

            $('#products-table tbody').append(productRow);
            productIndex++;
        });

        $(document).on('click', '.remove-product', function () {
            $(this).closest('tr').remove();
            updateTotal();
        });

        // Atualizar preço unitario quando selecionar produto
        $(document).on('change', '.product-select', function () {
            const selected = $(this).find('option:selected');
            const price = parseFloat(selected.data('price')) || 0;
            const row = $(this).closest('tr');
            row.find('.unit-price').val(price.toFixed(2)).trigger('input');
        });

        // Atualizar subtotal
        $(document).on('input', '.quantity, .unit-price', function () {
            const row = $(this).closest('tr');
            const quantity = parseFloat(row.find('.quantity').val()) || 0;
            const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
            const subtotal = (quantity * unitPrice).toFixed(2);
            row.find('.subtotal').val(subtotal);
            updateTotal();
        });

        // Atualizar total da venda
        function updateTotal() {
            totalSale = 0;
            $('.subtotal').each(function () {
                totalSale += parseFloat($(this).val()) || 0;
            });

            $('#total_amount').val(totalSale.toFixed(2));
            $('#total-sale').val('R$ ' + totalSale.toFixed(2).replace('.', ','));
            
            // Atualiza parcelas quando o total muda
            generateInstallments();
        }

        // Gerar parcelas
        function generateInstallments() {
            const container = $('#installments-container');
            const count = parseInt($('#installments-count').val());
            const paymentType = $('#payment-type').val();
            const paymentMethod = $('#payment-method').val();

            container.empty();

            if (count <= 0 || isNaN(count)) return;

            // à vista: 1 parcela
            if (paymentMethod === 'avista') {
                $('#installments-count').val(1);
                count = 1;
            }

            const installmentValue = totalSale / count;
            const today = new Date();

            for (let i = 0; i < count; i++) {
                const dueDate = new Date();
                dueDate.setMonth(today.getMonth() + i + 1);

                const installmentHtml = `
                    <div class="installment-card mb-3 p-3 border rounded">
                        <h5>Parcela ${i + 1}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Data de Vencimento</label>
                                    <input type="date" name="installments[${i}][due_date]" 
                                           class="form-control" value="${dueDate.toISOString().split('T')[0]}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Valor</label>
                                    <input type="number" name="installments[${i}][amount]" 
                                           class="form-control installment-amount" 
                                           step="0.01" value="${installmentValue.toFixed(2)}" required>
                                    <input type="hidden" name="installments[${i}][payment_type]" value="${paymentType}">
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                container.append(installmentHtml);
            }

            updateInstallmentsTotal();
        }

        function updateInstallmentsTotal() {
            let sum = 0;
            $('.installment-amount').each(function () {
                sum += parseFloat($(this).val()) || 0;
            });

            $('#total-installments-display').text(formatCurrency(sum));

            const summary = $('#installments-summary');
            const differenceElement = $('#installments-difference');
            const submitBtn = $('button[type="submit"]');

            summary.removeClass('alert-danger alert-success');
            differenceElement.removeClass('text-danger text-success');
            submitBtn.prop('disabled', false);

            // Calcula a diferença
            const difference = Math.abs(sum - totalSale);

            if (difference > 0.01) {
                summary.addClass('alert-danger');
                differenceElement.addClass('text-danger')
                    .html(`<strong>Diferença:</strong> R$ ${difference.toFixed(2).replace('.', ',')}`);

                submitBtn.prop('disabled', true)
                    .attr('title', 'Corrija as parcelas antes de salvar');
            } else {
                summary.addClass('alert-success');
                differenceElement.addClass('text-success')
                    .html('<i class="fas fa-check-circle"></i> Valores compatíveis');
            }
        }

        function formatCurrency(value) {
            return 'R$ ' + value.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, '$1.');
        }

        $('#installments-count, #payment-type, #payment-method').change(generateInstallments);
        $(document).on('input', '.installment-amount', updateInstallmentsTotal);

        // Validação antes de enviar o formulário
        $('#sale-form').submit(function (e) {
            let sum = 0;
            $('.installment-amount').each(function () {
                sum += parseFloat($(this).val()) || 0;
            });

            if (Math.abs(sum - totalSale) > 0.01) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Valores incompatíveis',
                    html: `A soma das parcelas (R$ ${sum.toFixed(2).replace('.', ',')}) 
                          não corresponde ao total da venda (R$ ${totalSale.toFixed(2).replace('.', ',')})`,
                    confirmButtonText: 'Entendi',
                    footer: 'Ajuste os valores das parcelas para continuar'
                });

                $('html, body').animate({
                    scrollTop: $('#installments-container').offset().top - 100
                }, 500);
            }
        });

        // Inicializa os valores
        updateTotal();
    });
</script>
@endpush