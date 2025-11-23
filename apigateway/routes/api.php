<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('gateway.auth')->group(function () {
    
    // Esta es la ruta "comodín" que captura todo.
    // Ejemplo: /api/citas/appointments -> ApiGatewayController lo maneja
    Route::any('/{service}/{path?}', [GatewayController::class, 'handleRequest'])
        ->where('path', '.*');

});