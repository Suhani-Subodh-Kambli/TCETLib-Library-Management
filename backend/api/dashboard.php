<?php

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    send_error("Method not allowed", 405);
}

$stmt = $pdo->query(
    "SELECT
        (SELECT COUNT(*) FROM books) AS total_books,
        (SELECT COALESCE(SUM(available_quantity), 0) FROM books) AS available_books,
        (SELECT COUNT(*) FROM transactions WHERE status = 'Issued') AS issued_books,
        (SELECT COUNT(*) FROM transactions) AS total_transactions"
);

$stats = $stmt->fetch();

send_success("Dashboard statistics retrieved successfully", [
    "total_books" => (int) $stats["total_books"],
    "available_books" => (int) $stats["available_books"],
    "issued_books" => (int) $stats["issued_books"],
    "total_transactions" => (int) $stats["total_transactions"]
]);
