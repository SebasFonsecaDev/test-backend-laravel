<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    // Mostrar la cuenta del usuario autenticado
    public function show()
    {
        $account = Auth::user()->account;

        if (!$account) {
            return response()->json(['message' => 'Cuenta no encontrada'], 404);
        }

        return response()->json(['account' => $account], 200);
    }

    // Realizar una transacción (crédito o débito)
    public function transact(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:credit,debit',
        ]);

        $account = Auth::user()->account;

        if (!$account) {
            return response()->json(['message' => 'Cuenta no encontrada'], 404);
        }

        if ($request->type == 'debit' && $account->balance < $request->amount) {
            return response()->json(['message' => 'Saldo insuficiente'], 400);
        }

        $transaction = new Transaction([
            'amount' => $request->amount,
            'type' => $request->type,
        ]);

        $account->transactions()->save($transaction);

        // Actualizar el balance de la cuenta
        if ($request->type == 'credit') {
            $account->balance += $request->amount;
        } elseif ($request->type == 'debit') {
            $account->balance -= $request->amount;
        }

        $account->save();

        return response()->json(['message' => 'Transacción realizada con éxito'], 200);
    }

    // Consultar el saldo de la cuenta del usuario autenticado
    public function getBalance()
    {
        $account = Auth::user()->account;

        if (!$account) {
            return response()->json(['message' => 'Cuenta no encontrada'], 404);
        }

        return response()->json(['balance' => $account->balance], 200);
    }

    // Agregar saldo a la cuenta del usuario autenticado
    public function addBalance(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $account = Auth::user()->account;

        if (!$account) {
            return response()->json(['message' => 'Cuenta no encontrada'], 404);
        }

        $account->balance += $request->amount;
        $account->save();

        return response()->json(['message' => 'Saldo agregado con éxito', 'balance' => $account->balance], 200);
    }

    // Mostrar las transacciones de la cuenta del usuario autenticado
    public function showTransactions()
    {
        $account = Auth::user()->account;

        if (!$account) {
            return response()->json(['message' => 'Cuenta no encontrada'], 404);
        }

        $transactions = $account->transactions()->orderBy('created_at', 'desc')->paginate(10);

        return response()->json(['transactions' => $transactions], 200);
    }
}
