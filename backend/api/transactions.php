<?php

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    send_error("Method not allowed", 405);
}

$query = "SELECT
            t.id,
            t.student_name,
            t.book_id,
            b.title AS book_title,
            t.issue_date,
            t.return_date,
            t.status
          FROM transactions t
          JOIN books b ON t.book_id = b.id";

if (isset($_GET["id"])) {
    $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if ($id === false || $id < 1) {
        send_error("Transaction ID is required", 400);
    }

    $stmt = $pdo->prepare($query . " WHERE t.id = ?");
    $stmt->execute([$id]);
    $transaction = $stmt->fetch();

    if (!$transaction) {
        send_error("Transaction not found", 404);
    }

    send_success("Transaction retrieved successfully", $transaction);
}

$stmt = $pdo->query($query . " ORDER BY t.id DESC");
$transactions = $stmt->fetchAll();

send_success("Transactions retrieved successfully", $transactions);
