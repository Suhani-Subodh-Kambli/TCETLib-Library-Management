# TCETLib – Library Management System

## Project Overview

TCETLib is a web-based Library Management System developed to manage book issuing and return records. It provides a simple interface for students to enter their details, select available books, and view recent library transactions.

The project demonstrates frontend development using HTML, CSS, and JavaScript, along with backend integration using PHP and MySQL.

## Objectives

* Develop a user-friendly interface for issuing books.
* Validate student details and form inputs using JavaScript.
* Display available books retrieved from the database.
* Store and retrieve book issue and return records.
* Demonstrate DOM manipulation, event handling, and database operations.

## Technologies Used

* **HTML5** – Structure of the web application.
* **CSS3** – Styling and responsive layout.
* **JavaScript** – Form validation, DOM manipulation, event handling, and API communication.
* **PHP** – Backend API and database operations.
* **MySQL** – Storage of book details and transaction records.
* **Fetch API** – Communication between JavaScript and PHP.

## Features

* Student information form.
* Academic year, department, division, and contact number fields.
* Book selection from available books.
* Automatic issue date and due date calculation.
* Client-side form validation.
* Contact number input restricted to 10 digits.
* Display of recent book transactions.
* Dynamic updates after a successful book issue.
* Clear form functionality.
* Responsive user interface.

## Project Structure

```text
TCETLib/
├── frontend/
│   ├── index.html
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
├── backend/
│   ├── README.md
│   ├── config/
│   │   └── database.php
│   ├── api/
│   │   ├── books.php
│   │   ├── dashboard.php
│   │   ├── issue.php
│   │   ├── return.php
│   │   └── transactions.php
│   └── database/
│       └── library_management.sql
└── README.md
```

## Installation and Setup

### Prerequisites

Install the following software:

* A modern web browser.
* XAMPP or another PHP environment.
* MySQL Server and MySQL Workbench.
* Visual Studio Code (recommended).

### Step 1: Clone the Repository

git clone https://github.com/Suhani-Subodh-Kambli/TCETLib-Library-Management.git
cd TCETLib


### Step 2: Set Up the Database

1. Open MySQL Workbench.
2. Connect to your MySQL Server.
3. Open `backend/database/library_management.sql`.
4. Execute the SQL script to create the database and required tables.
5. Verify that the `books` and `transactions` tables have been created.

### Step 3: Configure the Database Connection

Open `backend/config/database.php` and configure the connection details according to your MySQL setup.


$host = "localhost";
$dbname = "library_management";
$username = "root";
$password = "";


Update the username and password if your MySQL configuration is different.

### Step 4: Start the PHP Backend

Open a terminal in the `backend` directory and run:


php -S localhost:8000


Alternatively, configure the project to run using XAMPP's Apache server and the appropriate project directory.

### Step 5: Start the Frontend

Open the `frontend` folder in Visual Studio Code and launch `index.html` using the Live Server extension.

The frontend should be accessible at:

http://localhost:5500


The backend API runs at:

http://localhost:8000/api/


Ensure that the frontend API URL and backend CORS configuration match your local setup.

## JavaScript Implementation (S1)

The frontend JavaScript implementation focuses on:

* **DOM Manipulation:** Accessing and updating HTML elements dynamically.
* **Event Handling:** Responding to user input, date changes, and button clicks.
* **Form Validation:** Checking required fields, contact number format, book selection, and date validity.
* **Regular Expressions:** Removing non-digit characters and validating 10-digit contact numbers.
* **Fetch API:** Sending requests to PHP endpoints and retrieving database records.
* **Asynchronous JavaScript:** Using `async` and `await` to handle API requests.
* **Error Handling:** Using `try`, `catch`, and `finally` to handle failures and restore button states.

## Backend Implementation (S2)

The PHP backend provides API endpoints for:

* Managing book records.
* Retrieving available books.
* Issuing books.
* Returning issued books.
* Retrieving transaction records.
* Fetching dashboard statistics.

MySQL stores the book information, available quantities, and transaction records.

## Application Workflow

1. The frontend loads available books and recent transactions from the PHP API.
2. The student enters their details and selects a book.
3. JavaScript validates the form before submission.
4. The Fetch API sends the form data to the backend.
5. PHP validates the request and performs the database operation.
6. MySQL updates the relevant records.
7. JavaScript displays the response and refreshes the book list and transaction table.

## Validation

The frontend performs the following validations:

* Required student details must be entered.
* Academic year, department, division, and book must be selected.
* Contact number must contain exactly 10 digits.
* Non-digit characters are removed from the contact number input.
* Issue date and due date must be provided.
* Due date cannot be earlier than the issue date.

Backend validation is also necessary to protect database operations from invalid requests.

## Team Responsibilities

* **Suhani S Kambli (S1):** JavaScript implementation, form validation, DOM manipulation, event handling, and frontend-to-backend API integration.
* **Mahika S Chaurasiya (S2):** PHP backend development, MySQL database operations, and API implementation.

## Future Enhancements

* Student authentication and authorization.
* Search and filtering of books.
* Book return functionality through the frontend.
* Overdue book notifications.
* Transaction history filtering.
* Improved reporting and dashboard visualizations.


