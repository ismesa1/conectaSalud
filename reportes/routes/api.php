<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/reports/patient/{patientId}/excel', [ReportController::class, 'generatePatientAppointmentsExcel']);


Route::get('/reports/patient/{patientId}/pdf', [ReportController::class, 'generatePatientAppointmentsPdf']);