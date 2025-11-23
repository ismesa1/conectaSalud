<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Exports\AppointmentsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function generatePatientAppointmentsExcel(string $patientId)
    {
        try {
            $citasServiceUrl = config('app.citas_service_url');

            $response = Http::get("{$citasServiceUrl}/api/appointments/patient/{$patientId}");

            if ($response->failed()) {
                return response()->json(['error' => 'No se pudo conectar al servicio de citas.'], 500);
            }

            $appointments = $response->json();


            return Excel::download(new AppointmentsExport($appointments), 'reporte_citas.xlsx');

        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error inesperado.', 'message' => $e->getMessage()], 500);
        }
    }

    public function generatePatientAppointmentsPdf(string $patientId)
    {
        try {
            $citasServiceUrl = config('app.citas_service_url');

            $response = Http::get("{$citasServiceUrl}/api/appointments/patient/{$patientId}");

            if ($response->failed()) {
                return response()->json(['error' => 'No se pudo conectar al servicio de citas.'], 500);
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