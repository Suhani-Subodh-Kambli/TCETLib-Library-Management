<?php

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    send_error("Method not allowed", 405);
}

$input = read_json_input();

$transactionId = get_required_int($input, "transaction_id", "Transaction ID");

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "SELECT id, book_id, status
         FROM transactions
         WHERE id = ?
         FOR UPDATE"
    );
    $stmt->execute([$transactionId]);
    $transaction = $stmt->fetch();

    if (!$transaction) {
        $pdo->rollBack();
        send_error("Transaction not found", 404);
    }

    if ($transaction["status"] !== "Issued") {
        $pdo->rollBack();
        send_error("This book has already been returned", 409);
    }

    $stmt = $pdo->prepare(
        "UPDATE transactions
         SET return_date = CURDATE(), status = 'Returned'
         WHERE id = ? AND status = 'Issued'"
    );
    $stmt->execute([$transactionId]);

    if ($stmt->rowCount() === 0) {
        $pdo->rollBack();
        send_error("This book has already been returned", 409);
    }

    $stmt = $pdo->prepare(
        "UPDATE books
         SET available_quantity = available_quantity + 1
         WHERE id = ?"
    );
    $stmt->execute([$transaction["book_id"]]);

    $pdo->commit();

    send_success("Book returned successfully");
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    send_error("Could not return the book", 500);
}
