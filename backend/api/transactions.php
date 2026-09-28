<?php

require_once "../config/database.php";

$result = mysqli_query($conn, "
    SELECT t.student_name, t.academic_year, t.department,
           b.title AS book_title, t.issue_date, t.due_date, t.status
    FROM transactions t
    JOIN books b ON t.book_id = b.id
    ORDER BY t.id DESC
");

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode([
    "success" => true,
    "message" => "Transactions retrieved successfully",
    "data" => $data
]);

?>