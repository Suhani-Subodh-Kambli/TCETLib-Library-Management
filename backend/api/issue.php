<?php

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    send_error("Method not allowed", 405);
}

$input = read_json_input();

$studentName = get_required_string($input, "student_name", "Student name");
$bookId = get_required_int($input, "book_id", "Book ID");
$issueDate = get_required_string($input, "issue_date", "Issue date");

if (!is_valid_date($issueDate)) {
    send_error("Issue date must be a valid date in YYYY-MM-DD format", 400);
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "SELECT id, available_quantity
         FROM books
         WHERE id = ?
         FOR UPDATE"
    );
    $stmt->execute([$bookId]);
    $book = $stmt->fetch();

    if (!$book) {
        $pdo->rollBack();
        send_error("Book not found", 404);
    }

    if ((int) $book["available_quantity"] <= 0) {
        $pdo->rollBack();
        send_error("Book is not available", 409);
    }

    $stmt = $pdo->prepare(
        "INSERT INTO transactions (student_name, book_id, issue_date, status)
         VALUES (?, ?, ?, 'Issued')"
    );
    $stmt->execute([$studentName, $bookId, $issueDate]);

    $transactionId = (int) $pdo->lastInsertId();

    $stmt = $pdo->prepare(
        "UPDATE books
         SET available_quantity = available_quantity - 1
         WHERE id = ?"
    );
    $stmt->execute([$bookId]);

    $pdo->commit();

    send_success("Book issued successfully", ["transaction_id" => $transactionId], 201);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    send_error("Could not issue the book", 500);
}
