<?php

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    send_error("Method not allowed", 405);
}

$stmt = $pdo->query(
    "SELECT
        t.id,
        t.student_name,
        t.academic_year,
        t.department,
        t.division,
        t.contact,
        t.book_id,
        b.title AS book_title,
        t.issue_date,
        t.due_date,
        t.remarks,
        t.return_date,
        t.status
     FROM transactions t
     JOIN books b ON t.book_id = b.id
     ORDER BY t.id DESC"
);

$transactions = $stmt->fetchAll();

send_success("Transactions retrieved successfully", $transactions);
