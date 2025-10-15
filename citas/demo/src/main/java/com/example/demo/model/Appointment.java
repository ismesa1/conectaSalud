package com.example.demo.model;

// En el paquete model
import jakarta.persistence.*;
import lombok.Data;
import java.time.LocalDateTime;

@Data // Anotación de Lombok
@Entity // Le dice a JPA que esta clase es una tabla en la BD
@Table(name = "appointments") // Nombre de la tabla
public class Appointment {

    @Id // Marca este campo como la clave primaria
    @GeneratedValue(strategy = GenerationType.IDENTITY) // Autoincremental
    private Long id;

    @Column(nullable = false) // Columna no nula
    private String patientId;

    @Column(nullable = false)
    private String doctorId;

    @Column(name = "appointment_date", nullable = false)
    private LocalDateTime appointmentDate;

    private String status; // Ej: "AGENDADA", "COMPLETADA", "CANCELADA"

    @Lob // Para textos largos
    private String notes;
}