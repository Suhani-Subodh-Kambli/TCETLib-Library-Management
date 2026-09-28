<?php

require_once __DIR__ . "/../config/database.php";

// Only POST request is allowed
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    send_error("Method not allowed", 405);
}

// Get input data
$input = read_json_input();

// Get required fields
$studentName = get_required_string($input, "student_name", "Student name", 255);
$academicYear = get_required_string($input, "academic_year", "Academic year", 20);
$department = get_required_string($input, "department", "Department", 100);
$division = get_required_string($input, "division", "Division", 10);
$contact = get_required_string($input, "contact", "Contact number", 15);
$bookId = get_required_int($input, "book_id", "Book ID");
$issueDate = get_required_string($input, "issue_date", "Issue date");
$dueDate = get_required_string($input, "due_date", "Due date");

// Remarks is optional
$remarks = "";

if (isset($input["remarks"]) && is_scalar($input["remarks"])) {
    $remarks = trim((string) $input["remarks"]);
}


// ---------------- VALIDATION ----------------

// Check contact number
if (!preg_match("/^[0-9]{10}$/", $contact)) {
    send_error("Contact number must contain exactly 10 digits", 400);
}

// Check issue date
if (!is_valid_date($issueDate)) {
    send_error("Issue date must be a valid date in YYYY-MM-DD format", 400);
}

// Check due date
if (!is_valid_date($dueDate)) {
    send_error("Due date must be a valid date in YYYY-MM-DD format", 400);
}

// Due date cannot be before issue date
if ($dueDate < $issueDate) {
    send_error("Due date cannot be before issue date", 400);
}

// Check remarks length
if (strlen($remarks) > 255) {
    send_error("Remarks must be 255 characters or fewer", 400);
}


// ---------------- DATABASE ----------------

try {

    // Start transaction
    $pdo->beginTransaction();


    // Check book availability
    $stmt = $pdo->prepare(
        "SELECT id, available_quantity
         FROM books
         WHERE id = ?
         FOR UPDATE"
    );

    $stmt->execute([$bookId]);

    $book = $stmt->fetch();


    // Book does not exist
    if (!$book) {
        $pdo->rollBack();
        send_error("Book not found", 404);
    }


    // No copies available
    if ((int) $book["available_quantity"] <= 0) {
        $pdo->rollBack();
        send_error("Book is not available", 409);
    }


    // Add transaction
    $stmt = $pdo->prepare(
        "INSERT INTO transactions
        (
            student_name,
            academic_year,
            department,
            division,
            contact,
            book_id,
            issue_date,
            due_date,
            remarks,
            status
        )
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


    // Get newly created transaction ID
    $transactionId = (int) $pdo->lastInsertId();


    // Reduce available book quantity by 1
    $stmt = $pdo->prepare(
        "UPDATE books
         SET available_quantity = available_quantity - 1
         WHERE id = ?"
    );

    $stmt->execute([$bookId]);


    // Save all changes
    $pdo->commit();


    // Send success response
    send_success(
        "Book issued successfully",
        ["transaction_id" => $transactionId],
        201
    );


} catch (Exception $e) {

    // Undo changes if something goes wrong
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    send_error("Could not issue the book", 500);
}