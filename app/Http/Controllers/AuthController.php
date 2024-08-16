<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Método para iniciar sesión
    public function login(Request $request)
    {
        // Validar los datos de la solicitud (email y password son requeridos)
        $validator = Validator::make($request->all(), [
            'email' => 'required|email', // El campo 'email' es obligatorio y debe ser un correo electrónico válido
            'password' => 'required', // El campo 'password' es obligatorio
        ]);

        // Intentar autenticar al usuario con las credenciales proporcionadas (email y password)
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Si la autenticación falla, se devuelve un mensaje de error con código 401 (No autorizado)
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Si la autenticación es exitosa, obtener el usuario autenticado
        $user = Auth::user();
        // Crear un token de acceso para el usuario autenticado
        $token = $user->createToken('auth_token')->plainTextToken;

        // Devolver el token de acceso en formato JSON, junto con el tipo de token (Bearer)
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    // Método para cerrar sesión
    public function logout()
    {
        // Eliminar todos los tokens del usuario autenticado
        auth()->user()->tokens()->delete();

        // Devolver una respuesta indicando que la sesión se cerró correctamente
        return response()->json([
            "message" => "Sesión cerrada correctamente"
        ]);
    }
}
