<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Aquí colocar la ruta pública para leer el QR
// Route::get('/sos/{qr_slug}', [ProfileController::class, 'showPublic']);


/*
|--------------------------------------------------------------------------
| Rutas Protegidas
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Rutas de Sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // Obtener información del usuario logueado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Aquí rutas para crear/editar el Perfil
    // Route::post('/profile', [ProfileController::class, 'store']);
    // Route::put('/profile', [ProfileController::class, 'update']);

});
