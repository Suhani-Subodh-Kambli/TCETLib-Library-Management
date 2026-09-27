<?php

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    send_error("Method not allowed", 405);
}

$input = read_json_input();

$studentName = get_required_string($input, "student_name", "Student name", 255);
$academicYear = get_required_string($input, "academic_year", "Academic year", 20);
$department = get_required_string($input, "department", "Department", 100);
$division = get_required_string($input, "division", "Division", 10);
$contact = get_required_string($input, "contact", "Contact number", 15);
$bookId = get_required_int($input, "book_id", "Book ID");
$issueDate = get_required_string($input, "issue_date", "Issue date");
$dueDate = get_required_string($input, "due_date", "Due date");

$remarks = isset($input["remarks"]) && is_scalar($input["remarks"])
    ? trim((string) $input["remarks"])
    : "";

if (!preg_match("/^[0-9]{10}$/", $contact)) {
    send_error("Contact number must contain exactly 10 digits", 400);
}

if (!is_valid_date($issueDate)) {
    send_error("Issue date must be a valid date in YYYY-MM-DD format", 400);
}

if (!is_valid_date($dueDate)) {
    send_error("Due date must be a valid date in YYYY-MM-DD format", 400);
}

if ($dueDate < $issueDate) {
    send_error("Due date cannot be before issue date", 400);
}

if (strlen($remarks) > 255) {
    send_error("Remarks must be 255 characters or fewer", 400);
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
        "INSERT INTO transactions
            (student_name, academic_year, department, division, contact,
             book_id, issue_date, due_date, remarks, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Issued')"
    );
    $stmt->execute([
        $studentName,
        $academicYear,
        $department,
        $division,
        $contact,
        $bookId,
        $issueDate,
        $dueDate,
        $remarks
    ]);

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
