<?php

$host = "localhost";
$dbname = "library_management";
$username = "root";
$password = "mahikasql@26";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );
} catch (PDOException $e) {
    header("Content-Type: application/json");
    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);

    exit;
}

$allowedOrigins = [
    "http://localhost:5500",
    "http://127.0.0.1:5500",
    "http://localhost:3000",
    "http://localhost:8000",
    "http://127.0.0.1:8000"
];

$requestOrigin = isset($_SERVER["HTTP_ORIGIN"]) ? $_SERVER["HTTP_ORIGIN"] : "";

if ($requestOrigin !== "" && in_array($requestOrigin, $allowedOrigins, true)) {
    header("Access-Control-Allow-Origin: " . $requestOrigin);
    header("Vary: Origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

header("Content-Type: application/json");

function send_success($message, $data = null, $statusCode = 200)
{
    http_response_code($statusCode);

    $payload = [
        "success" => true,
        "message" => $message
    ];

    if ($data !== null) {
        $payload["data"] = $data;
    }

    echo json_encode($payload);
    exit;
}

function send_error($message, $statusCode = 400)
{
    http_response_code($statusCode);

    echo json_encode([
        "success" => false,
        "message" => $message
    ]);
    exit;
}

function read_json_input()
{
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    return is_array($data) ? $data : [];
}

function get_required_string($data, $key, $label, $maxLength = null)
{
    $value = isset($data[$key]) && is_scalar($data[$key])
        ? trim((string) $data[$key])
        : "";

    if ($value === "") {
        send_error($label . " is required", 400);
    }

    if ($maxLength !== null && strlen($value) > $maxLength) {
        send_error($label . " must be " . $maxLength . " characters or fewer", 400);
    }

    return $value;
}

function get_required_int($data, $key, $label)
{
    if (!isset($data[$key]) || $data[$key] === "" || $data[$key] === null) {
        send_error($label . " is required", 400);
    }

    $value = filter_var($data[$key], FILTER_VALIDATE_INT);

    if ($value === false) {
        send_error($label . " must be a valid integer", 400);
    }

    if ($value < 1) {
        send_error($label . " must be greater than 0", 400);
    }

    return $value;
}

function is_valid_date($value)
{
    $date = DateTime::createFromFormat("Y-m-d", $value);

    return $date !== false && $date->format("Y-m-d") === $value;
}
