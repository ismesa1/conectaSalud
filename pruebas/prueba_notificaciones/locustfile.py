from locust import HttpUser, task
import random

class SimpleUser(HttpUser):
    @task
    def create_notificacion(self):
        usuario_id = f"usuario_{random.randint(1, 1000)}"
        data = {
            "usuario_id": usuario_id,
            "titulo": "Recordatorio de Cita",
            "mensaje": f"Tienes una cita programada para mañana."
        }
        self.client.post("/api/notificaciones", json=data)

    @task
    def get_notificaciones(self):
        usuario_id = f"usuario_{random.randint(1, 50)}"
        self.client.get(f"/api/notificaciones/{usuario_id}", name="/api/notificaciones/[id]")
