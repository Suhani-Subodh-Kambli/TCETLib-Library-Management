<?php

require_once "../config/database.php";

$input = json_decode(file_get_contents("php://input"), true);

$studentName = $input["student_name"];
$academicYear = $input["academic_year"];
$department = $input["department"];
$division = $input["division"];
$contact = $input["contact"];
$bookId = $input["book_id"];
$issueDate = $input["issue_date"];
$dueDate = $input["due_date"];
$remarks = $input["remarks"];

// Check book
$result = mysqli_query($conn, "SELECT * FROM books WHERE id = $bookId");
$book = mysqli_fetch_assoc($result);

if (!$book) {
    die("Book not found");
}

if ($book["available_quantity"] <= 0) {
    die("Book is not available");
}

// Save issue record
$sql = "INSERT INTO transactions
(student_name, academic_year, department, division, contact,
book_id, issue_date, due_date, remarks, status)
VALUES
('$studentName', '$academicYear', '$department', '$division',
'$contact', '$bookId', '$issueDate', '$dueDate', '$remarks', 'Issued')";

if (!mysqli_query($conn, $sql)) {
    die("Error: " . mysqli_error($conn));
}

// Reduce book quantity
mysqli_query($conn,
    "UPDATE books
     SET available_quantity = available_quantity - 1
     WHERE id = $bookId"
);

echo json_encode([
    "success" => true,
    "message" => "Book issued successfully"
]);

?>