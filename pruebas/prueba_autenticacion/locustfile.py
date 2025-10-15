from locust import HttpUser, task
import random
import string

class SimpleUser(HttpUser):
    @task()
    def register_user(self):
        ## Creación de una cadena de texto aleatoria.
        random_string = ''.join(random.choices(string.ascii_lowercase, k=10))
        #Usamos la cadena aleatoria que acabamos de crear para construir un email
        email = f"testuser_{random_string}@example.com"
        data = {
            "name": "Usuario de Prueba de Carga",
            "email": email,
            "password": "password123"
        }

        self.client.post("/api/create_user", json=data)

    @task
    def login_user(self):
        data = {
            "email": "isa@example.com",
            "password": "prueba"
        }

        self.client.post("/api/login", json=data)