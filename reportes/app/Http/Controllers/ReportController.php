<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Exports\AppointmentsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Generar reporte Excel de citas de un paciente
     */
    public function generateAppointmentsExcel($patientId)
    {
        try {
            $citasServiceUrl = config('app.citas_service_url');
            $apiKey = config('app.api_key');

            $response = Http::withHeaders([
                'X-API-Key' => $apiKey
            ])->get("{$citasServiceUrl}/api/appointments/patient/{$patientId}");

            if ($response->failed()) {
                return response()->json(['error' => 'No se pudo conectar al microservicio de Citas.'], 500);
            }

            $appointments = $response->json();

            return Excel::download(new AppointmentsExport($appointments), "reporte_citas_{$patientId}.xlsx");

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error inesperado.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar PDF de citas de un paciente
     */
    public function generateAppointmentsPdf($patientId)
    {
        try {
            $citasServiceUrl = config('app.citas_service_url');
            $apiKey = config('app.api_key');

            $response = Http::withHeaders([
                'X-API-Key' => $apiKey
            ])->get("{$citasServiceUrl}/api/appointments/patient/{$patientId}");

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
                'error' => 'Ocurrió un error inesperado.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
