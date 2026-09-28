<?php

// ---------------- DATABASE CONNECTION ----------------

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

    // Show database errors as exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Return database results as associative arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    header("Content-Type: application/json");
    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);

    exit;
}


// ---------------- CORS ----------------

$allowedOrigins = [
    "http://localhost:5500",
    "http://127.0.0.1:5500",
    "http://localhost:3000",
    "http://localhost:8000",
    "http://127.0.0.1:8000"
];

$requestOrigin = isset($_SERVER["HTTP_ORIGIN"])
    ? $_SERVER["HTTP_ORIGIN"]
    : "";

// Allow requests from trusted origins
if ($requestOrigin !== "" && in_array($requestOrigin, $allowedOrigins, true)) {

    header("Access-Control-Allow-Origin: " . $requestOrigin);
    header("Vary: Origin");

    header(
        "Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"
    );

    header("Access-Control-Allow-Headers: Content-Type");
}


// Handle browser preflight request
if (
    isset($_SERVER["REQUEST_METHOD"]) &&
    $_SERVER["REQUEST_METHOD"] === "OPTIONS"
) {
    http_response_code(204);
    exit;
}


// All responses are JSON
header("Content-Type: application/json");


// ---------------- SUCCESS RESPONSE ----------------

function send_success($message, $data = null, $statusCode = 200)
{
    http_response_code($statusCode);

    $response = [
        "success" => true,
        "message" => $message
    ];

    // Add data only when available
    if ($data !== null) {
        $response["data"] = $data;
    }

    echo json_encode($response);
    exit;
}


// ---------------- ERROR RESPONSE ----------------

function send_error($message, $statusCode = 400)
{
    http_response_code($statusCode);

    echo json_encode([
        "success" => false,
        "message" => $message
    ]);

    exit;
}


// ---------------- READ JSON INPUT ----------------

function read_json_input()
{
    $rawData = file_get_contents("php://input");

    $data = json_decode($rawData, true);

    if (is_array($data)) {
        return $data;
    }

    return [];
}


// ---------------- REQUIRED STRING ----------------

function get_required_string(
    $data,
    $key,
    $label,
    $maxLength = null
) {
    $value = "";

    if (isset($data[$key]) && is_scalar($data[$key])) {
        $value = trim((string) $data[$key]);
    }

    // Check if value is empty
    if ($value === "") {
        send_error($label . " is required", 400);
    }

    // Check maximum length
    if ($maxLength !== null && strlen($value) > $maxLength) {
        send_error(
            $label . " must be " . $maxLength . " characters or fewer",
            400
        );
    }

    return $value;
}


// ---------------- REQUIRED INTEGER ----------------

function get_required_int($data, $key, $label)
{
    // Check if value exists
    if (
        !isset($data[$key]) ||
        $data[$key] === "" ||
        $data[$key] === null
    ) {
        send_error($label . " is required", 400);
    }

    // Convert value to integer
    $value = filter_var(
        $data[$key],
        FILTER_VALIDATE_INT
    );

    // Check if valid integer
    if ($value === false) {
        send_error($label . " must be a valid integer", 400);
    }

    // ID should be greater than 0
    if ($value < 1) {
        send_error($label . " must be greater than 0", 400);
    }

    return $value;
}


// ---------------- DATE VALIDATION ----------------

function is_valid_date($value)
{
    $date = DateTime::createFromFormat(
        "Y-m-d",
        $value
    );

    return (
        $date !== false &&
        $date->format("Y-m-d") === $value
    );
}