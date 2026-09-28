<?php

// ---------------- MySQL CONNECTION ----------------

$host = "localhost";
$dbname = "library_management";
$username = "root";
$password = "";
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
