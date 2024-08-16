<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Obtén todos los usuarios y sus cuentas
        $users = User::with('account')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.index', compact('users'));
    }

    public function showTransactions($userId)
    {
        // Encuentra el usuario por ID
        $user = User::find($userId);

        // Verifica si el usuario existe
        if (!$user) {
            return abort(404, 'Usuario no encontrado');
        }

        // Obtén la cuenta del usuario
        $account = $user->account;

        // Verifica si la cuenta existe
        if (!$account) {
            return abort(404, 'Cuenta no encontrada');
        }

        // Obtén las transacciones de la cuenta y ordénalas por fecha
        $transactions = $account->transactions()->orderBy('transaction_date', 'desc')->get();

        return view('transactions.index', compact('transactions', 'account'));
    }

    public function showTransactionsByUserId($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $account = $user->account;

        if (!$account) {
            return response()->json(['message' => 'Cuenta no encontrada'], 404);
        }

        $transactions = $account->transactions()->orderBy('transaction_date', 'desc')->get();

        // Log de la acción
        Log::channel('customTraking')->info("User $userId requested their transactions. $transactions");

        return response()->json([
            'transactions' => $transactions,
            'account' => $account
        ]);
    }

    public function showBalanceByUserId($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $account = $user->account;

        if (!$account) {
            return response()->json(['message' => 'Cuenta no encontrada'], 404);
        }

        $balance = $account->balance;

        return response()->json([
            'user_id' => $userId,
            'balance' => $balance
        ]);
    }

    public function downloadLogs(): StreamedResponse
    {
        $logFilePath = storage_path('logs/traking-user.log');

        // Verifica si el archivo de logs existe
        if (!file_exists($logFilePath)) {
            return response()->json(['message' => 'Archivo de logs no encontrado'], 404);
        }

        // Retorna una respuesta de archivo descargable
        return response()->stream(
            function () use ($logFilePath) {
                // Usa fopen y fpassthru para manejar grandes archivos y evitar problemas de memoria
                $file = fopen($logFilePath, 'rb');
                fpassthru($file);
                fclose($file);
            },
            200,
            [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename="user_traking_logs.txt"',
            ]
        );
    }
}
