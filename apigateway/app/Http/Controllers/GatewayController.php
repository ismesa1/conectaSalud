<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    protected $authUrl;
    protected $recordsUrl;
    protected $appointmentsUrl;
    protected $notificationsUrl;
    protected $reportsUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->authUrl = env('AUTH_API_URL');
        $this->recordsUrl = env('RECORDS_API_URL');
        $this->appointmentsUrl = env('APPOINTMENTS_API_URL');
        $this->notificationsUrl = env('NOTIFICATIONS_API_URL');
        $this->reportsUrl = env('REPORTS_API_URL');
        $this->apiKey = env('API_KEY');
    }

    // ============================
    // Microservicio de Autenticacion
    // ============================

        // Registrar usuario
    public function register(Request $request)
    {
        $data = $request->all();

        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey  // si tu auth lo requiere
        ])->post("{$this->authUrl}/api/create_user", $data);

        return $response->json();
    }

    // Login
    public function login(Request $request)
    {
        $data = $request->all();

        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey
        ])->post("{$this->authUrl}/api/login", $data);

        return response()->json($response->json(), $response->status());
    }

    // ============================
    // Microservicio de Citas
    // ============================
public function getAppointments($patientId)
    {
        $response = Http::withHeaders(['X-API-KEY' => $this->apiKey])
                        ->get("{$this->appointmentsUrl}/api/appointments/patient/{$patientId}");
        return response()->json($response->json(), $response->status());
    }

    public function createAppointment(Request $request)
    {
        $response = Http::withHeaders(['X-API-KEY' => $this->apiKey])
                        ->post("{$this->appointmentsUrl}/api/appointments", $request->all());
        return response()->json($response->json(), $response->status());
    }

    // ============================
    // Microservicio de Historias Clínicas
    // ============================
// Obtener historias de un paciente
public function getMedicalRecords($patientId) {
    $response = Http::withHeaders([
        'X-API-Key' => $this->apiKey
    ])->get("{$this->recordsUrl}/api/historias/paciente/{$patientId}");

    return response()->json($response->json(), $response->status());
}

// Crear una nueva historia
public function createMedicalRecord(Request $request) {
    $data = $request->all();
    $response = Http::withHeaders([
        'X-API-Key' => $this->apiKey
    ])->post("{$this->recordsUrl}/api/historias", $data);

    return response()->json($response->json(), $response->status());
}

// Actualizar historia
public function updateMedicalRecord(Request $request, $id) {
    $data = $request->all();
    $response = Http::withHeaders([
        'X-API-Key' => $this->apiKey
    ])->put("{$this->recordsUrl}/api/historias/{$id}", $data);

    return response()->json($response->json(), $response->status());
}

// Borrar historia
public function deleteMedicalRecord($id) {
    $response = Http::withHeaders([
        'X-API-Key' => $this->apiKey
    ])->delete("{$this->recordsUrl}/api/historias/{$id}");

    return response()->json($response->json(), $response->status());
}

    // ============================
    // Microservicio de Notificaciones
    // ============================
    // Obtener notificaciones de un usuario
public function getNotifications($userId)
{
    $response = Http::withHeaders([
        'X-API-Key' => $this->apiKey
    ])->get("{$this->notificationsUrl}/api/notificaciones/{$userId}");

    return response()->json($response->json(), $response->status());
}

// Enviar una notificación
public function sendNotification(Request $request)
{
    $data = $request->all();

    $response = Http::withHeaders([
        'X-API-Key' => $this->apiKey
    ])->post("{$this->notificationsUrl}/api/notificaciones", $data);

    return response()->json($response->json(), $response->status());
}

    // ============================
    // Microservicio de Reportes
    // ============================
    public function getReports()
    {
        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey
        ])->get("{$this->reportsUrl}/reports");

        return $response->json();
    }

    public function generateReport(Request $request)
    {
        $data = $request->all();
        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey
        ])->post("{$this->reportsUrl}/api/reports", $data);

        return $response->json();
    }
}
