<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .details {
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .footer {
            margin-top: 50px;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div>Emitido em: {{ $date }}</div>
    </div>

    <div class="details">
        <p><strong>Cliente:</strong> {{ $sale->client->name ?? 'Não informado' }}</p>
        <p><strong>Total:</strong> R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</p>
    </div>

    <h4>Produtos</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th class="text-right">Preço Unit.</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td class="text-right">R$ {{ number_format($product->pivot->unit_price, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($product->pivot->subtotal, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($sale->installments->count() > 0)
        <h4 class="mt-4">Parcelas</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Parcela</th>
                    <th>Vencimento</th>
                    <th class="text-right">Valor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->installments as $installment)
                    <tr>
                        <td>{{ $installment->number }}/{{ $sale->installments->count() }}</td>
                        <td>{{ $installment->due_date->format('d/m/Y') }}</td>
                        <td class="text-right">R$ {{ number_format($installment->amount, 2, ',', '.') }}</td>
                        <td>{{ $installment->paid_at ? 'Pago' : 'Pendente' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        DC Tecnologia - Sistema de Gestão Comercial
    </div>
</body>

</html>