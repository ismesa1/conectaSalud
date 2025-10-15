package com.example.demo.repository;

// En el paquete repository
import com.example.demo.model.Appointment;
import org.springframework.data.jpa.repository.JpaRepository;
import java.util.List;

// Extiende JpaRepository en lugar de MongoRepository
// Se especifica la entidad (Appointment) y el tipo de la clave primaria (Long)
public interface AppointmentRepository extends JpaRepository<Appointment, Long> {

    // Spring Data JPA sigue creando las consultas automáticamente a partir del nombre
    List<Appointment> findByPatientId(String patientId);
    List<Appointment> findByDoctorId(String doctorId);
}