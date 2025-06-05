from flask import Flask, request, jsonify
from flask_cors import CORS
import openai
import json

app = Flask(__name__)
CORS(app)

with open("erickprompt.json", "r") as f:
    prompt_data = json.load(f)

@app.route("/", methods=["POST"])
def chat():
    user_msg = request.json.get("message")
    if not user_msg:
        return jsonify({"reply": "No message received."}), 400

    messages = [
        {"role": "system", "content": prompt_data["systemPrompt"]},
        {"role": "user", "content": user_msg}
    ]

    response = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=messages
    )

    reply = response.choices[0].message["content"]
    return jsonify({"reply": reply})
