<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GatewayController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí registramos las rutas de la API Gateway para "ConectaSalud".
| Todas las peticiones pasan por este gateway y se redirigen a los microservicios.
|
*/

// ============================
// Rutas de Autenticación
// ============================
Route::post('/register', [GatewayController::class, 'register']);
Route::post('/login', [GatewayController::class, 'login']);

// ============================
// Rutas protegidas por Sanctum
// ============================
Route::group(['middleware' => 'auth:sanctum'], function () {

    // Logout y cambio de contraseña
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/change_password', [UserController::class, 'change_password']);

    // ============================
    // Microservicio de Citas Médicas
    // ============================
    Route::get('/appointments/{patientId}', [GatewayController::class, 'getAppointments']);
    Route::post('/appointments', [GatewayController::class, 'createAppointment']);

    // ============================
    // Microservicio de Historias Clínicas
    // ============================
    Route::get('/records/{patientId}', [GatewayController::class, 'getMedicalRecords']);
    Route::post('/records', [GatewayController::class, 'createMedicalRecord']);

    // ============================
    // Microservicio de Notificaciones
    // ============================
    Route::get('/notifications/{userId}', [GatewayController::class, 'getNotifications']);
    Route::post('/notifications', [GatewayController::class, 'sendNotification']);

    // ============================
    // Microservicio de Reportes
    // ============================
    Route::get('/reports', [GatewayController::class, 'getReports']);
    Route::post('/reports', [GatewayController::class, 'generateReport']);
});
