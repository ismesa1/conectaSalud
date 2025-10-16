from locust import HttpUser, task
from datetime import datetime

# CitasCrudUser es el usuario virtual que probará el CRUD completo.
class SimpleUser(HttpUser):
    @task
    def create_appointment(self):
        data = {
            "patientId": "paciente_crud_1",
            "doctorId": "doctor_crud_1",
            "appointmentDate": datetime.now().isoformat(),
            "status": "AGENDADA",
            "notes": "Cita de prueba CRUD."
        }
        self.client.post("/api/appointments", json=data)

    @task
    def get_all_appointments(self):
        self.client.get("/api/appointments")

    @task
    def update_appointment(self):
        data = {
            "patientId": "paciente_actualizado",
            "doctorId": "doctor_actualizado",
            "appointmentDate": datetime.now().isoformat(),
            "status": "REPROGRAMADA",
            "notes": "Cita actualizada por prueba de carga."
        }
        self.client.put("/api/appointments/1", json=data)

    @task
    def delete_appointment(self):
        self.client.delete("/api/appointments/2")