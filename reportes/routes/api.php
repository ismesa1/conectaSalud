<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/reports/appointments/excel/{patientId}', [ReportController::class, 'generateAppointmentsExcel']);

Route::get('/reports/appointments/pdf/{patientId}', [ReportController::class, 'generateAppointmentsPdf']);