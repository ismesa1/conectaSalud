from flask import Flask, jsonify, request
from pymongo import MongoClient
from datetime import datetime

app = Flask(__name__)

client = MongoClient("mongodb://127.0.0.1:27017/")
db = client["conectasalud"]
notificaciones = db["notificaciones"]
API_KEY = "ConectaSaludKey123"

@app.before_request
def verificar_api_key():
    api_key = request.headers.get("X-API-Key")
    if api_key != API_KEY:
        return jsonify({"error": "Acceso no autorizado"}), 401

@app.route('/api/notificaciones', methods=['POST'])
def agregar_notificacion():
    data = request.get_json()

    notificaciones.insert_one({
        "usuario_id": data.get("usuario_id"),
        "titulo": data.get("titulo"),
        "mensaje": data.get("mensaje"),
        "fecha": datetime.now().isoformat(),
        "estado": "pendiente"
    })

    return jsonify({
        "mensaje": "Notificación guardada correctamente"
    }), 201


@app.route('/api/notificaciones/<usuario_id>', methods=['GET'])
def listar_notificaciones(usuario_id):
    docs = notificaciones.find({"usuario_id": usuario_id})
    resultado = []
    for doc in docs:
        resultado.append({
            "id": str(doc["_id"]),
            "titulo": doc["titulo"],
            "mensaje": doc["mensaje"],
            "fecha": doc["fecha"],
            "estado": doc["estado"]
        })
    return jsonify(resultado), 200


if __name__ == '__main__':
    app.run(host="0.0.0.0", debug=True, port=5001)
