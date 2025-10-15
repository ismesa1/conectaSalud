<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Citas</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Citas del Paciente</h1>
    <table>
        <thead>
            <tr>
                <th>ID Cita</th>
                <th>ID Doctor</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment['id'] }}</td>
                    <td>{{ $appointment['doctorId'] }}</td>
                    <td>{{ $appointment['appointmentDate'] }}</td>
                    <td>{{ $appointment['status'] }}</td>
                    <td>{{ $appointment['notes'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No se encontraron citas para este paciente.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
