<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AppointmentsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $appointments;

    public function __construct(array $appointments)
    {
        $this->appointments = $appointments;
    }

    public function collection()
    {
        return collect($this->appointments);
    }

    public function headings(): array
    {
        return [
            'ID Cita',
            'ID Paciente',
            'ID Doctor',
            'Fecha de la Cita',
            'Estado',
            'Notas',
        ];
    }
}