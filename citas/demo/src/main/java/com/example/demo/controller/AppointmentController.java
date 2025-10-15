// En el paquete controller
package com.example.demo.controller;

import com.example.demo.model.Appointment;
import com.example.demo.repository.AppointmentRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import java.util.List;

@RestController
@RequestMapping("/api/appointments") // La ruta base para todos los endpoints de este controlador
public class AppointmentController {

    // Inyección de dependencias: Spring se encarga de darnos una instancia
    // del repositorio para poder hablar con la base de datos.
    @Autowired
    private AppointmentRepository appointmentRepository;

    /**
     * Endpoint para crear una nueva cita.
     * Se accede a él vía POST http://localhost:8080/api/appointments
     */
    @PostMapping
    public Appointment createAppointment(@RequestBody Appointment appointment) {
        appointment.setStatus("AGENDADA");
        return appointmentRepository.save(appointment);
    }

    /**
     * Endpoint para obtener todas las citas de un paciente específico.
     * Se accede a él vía GET http://localhost:8080/api/appointments/patient/{patientId}
     */
    @GetMapping("/patient/{patientId}")
    public List<Appointment> getAppointmentsByPatient(@PathVariable String patientId) {
        return appointmentRepository.findByPatientId(patientId);
    }

    /**
     * Endpoint para cancelar una cita por su ID.
     * Se accede a él vía PUT http://localhost:8080/api/appointments/{id}/cancel
     */
    @PutMapping("/{id}/cancel")
    public ResponseEntity<Appointment> cancelAppointment(@PathVariable Long id) {
        // Busca la cita por ID. Si la encuentra, la actualiza. Si no, devuelve un error 404.
        return appointmentRepository.findById(id).map(appointment -> {
            appointment.setStatus("CANCELADA");
            appointmentRepository.save(appointment);
            return ResponseEntity.ok(appointment);
        }).orElse(ResponseEntity.notFound().build());
    }
}
