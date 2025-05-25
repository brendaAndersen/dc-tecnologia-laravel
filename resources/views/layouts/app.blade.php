<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestão de Produtos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-lilac rounded-bottom-1" data-bs-theme="dark">
    <div class="container rounded-bottom-1">
      <a class="navbar-brand text-white">
        <i class="fas fa-store me-2"></i>Gestão de Vendas
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-boxes me-1"></i> Produtos
            </a>
            <ul class="dropdown-menu bg-lilac-dark">
              <li><a class="dropdown-item" href="{{ route('products.index') }}">
                  <i class="fas fa-list me-1"></i> Lista
                </a></li>
              <li><a class="dropdown-item" href="{{ route('products.create') }}">
                  <i class="fas fa-plus me-1"></i> Criar
                </a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-users me-1 text-white"></i> Clientes
            </a>
            <ul class="dropdown-menu bg-lilac-dark">
              <li><a class="dropdown-item" href="{{ route('clients.index') }}">
                  <i class="fas fa-list me-1"></i> Lista
                </a></li>
              <li><a class="dropdown-item" href="{{ route('clients.create') }}">
                  <i class="fas fa-user-plus me-1"></i> Criar
                </a>
              </li>
            </ul>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-boxes me-1"></i> Vendas
            </a>
            <ul class="dropdown-menu bg-lilac-dark">
              <li><a class="dropdown-item" href="{{ route('sales.index') }}">
                  <i class="fas fa-list me-1"></i> Lista
                </a></li>
              <li><a class="dropdown-item" href="{{ route('sales.create') }}">
                  <i class="fas fa-plus me-1"></i> Criar
                </a>
              </li>
          </li>
        </ul>
        </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    @yield('content')
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>

  @stack('scripts')
</body>

</html>