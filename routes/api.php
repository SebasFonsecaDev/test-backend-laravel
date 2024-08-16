<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas que requieren autenticación mediante Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Obtener información de la cuenta del usuario autenticado
    Route::get('/account', [AccountController::class, 'show']);

    // Realizar transacciones en la cuenta del usuario autenticado
    Route::post('/account/transact', [AccountController::class, 'transact']);

    // Obtener el balance de la cuenta del usuario autenticado
    Route::get('/account/balance', [AccountController::class, 'getBalance']);

    // Agregar balance a la cuenta del usuario autenticado
    Route::post('/account/add-balance', [AccountController::class, 'addBalance']);

    // Obtener las transacciones de la cuenta del usuario autenticado
    Route::get('/account/transactions', [AccountController::class, 'showTransactions']);
});

// Ruta para iniciar sesión
Route::post('/login', [AuthController::class, 'login']);

// Ruta para cerrar sesión, requiere autenticación mediante Sanctum
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Rutas que requieren autenticación mediante Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Obtener las transacciones de un usuario específico por ID
    Route::get('users/{userId}/transactions', [UserController::class, 'showTransactionsByUserId']);

    // Obtener el balance de un usuario específico por ID
    Route::get('users/{userId}/balance', [UserController::class, 'showBalanceByUserId']);

    // Ruta para descargar los logs de acciones de los usuarios
    Route::get('logs/download', [UserController::class, 'downloadLogs']);
});

