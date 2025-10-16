from locust import HttpUser, task
import random

class SimpleUser(HttpUser):
    @task
    def generate_pdf_report(self):
        patient_id = random.randint(1, 50)
        self.client.get(
            f"/api/reports/patient/{patient_id}/pdf",
            name="/api/reports/patient/[id]/pdf" 
        )

    @task
    def generate_excel_report(self):
        patient_id = random.randint(1, 50)
        self.client.get(
            f"/api/reports/patient/{patient_id}/excel",
            name="/api/reports/patient/[id]/excel"
        )