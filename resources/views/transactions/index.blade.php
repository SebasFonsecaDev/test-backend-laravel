<!-- resources/views/transactions/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transacciones de la Cuenta</title>
</head>
<body>
    <h1>Transacciones de la Cuenta {{ $account->account_number }}</h1>

    @if ($transactions->isEmpty())
        <p>No hay transacciones disponibles para esta cuenta.</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Monto</th>
                    <th>Fecha de Transacción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->amount }}</td>
                        <td>{{ $transaction->transaction_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ url('/users') }}">Volver a la lista de usuarios</a>
</body>
</html>
