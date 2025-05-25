@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-lilac-dark text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Cadastrar Novo Cliente
                    </h4>
                </div>

                <div class="card-body">
                    <form id="client-form" action="{{ route('clients.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <h5 class="text-lilac mb-3">
                                <i class="fas fa-id-card me-2"></i>Dados do Cliente
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nome Completo*</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback">Por favor, informe o nome do cliente.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-mail*</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">Por favor, informe um e-mail válido.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00">
                                </div>
                               
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-lilac mb-3">
                                <i class="fas fa-phone-alt me-2"></i>Contato
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Telefone*</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                    <div class="invalid-feedback">Por favor, informe um telefone.</div>
                                </div>
                              
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('clients.index') }}" class="btn text-white bg-lilac me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Cadastrar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#cpf').mask('000.000.000-00', {reverse: true});
    $('#phone').mask('(00) 00000-0000');
    $('#zip_code').mask('00000-000');

    $('#client-form').submit(function(e) {
        let isValid = true;
        
        $('[required]').each(function() {
            if ($(this).val() === '') {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        const email = $('#email').val();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#email').addClass('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 100
            }, 500);
        }
    });
});
</script>
@endsection