<?php

require_once __DIR__ . "/../config/database.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC");
    $books = $stmt->fetchAll();

    send_success("Books retrieved successfully", $books);
}

if ($method === "POST") {
    $input = read_json_input();

    $title = get_required_string($input, "title", "Title");
    $author = get_required_string($input, "author", "Author");
    $category = get_required_string($input, "category", "Category");
    $quantity = get_required_int($input, "quantity", "Quantity");

    $stmt = $pdo->prepare(
        "INSERT INTO books (title, author, category, quantity, available_quantity)
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->execute([$title, $author, $category, $quantity, $quantity]);

    $newBookId = (int) $pdo->lastInsertId();

    send_success("Book added successfully", ["id" => $newBookId], 201);
}

if ($method === "PUT") {
    $input = read_json_input();

    $id = get_required_int($input, "id", "Book ID");
    $title = get_required_string($input, "title", "Title");
    $author = get_required_string($input, "author", "Author");
    $category = get_required_string($input, "category", "Category");
    $quantity = get_required_int($input, "quantity", "Quantity");

    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch();

    if (!$book) {
        send_error("Book not found", 404);
    }

    $issuedCopies = (int) $book["quantity"] - (int) $book["available_quantity"];

    if ($issuedCopies < 0) {
        $issuedCopies = 0;
    }

    if ($quantity < $issuedCopies) {
        send_error("Quantity cannot be less than the number of currently issued copies", 400);
    }

    $availableQuantity = $quantity - $issuedCopies;

    $stmt = $pdo->prepare(
        "UPDATE books
         SET title = ?, author = ?, category = ?, quantity = ?, available_quantity = ?
         WHERE id = ?"
    );

    $stmt->execute([$title, $author, $category, $quantity, $availableQuantity, $id]);

    send_success("Book updated successfully");
}

if ($method === "DELETE") {
    $id = filter_var(isset($_GET["id"]) ? $_GET["id"] : null, FILTER_VALIDATE_INT);

    if ($id === false || $id === null || $id < 1) {
        send_error("Book ID is required", 400);
    }

    $stmt = $pdo->prepare("SELECT id FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch();

    if (!$book) {
        send_error("Book not found", 404);
    }

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) AS issued_count
         FROM transactions
         WHERE book_id = ? AND status = 'Issued'"
    );
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if ((int) $row["issued_count"] > 0) {
        send_error("Cannot delete a book that is currently issued", 409);
    }

    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);

    send_success("Book deleted successfully");
}

send_error("Method not allowed", 405);
