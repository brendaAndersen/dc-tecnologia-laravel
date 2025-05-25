@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header bg-lilac text-white">
            <h4><i class="fas fa-plus me-2"></i>Nova Venda</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('sales.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="client_id" class="form-label">Cliente</label>
                    <select name="client_id" id="client_id" class="form-select" required>
                        <option value="">Selecione um cliente</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="mb-3">
                    <label class="form-label">Produtos</label>
                    <table class="table table-bordered" id="products-table">
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
                            <!-- Linhas dinâmicas aqui -->
                        </tbody>
                    </table>
                    <button type="button" id="add-product" class="btn btn-secondary w-100">
                        <i class="fas fa-plus"></i> Adicionar Produto
                    </button>
                </div>

                <div class="mb-3">
                    <label for="total_amount" class="form-label">Total</label>
                    <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" readonly
                        required>
                </div>

                <div class="mb-3">
                    <div class="card-header">Pagamento</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo de Pagamento</label>
                                    <select class="form-control" name="payment_type" id="payment-type">
                                        <option value="01">Dinheiro</option>
                                        <option value="02">Cartão de Crédito</option>
                                        <option value="03">Cartão de Débito</option>
                                        <option value="04">PIX</option>
                                        <option value="05">Boleto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Forma de Pagamento</label>
                                    <select class="form-control" name="payment_method" id="payment-method">
                                        <option value="personalizado">Personalizado</option>
                                        <option value="avista">À Vista</option>
                                        <option value="parcelado">Parcelado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Qtd. de Parcelas</label>
                                    <input type="number" class="form-control" name="installments_count"
                                        id="installments-count" min="1" value="1">
                                </div>
                            </div>
                        </div>

                        <div id="installments-container" class="mt-3">
                            <!-- As parcelas serão geradas aqui via JavaScript -->
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total da Venda</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control" id="total-sale"
                                            value="{{ isset($sale) ? number_format($sale->total_amount, 2, ',', '.') : '0,00' }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Registrar Venda
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Variáveis globais
            const availableProducts = @json($products);
            let productIndex = 0;
            let totalSale = 0;

            // Adicionar produto
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

            // Remover produto
            $(document).on('click', '.remove-product', function () {
                $(this).closest('tr').remove();
                updateTotal();
            });

            // Atualizar preço unitário quando selecionar produto
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
                $('#total-sale-display').text(formatCurrency(totalSale));
                $('#total-sale').val('R$ ' + totalSale.toFixed(2).replace('.', ','));

                // Atualiza parcelas quando o total muda
                generateInstallments();
            }

            // Gerar parcelas
            function generateInstallments() {
                const container = $('#installments-container');
                const count = parseInt($('#installments-count').val());
                const paymentType = $('#payment-type').val();

                container.empty();

                if (count <= 0 || isNaN(count)) return;

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

                const display = $('#total-installments-display');
                display.text(formatCurrency(sum));

                // Elementos para feedback
                const summary = $('#installments-summary');
                const differenceElement = $('#installments-difference');
                const submitBtn = $('button[type="submit"]');

                // Remove classes anteriores
                summary.removeClass('alert-danger alert-success');
                differenceElement.removeClass('text-danger text-success');
                submitBtn.prop('disabled', false);

                // Calcula a diferença
                const difference = Math.abs(sum - totalSale);

                if (difference > 0.01) {
                    // Feedback de erro
                    summary.addClass('alert-danger');
                    differenceElement.addClass('text-danger')
                        .html(`<strong>Diferença:</strong> R$ ${difference.toFixed(2).replace('.', ',')}`);

                    // Desabilita o botão de submit
                    submitBtn.prop('disabled', true)
                        .attr('title', 'Corrija as parcelas antes de salvar');

                    // Adiciona tooltip de erro
                    $('.installment-amount').tooltip({
                        title: 'Ajuste este valor para que a soma corresponda ao total',
                        trigger: 'manual',
                        placement: 'top'
                    }).tooltip('show');
                } else {
                    // Feedback de sucesso
                    summary.addClass('alert-success');
                    differenceElement.addClass('text-success')
                        .html('<i class="fas fa-check-circle"></i> Valores compatíveis');

                    // Remove tooltips
                    $('.installment-amount').tooltip('dispose');
                }
            }

            // Adiciona elemento de feedback no HTML (coloque isso no seu card-body de pagamento)
            const feedbackHtml = `
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <div id="installments-summary" class="alert">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span>
                                                                <strong>Soma das Parcelas:</strong> 
                                                                <span id="total-installments-display">R$ 0,00</span>
                                                            </span>
                                                            <small id="installments-difference" class="fw-bold"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `;

            // Insere o feedback após o container de parcelas
            $('#installments-container').after(feedbackHtml);

            // Validação ao mudar valores das parcelas
            $(document).on('input', '.installment-amount', function () {
                // Força o recálculo imediato
                updateInstallmentsTotal();

                // Validação individual para cada parcela
                const value = parseFloat($(this).val()) || 0;
                if (value <= 0) {
                    $(this).addClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                    $(this).after('<div class="invalid-feedback">O valor deve ser positivo</div>');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            // Validação antes de enviar o formulário
            $('#sale-form').submit(function (e) {
                let sum = 0;
                $('.installment-amount').each(function () {
                    sum += parseFloat($(this).val()) || 0;
                });

                if (Math.abs(sum - totalSale) > 0.01) {
                    e.preventDefault();

                    // Feedback mais amigável que um alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Valores incompatíveis',
                        html: `A soma das parcelas (R$ ${sum.toFixed(2).replace('.', ',')}) 
                                                          não corresponde ao total da venda (R$ ${totalSale.toFixed(2).replace('.', ',')})`,
                        confirmButtonText: 'Entendi',
                        footer: 'Ajuste os valores das parcelas para continuar'
                    });

                    // Rolagem para a seção de parcelas
                    $('html, body').animate({
                        scrollTop: $('#installments-container').offset().top - 100
                    }, 500);
                }
            });
            function formatCurrency(value) {
                return 'R$ ' + value.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, '$1.');
            }

            $('#installments-count, #payment-type').change(generateInstallments);
            $(document).on('input', '.installment-amount', updateInstallmentsTotal);

            // $('#sale-form').submit(function (e) {
            //     let sum = 0;
            //     $('.installment-amount').each(function () {
            //         sum += parseFloat($(this).val()) || 0;
            //     });

            //     if (Math.abs(sum - totalSale) > 0.01) {
            //         e.preventDefault();
            //         alert('A soma das parcelas não corresponde ao total da venda!');
            //         $('#installments-summary').addClass('alert-danger');
            //     }
            // });

            $('#add-product').click();
        });
    </script>
@endpush