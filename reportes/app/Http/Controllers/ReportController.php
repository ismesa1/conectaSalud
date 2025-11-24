<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Exports\AppointmentsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected $citasUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->citasUrl = env('CITAS_SERVICE_URL');
        $this->apiKey   = env('API_KEY');
    }

    /* ===========================================================
       GENERAR EXCEL DE CITAS POR PACIENTE
    ============================================================ */
    public function generatePatientAppointmentsExcel(string $patientId)
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey
            ])->get("{$this->citasUrl}/api/appointments/patient/{$patientId}");

            if ($response->failed()) {
                return response()->json(['error' => 'No se pudo conectar al microservicio de Citas.'], 500);
            }

            $appointments = $response->json();

            return Excel::download(new AppointmentsExport($appointments), "reporte_citas_{$patientId}.xlsx");

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error inesperado.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /* ===========================================================
       GENERAR PDF DE CITAS POR PACIENTE
    ============================================================ */
    public function generatePatientAppointmentsPdf(string $patientId)
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey
            ])->get("{$this->citasUrl}/api/appointments/patient/{$patientId}");

            if ($response->failed()) {
                return response()->json(['error' => 'No se pudo conectar al microservicio de Citas.'], 500);
            }

            $appointments = $response->json() ?? [];

            $pdf = Pdf::loadView('reports.appointments_pdf', [
                'appointments' => $appointments
            ]);

            return $pdf->download("reporte_citas_{$patientId}.pdf");

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error inesperado.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
