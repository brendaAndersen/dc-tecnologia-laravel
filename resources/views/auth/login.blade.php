{{-- View --}}
@extends('layouts.guest')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login do Cliente</div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="cpf">CPF</label>
                                <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror"
                                    name="cpf" value="{{ old('cpf') }}" required autocomplete="cpf" autofocus
                                    placeholder="000.000.000-00" oninput="formatarCPF(this)">

                                @error('cpf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary w-100">
                                    Entrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatarCPF(campo) {
            let cpf = campo.value.replace(/\D/g, '');

            if (cpf.length > 3) cpf = cpf.replace(/^(\d{3})/, '$1.');
            if (cpf.length > 7) cpf = cpf.replace(/^(\d{3})\.(\d{3})/, '$1.$2.');
            if (cpf.length > 11) cpf = cpf.replace(/^(\d{3})\.(\d{3})\.(\d{3})/, '$1.$2.$3-');

            campo.value = cpf.substring(0, 14);
        }
    </script>
@endsection