<?php

// Books hardcoded

$books = [
    [
        "id" => 8,
        "title" => "Technical Writing Skills",
        "author" => "Raman Mehta",
        "category" => "General",
        "quantity" => 2,
        "available_quantity" => 2
    ],
    [
        "id" => 7,
        "title" => "Electronics Devices and Circuits",
        "author" => "Salivahanan",
        "category" => "Electronics",
        "quantity" => 3,
        "available_quantity" => 2
    ],
    [
        "id" => 6,
        "title" => "Artificial Intelligence: A Modern Approach",
        "author" => "Stuart Russell",
        "category" => "AI and Data Science",
        "quantity" => 5,
        "available_quantity" => 3
    ],
    [
        "id" => 5,
        "title" => "Operating System Concepts",
        "author" => "Silberschatz",
        "category" => "Computer Science",
        "quantity" => 4,
        "available_quantity" => 4
    ],
    [
        "id" => 4,
        "title" => "Data Structures and Algorithms",
        "author" => "Narasimha Karumanchi",
        "category" => "Computer Science",
        "quantity" => 3,
        "available_quantity" => 3
    ],
    [
        "id" => 3,
        "title" => "Introduction to DBMS",
        "author" => "Smith William",
        "category" => "Databases",
        "quantity" => 6,
        "available_quantity" => 6
    ],
    [
        "id" => 2,
        "title" => "PHP and MySQL Foundations",
        "author" => "Alex Johnson",
        "category" => "Programming",
        "quantity" => 4,
        "available_quantity" => 3
    ],
    [
        "id" => 1,
        "title" => "JavaScript Basics",
        "author" => "John Smith",
        "category" => "Programming",
        "quantity" => 5,
        "available_quantity" => 5
    ]
];

echo json_encode([
    "success" => true,
    "message" => "Books retrieved successfully",
    "data" => $books
]);
