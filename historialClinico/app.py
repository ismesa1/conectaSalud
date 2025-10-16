from flask import Flask, jsonify, request
from pymongo import MongoClient
from datetime import datetime

app = Flask(__name__)

client = MongoClient("mongodb://localhost:27017/")
db = client["conectasalud"]
historiales = db["historias"]

@app.route('/api/historias', methods=['POST'])
def agregar_historia():
    data = request.get_json()
    nuevo = {
        "paciente_id": data["paciente_id"],
        "medico_id": data.get("medico_id"),
        "diagnostico": data["diagnostico"],
        "tratamiento": data.get("tratamiento", ""),
        "notas": data.get("notas", ""),
        "fecha": datetime.now().isoformat()
    }
    historiales.insert_one(nuevo)
    return jsonify({"mensaje": "Historial creado correctamente"}), 201


@app.route('/api/historias/paciente/<paciente_id>', methods=['GET'])
def obtener_historial_por_paciente(paciente_id):
    registros = historiales.find({"paciente_id": paciente_id})
    resultado = []
    for r in registros:
        resultado.append({
            "id": str(r["_id"]),
            "medico_id": r.get("medico_id"),
            "diagnostico": r.get("diagnostico"),
            "tratamiento": r.get("tratamiento"),
            "fecha": r.get("fecha"),
            "notas": r.get("notas", "")
        })     
    return jsonify(resultado), 200

if __name__ == "__main__":
    app.run(debug=True, port=5000)
