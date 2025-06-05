<?php
// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Read the input
$input = json_decode(file_get_contents("php://input"), true);
if (!isset($input['message']) || empty(trim($input['message']))) {
    echo json_encode(["reply" => "No message received"]);
    exit;
}

// Your OpenAI API Key
$apiKey = "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"; // Replace with your real key

// Prepare the OpenAI request
$url = "https://api.openai.com/v1/chat/completions";
$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "You are Erick, a helpful AI assistant."],
        ["role" => "user", "content" => $input['message']]
    ],
    "temperature" => 0.7,
    "max_tokens" => 1000,
];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ],
    CURLOPT_POSTFIELDS => json_encode($data),
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

// Handle errors
if ($error) {
    echo json_encode(["reply" => "Error: $error"]);
    exit;
}

// Parse response
$responseData = json_decode($response, true);
$reply = $responseData['choices'][0]['message']['content'] ?? "Error: No reply from AI";
echo json_encode(["reply" => $reply]);
