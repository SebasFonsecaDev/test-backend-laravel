<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Ruta para obtener la lista de usuarios
// Se asigna el nombre de ruta 'users.index' para referenciarla en otras partes de la aplicación
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Ruta para obtener las transacciones de una cuenta específica por ID
// Se asigna el nombre de ruta 'transactions.show' para referenciarla en otras partes de la aplicación
Route::get('/users/transactions/{accountId}', [UserController::class, 'showTransactions'])->name('transactions.show');
