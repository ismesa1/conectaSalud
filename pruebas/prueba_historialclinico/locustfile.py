from locust import HttpUser, task
import random

class HistorialCrudUser(HttpUser):

    @task
    def create_historia(self):
        paciente_id = f"paciente_{random.randint(1, 1000)}"
        data = {
            "paciente_id": paciente_id,
            "medico_id": "medico_flask_1",
            "diagnostico": "Chequeo de rutina",
            "tratamiento": "Sin novedades.",
            "notas": "Prueba de carga POST."
        }
        self.client.post("/api/historias", json=data)

    @task
    def get_historia(self):
        paciente_id = f"paciente_{random.randint(1, 50)}"
        self.client.get(f"/api/historias/paciente/{paciente_id}", name="/api/historias/paciente/[id]")

    @task
    def update_historia(self):
        historia_id = "68f0552e8dcd86ba61538aae" 
        data = {
            "diagnostico": "Diagnóstico Actualizado por Locust",
            "notas": "El paciente muestra mejoría."
        }
        self.client.put(f"/api/historias/{historia_id}", json=data, name="/api/historias/[id]")

    @task
    def delete_historia(self):
        #Funcionará una vez por ID.
        historia_id = "68f0552e8dcd86ba61538aaf" 
        self.client.delete(f"/api/historias/{historia_id}", name="/api/historias/[id]")
