@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header bg-lilac-dark text-center text-white">
            <h5 class="mb-0">Cadastrar Novo Produto</h5>
        </div>
        <div class="card-body">
            <form id="product-form" action="{{ route('products.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nome do Produto</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">Por favor, informe o nome do produto.</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
                        <div class="invalid-feedback">A quantidade deve ser um número positivo.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="unity_value" class="form-label">Valor Unitário (R$)</label>
                        <input type="number" step="0.01" class="form-control" id="unity_value" name="unity_value" min="0"
                            required>
                        <div class="invalid-feedback">O valor deve ser um número positivo.</div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary me-md-2">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                  <button type="submit" class="btn bg-lilac text-white">
                    Enviar
                  </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Validação do formulário
            $('#product-form').submit(function (e) {
                let isValid = true;

                // Validação do nome
                if ($('#name').val().trim() === '') {
                    $('#name').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#name').removeClass('is-invalid');
                }

                // Validação da quantidade
                if ($('#quantity').val() === '' || parseInt($('#quantity').val()) < 0) {
                    $('#quantity').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#quantity').removeClass('is-invalid');
                }

                // Validação do valor
                if ($('#unity_value').val() === '' || parseFloat($('#unity_value').val()) < 0) {
                    $('#unity_value').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#unity_value').removeClass('is-invalid');
                }

                if (!isValid) {
                    e.preventDefault();
                    return false;
                }

                return true;
            });

            // Formatação do valor monetário
            $('#unity_value').on('blur', function () {
                const value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    $(this).val(value.toFixed(2));
                }
            });
        });
    </script>
@endsection