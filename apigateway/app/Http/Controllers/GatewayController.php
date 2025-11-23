<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    /**
     * Método mágico para manejar todas las peticiones a los microservicios.
     *
     * @param Request $request La petición original del cliente.
     * @param string $service El nombre del servicio (auth, citas, historias, etc.).
     * @param string $path La ruta específica dentro del servicio (ej: login, create_user).
     */
    public function handleRequest(Request $request, $service, $path = '')
    {
        $baseUrl = $this->getServiceUrl($service);

        if (!$baseUrl) {
            return response()->json(['error' => 'Microservicio no encontrado o no configurado.'], 404);
        }

        $url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

        $queryString = $request->getQueryString();
        if ($queryString) {
            $url .= "?{$queryString}";
        }

        $method = $request->method();
        
        $data = $request->all();

        $http = Http::withHeaders([
            'X-Gateway-Key' => env('GATEWAY_API_KEY'),
            'Accept' => 'application/json',
        ]);

        try {
            $response = $http->send($method, $url, [
                'json' => $data 
            ]);

            return response($response->body(), $response->status())
                ->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error de conexión con el microservicio.',
                'service' => $service,
                'url' => $url,
                'details' => $e->getMessage()
            ], 502); 
        }
    }

    private function getServiceUrl($service)
    {
        switch ($service) {
            case 'auth':
                return env('MICROSERVICE_AUTH_URL');
            case 'historias':
                return env('MICROSERVICE_HISTORIAS_URL');
            case 'notificaciones':
                return env('MICROSERVICE_NOTIFICACIONES_URL');
            case 'citas':
                return env('MICROSERVICE_CITAS_URL');
            case 'reportes':
                return env('MICROSERVICE_REPORTES_URL');
            default:
                return null;
        }
    }
}
