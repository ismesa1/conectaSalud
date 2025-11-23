<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Citas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Reporte de Citas</h1>

    @if(count($citas) === 0)
        <p>No hay citas para este paciente.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Doctor</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($citas as $cita)
                    <tr>
                        <td>{{ $cita['id'] }}</td>
                        <td>{{ $cita['patientId'] }}</td>
                        <td>{{ $cita['doctorId'] }}</td>
                        <td>{{ $cita['appointmentDate'] }}</td>
                        <td>{{ $cita['status'] }}</td>
                        <td>{{ $cita['notes'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
