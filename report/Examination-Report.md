# An Innovative Examination Report Of

# Web Programming

---

## TCETLib – Library Management System

---

**Under the guidance of**
**Mrs. Apeksha Waghmare**

**A.Y 2025-26**

---

**By**

| Name | Class & Division | Roll No. |
| ---- | ---------------- | -------- |
| Mahika | TE-IT | _________ |
| Suhani Subodh Kambli | TE-IT | _________ |

---

## Table of Contents

| Sr No. | Topic | Page No. |
| ------ | ----- | -------- |
| 1.0 | Abstract | ___ |
| 2.0 | Introduction | ___ |
| 3.0 | Literature Review | ___ |
| 4.0 | System Requirements | ___ |
| 5.0 | System Design & Implementation | ___ |
| 6.0 | Results and Screenshots | ___ |
| 7.0 | Conclusion | ___ |

---
---

## 1.0 Abstract

TCETLib – Library Management System is a web-based application developed for managing the book issue process of a college library. The system replaces the traditional paper register with a centralised digital platform where students can submit book issue requests together with their complete academic and contact details. The application is built as a three-layer system consisting of an HTML5, CSS3 and JavaScript frontend, a PHP backend that exposes RESTful API endpoints, and a MySQL database for permanent data storage.

Through a structured form, a student enters their full name, academic year, department, division and 10-digit contact number, then selects an available book, the issue date, the due date and optional remarks. On submission the data is sent as JSON to the PHP backend, where every field is validated a second time. The backend verifies that the book exists and that at least one copy is free, stores the issue record, and decreases the book's available quantity in the same database transaction so that the stock count and the record can never become inconsistent.

All issue records are displayed in a transaction history table showing the student name, department, academic year, book title, issue date, due date and status. The frontend uses JavaScript events, DOM manipulation and the Fetch API to load books, render tables and display success or error messages without reloading the page. Security is maintained through PDO prepared statements, server-side validation, restricted CORS and credential files that are never exposed to the browser.

The overall system is designed to be simple, secure, responsive and easy to understand — a complete demonstration of client-side and server-side web technologies working together through standard JSON APIs.

---

## 2.0 Introduction

### 2.1 Background

Every college library maintains a record of which student has borrowed which book and when it is expected back. In most institutions this process is still handled with a physical register: the librarian writes the student's details and the book's name by hand, and the available stock is corrected manually.

This traditional approach has several problems:

- Records are difficult to search and organise.
- Human error leads to wrong or missing entries.
- Available stock is often calculated incorrectly.
- Finding the current holder of a book requires flipping through pages.
- No summary or quick overview of library activity is available.

### 2.2 Problem Statement

There is a need for a simple digital system that records book issues centrally, always knows how many copies of each book are free, and lets students and library staff view the issue history instantly.

### 2.3 Proposed System

TCETLib provides a web-based issue management platform. A student fills a structured form in the browser; the request travels as JSON to a PHP API; the API validates the data, checks availability and stores everything in MySQL. The frontend immediately reloads the book list and transaction table from the API, so the displayed data always reflects the actual database state.

### 2.4 Objectives

The primary objectives of the project are:

1. To develop a centralised book issue registration system.
2. To store issue records permanently and securely in MySQL.
3. To maintain the total quantity and available quantity of every book automatically.
4. To record complete student details — name, academic year, department, division and contact number.
5. To prevent the issue of a book when no copy is currently available.
6. To validate all data on both the frontend and the backend.
7. To display complete issue history with student, book, date and status information.
8. To keep the frontend and backend independent, connected only through defined JSON APIs.
9. To implement secure database access using prepared statements.
10. To create a responsive, user-friendly interface with clear feedback messages.

### 2.5 Scope

The scope of the project covers book issue registration, availability management and transaction history display. The system is designed as a single-page style portal that runs on a local server and can be extended later with return processing, dashboards and book management.

---

## 3.0 Literature Review

### 3.1 Traditional Library Record Systems

Traditional hostel-style or library management depends on paper registers, notice boards and verbal communication. While these methods can be useful for very small volumes, they do not provide searchable centralised records, automatic stock control or any mechanism for quick reporting. Errors in hand-written entries are common and difficult to detect.

### 3.2 Web-Based Management Systems

Web-based management systems improve this process by allowing users to submit information electronically and maintain structured records in a database. A browser-based interface removes the need for special client software: any device with a modern browser can access the system. Modern applications commonly separate the frontend and backend so that each can be developed and maintained independently.

### 3.3 Technology Stack Selection

Several stacks were considered:

- **MERN (MongoDB, Express, React, Node.js):** a popular full-JavaScript stack. However, book stock and issue records are structured, related data — a relational model fits naturally.
- **Java / J2EE:** powerful but heavy for a project of this size, requiring application servers and build tooling.
- **PHP + MySQL:** PHP is purpose-built for server-side web development, runs on any standard Apache/PHP/MySQL installation, and needs no framework or build step for a small application. MySQL provides tables, numeric aggregation and foreign keys that directly model library data.

PHP with MySQL was therefore selected. PDO (PHP Data Objects) was chosen over the older `mysqli` interface because it offers a uniform API, true prepared statements and clear exception handling.

### 3.4 Communication: REST and JSON

REST over HTTP with JSON payloads has become the standard for browser–server communication. Every endpoint in this project returns the same JSON envelope:

```json
{ "success": true, "message": "...", "data": {} }
```

Because the format is uniform, the frontend needs only one condition (`if (!result.success)`) to decide whether to show a success or an error message — no parsing of server-specific formats is required.

### 3.5 Security Principles

Two well-known principles guided the design:

1. **Server-side validation is mandatory.** Client-side JavaScript validation improves user experience but can be bypassed using browser developer tools or direct API calls with Postman/curl. Only PHP validation truly protects the database.
2. **Prepared statements prevent SQL injection.** By separating SQL structure from user data, placeholders (`?`) ensure input can never change the meaning of a query.

### 3.6 Data Consistency

Issuing one book modifies two tables: a row is inserted into `transactions` and `available_quantity` is decreased in `books`. Database transaction theory states that such multi-table operations must be atomic — all or nothing. PHP's `beginTransaction()`, `commit()` and `rollBack()` implement exactly this guarantee.

### 3.7 Gap and Contribution

Existing library software is often complex, commercial and hard to explain in an academic setting. TCETLib contributes a minimal, transparent implementation that demonstrates every layer of a modern web application — form design, validation, asynchronous API calls, REST endpoints, SQL with relationships, and transaction-safe updates — in code short enough to study completely.

---

## 4.0 System Requirements

### 4.1 Hardware Requirements

- Computer or laptop
- Minimum 4 GB RAM
- Dual-core processor or better
- Minimum 2 GB available storage
- Internet connection is optional — the system runs fully on the local machine

### 4.2 Software Requirements

- Windows, macOS, or Linux
- PHP 8.1 or later
- MySQL 8.0 or later (XAMPP/WAMP also acceptable)
- Visual Studio Code or another code editor
- Modern web browser such as Google Chrome, Microsoft Edge, or Mozilla Firefox
- MySQL Workbench (optional, for viewing tables)
- A local web server (Apache via XAMPP, or the PHP built-in development server)

### 4.3 Technologies Used

| Technology | Purpose |
| ---------- | ------- |
| HTML5 | Application structure, forms and semantic sections |
| CSS3 | Styling, cards, badges and responsive layout |
| JavaScript (ES6) | Application logic, events, DOM manipulation, validation |
| Fetch API | Asynchronous frontend–backend API communication |
| PHP | Server-side logic and REST API endpoints |
| MySQL | Relational database storage |
| PDO | Database interface with prepared statements and transactions |
| JSON | Data exchange format between frontend and backend |
| HTTP Status Codes | Clear success/error signalling (200, 201, 400, 404, 405, 409) |
| CORS | Controlled cross-origin access between local ports |

### 4.4 Functional Requirements

The system should allow users to:

1. View the list of books fetched from the database.
2. See only books with at least one available copy in the book dropdown.
3. Enter the student's full name.
4. Select the academic year (FE / SE / TE / BE).
5. Select the department from a predefined list.
6. Select the division (A / B / C / D).
7. Enter a 10-digit contact number (digits only).
8. Select the issue date.
9. Have the due date automatically set to issue date + 7 days.
10. Enter optional remarks.
11. Validate all required fields in the browser before submission.
12. Submit the issue request to the PHP API as JSON.
13. Re-validate every field on the backend and store the record in MySQL.
14. Decrease the book's available quantity at the same instant the record is created.
15. Receive `409` when a book has no free copies, without creating any record.
16. View all issue transactions with student, book, dates and status.
17. Receive clear success and error messages returned by the API.

### 4.5 Non-Functional Requirements

- **Responsive user interface** — usable on desktop, tablet and smaller screens.
- **Simple and intuitive navigation** — three clearly numbered sections.
- **Dual validation** — frontend for experience, backend for security.
- **Reliable database communication** — prepared statements and transactions.
- **Maintainable code structure** — plain files, no framework, clear separation of concerns.
- **Fast API responses** — single-query endpoints and indexed primary keys.
- **Secure credential handling** — database password exists only in `config/database.php`.
- **Clear frontend/backend separation** — connected only through the JSON API contract.

---

## 5.0 System Design & Implementation

### 5.1 System Architecture

TCETLib follows a three-layer architecture:

**Frontend Layer** — Built with HTML5, CSS3 and JavaScript. It renders the issue form, loads the book dropdown, validates input, sends requests with Fetch and displays the transaction table through DOM manipulation.

**Backend Layer** — Built with plain PHP. It exposes three REST endpoints, validates incoming JSON, executes prepared SQL statements inside database transactions where needed, and returns JSON with appropriate HTTP status codes.

**Database Layer** — MySQL hosts the `library_management` database containing two related tables: `books` and `transactions`.

**Request flow:**

```text
Student → Browser (HTML/CSS/JS) → Fetch API (JSON) → PHP API → PDO → MySQL
```

**Retrieval flow:**

```text
MySQL → PDO → PHP API (JSON) → Fetch → DOM (dropdown/table) → User
```

### 5.2 Frontend Implementation

The page contains three cards inside a header–main–footer layout:

1. **Student Information** — full name, academic year, department, division, contact number.
2. **Book Details** — book dropdown (loaded from the API), issue date, due date, remarks, *Issue Book* and *Clear Form* buttons, and the message area.
3. **Recent Transactions** — a seven-column table: Student, Department, Year, Book, Issue Date, Due Date, Status, plus a record counter.

**JavaScript events used:**

| Event | Element | Purpose |
| ----- | ------- | ------- |
| `input` | contact field | Accepts digits only, maximum 10 |
| `change` | issue date | Recalculates due date as issue date + 7 days |
| `click` | Issue button | Validates the form and sends the POST request |
| `click` | Clear button | Resets all fields |
| page load | window | Fetches books and transactions on start-up |

**DOM manipulation performed:** building `<option>` elements for the dropdown, creating `<tr>` elements for each transaction, updating the record counter, showing/hiding messages, and clearing the form after a successful submission.

**Client-side validation rules:**

- Name, year, department, division, book and both dates are required.
- Contact must match exactly 10 digits.
- Due date cannot be earlier than the issue date.

### 5.3 Backend Implementation

The backend exposes exactly three endpoints:

| Endpoint | Method | Purpose |
| -------- | ------ | ------- |
| `/api/books.php` | GET | Returns all books for the dropdown |
| `/api/issue.php` | POST | Validates and stores one issue record |
| `/api/transactions.php` | GET | Returns all transactions joined with book titles |

Any other HTTP method on these endpoints is rejected with `405 Method not allowed`.

**Issue operation pipeline (`api/issue.php`):**

```text
Receive JSON body
      ↓
Validate all 9 fields (required, length, format)
      ↓
Book exists?  ── No ──→ 404 "Book not found"
      ↓ Yes
available_quantity > 0?  ── No ──→ 409 "Book is not available"
      ↓ Yes
Begin DB transaction
      ↓
SELECT book FOR UPDATE   (row lock — blocks concurrent issue of last copy)
      ↓
INSERT INTO transactions (...)
      ↓
UPDATE books SET available_quantity = available_quantity - 1
      ↓
COMMIT  →  201 "Book issued successfully"
```

Any failure triggers `rollBack()`, restoring the database to its original state.

**Shared configuration (`config/database.php`)** provides the PDO connection (error mode: exceptions, fetch mode: associative arrays), CORS headers for allowed origins, and helper functions for JSON responses and validation — so every endpoint behaves identically.

**Uniform response format:**

```json
{ "success": true, "message": "Book issued successfully", "data": { "transaction_id": 7 } }
```

```json
{ "success": false, "message": "Book is not available" }
```

**Status codes used:**

| Code | Meaning |
| ---- | ------- |
| 200 | Successful GET request |
| 201 | Resource created (issue record) |
| 400 | Invalid or missing input |
| 404 | Book not found |
| 405 | Method not allowed |
| 409 | Conflict — book unavailable |
| 500 | Server/database error (clean message only) |

### 5.4 Database Design

**Table: `books`**

| Column | Type | Key | Description |
| ------ | ---- | --- | ----------- |
| id | INT AUTO_INCREMENT | PK | Unique book identifier |
| title | VARCHAR(255) | — | Book title |
| author | VARCHAR(255) | — | Author name |
| category | VARCHAR(100) | — | Book category |
| quantity | INT | — | Total copies owned by the library |
| available_quantity | INT | — | Copies currently free to issue |
| created_at | TIMESTAMP | — | Record creation time |

**Table: `transactions`**

| Column | Type | Key | Description |
| ------ | ---- | --- | ----------- |
| id | INT AUTO_INCREMENT | PK | Unique transaction identifier |
| student_name | VARCHAR(255) | — | Student issuing the book |
| academic_year | VARCHAR(20) | — | FE / SE / TE / BE |
| department | VARCHAR(100) | — | Student department |
| division | VARCHAR(10) | — | Class division |
| contact | VARCHAR(15) | — | 10-digit contact number |
| book_id | INT | FK | References `books.id` |
| issue_date | DATE | — | Date the book was issued |
| due_date | DATE | — | Expected return date |
| remarks | VARCHAR(255) | — | Optional remarks |
| return_date | DATE | — | Actual return date, if returned |
| status | ENUM('Issued','Returned') | — | Current status |
| created_at | TIMESTAMP | — | Record creation time |

**Relationship (ER description):**

```text
books (1) ──────< transactions (∞)
books.id  ←──  transactions.book_id   FOREIGN KEY, ON DELETE RESTRICT
```

The foreign key ensures a transaction can never reference a non-existent book; `ON DELETE RESTRICT` blocks deletion of a book that is referenced.

**Why two quantity columns?** `quantity` represents total copies owned, `available_quantity` represents copies free right now. Example: total 5, available 3 → 2 copies are currently issued.

### 5.5 Issue Transaction Logic (Consistency)

Because issuing changes two tables, the operation is wrapped in a PDO transaction:

```php
$pdo->beginTransaction();
// insert transaction row
// update available_quantity
$pdo->commit();      // both succeed
// on any exception → $pdo->rollBack();  nothing changes
```

The book row is additionally locked with `SELECT ... FOR UPDATE`, so if two students try to grab the last copy simultaneously, the second request waits and then receives `409 Book is not available` — the stock can never go negative.

### 5.6 Status Management

Each transaction carries a status:

- **Issued** — the book is currently with the student; `return_date` is empty. Displayed with an *issued* badge.
- **Returned** — the book has been returned; `return_date` is filled. Displayed with a *returned* badge.

Colour-coded badges in the table make the state understandable at a glance.

### 5.7 Security and Configuration

1. **Prepared statements** — every query containing user input uses `?` placeholders, preventing SQL injection.
2. **Server-side validation** — required fields, exact lengths, 10-digit contact, valid `YYYY-MM-DD` dates and `due_date ≥ issue_date` are all re-checked in PHP.
3. **Credential isolation** — the MySQL password exists only in `backend/config/database.php`; no JavaScript file contains database details.
4. **Clean error messages** — raw PDO exceptions are caught and converted to JSON; no SQL text is ever sent to the browser.
5. **Restricted CORS** — only explicitly listed local origins receive `Access-Control-Allow-Origin`; the wildcard `*` is not used.

---

## 6.0 Results and Screenshots

### 6.1 Testing Approach

Each API was tested manually through browser, curl and direct HTTP calls, and the complete form flow was verified end-to-end. Client-side checks were confirmed in the browser console (JavaScript syntax verified with `node --check`).

### 6.2 Functional Test Results

| # | Test Case | Expected Result | Actual Result |
| - | --------- | --------------- | ------------- |
| 1 | Open the issue page | Dropdown loads books from API | Pass — 8 seed books loaded |
| 2 | Submit issue with all 9 fields | `201`, record saved, availability −1 | Pass — `"Book issued successfully"` |
| 3 | Submit with empty department | `400` with message | Pass — `"Department is required"` |
| 4 | Contact with fewer than 10 digits | `400` with message | Pass — `"Contact number must contain exactly 10 digits"` |
| 5 | Due date before issue date | `400` with message | Pass — `"Due date cannot be before issue date"` |
| 6 | Missing academic year | `400` with message | Pass — `"Academic year is required"` |
| 7 | Issue a book with 0 availability | `409`, no record created | Pass — `"Book is not available"` |
| 8 | GET books | `200` with all fields | Pass — 8 rows with `available_quantity` |
| 9 | GET transactions | `200`, all student + book fields | Pass — JOIN returns `book_title` |
| 10 | Wrong HTTP method (POST on books) | `405` | Pass — `"Method not allowed"` |
| 11 | PHP syntax check (`php -l`) on all files | No errors | Pass — 4/4 files clean |
| 12 | Cross-origin call from Live Server (5500) | Allowed by CORS | Pass — `204` preflight, `200` response |

### 6.3 Sample API Responses

**Successful issue (201):**

```json
{
    "success": true,
    "message": "Book issued successfully",
    "data": { "transaction_id": 7 }
}
```

**Validation error (400):**

```json
{ "success": false, "message": "Department is required" }
```

**Unavailable book (409):**

```json
{ "success": false, "message": "Book is not available" }
```

**Books list (200) — extracted row:**

```json
{
    "id": 1,
    "title": "JavaScript Basics",
    "author": "John Smith",
    "category": "Programming",
    "quantity": 5,
    "available_quantity": 5
}
```

**Transactions list (200) — extracted row:**

```json
{
    "student_name": "Mahika Patil",
    "academic_year": "TE",
    "department": "Information Technology",
    "book_title": "JavaScript Basics",
    "issue_date": "2026-09-20",
    "due_date": "2026-09-27",
    "status": "Returned"
}
```

### 6.4 Screenshots

The following screenshots are to be inserted while preparing the hard copy:

- **Fig 1.** TCETLib home page — header, Student Information card and Book Details card
- **Fig 2.** Book dropdown loaded from the database showing books with available copies
- **Fig 3.** Completed issue form before submission
- **Fig 4.** Success message displayed after issuing a book
- **Fig 5.** Updated Recent Transactions table with status badges
- **Fig 6.** Form validation error messages (empty required field / invalid contact)
- **Fig 7.** Book-unavailable error (`409`) when availability is zero
- **Fig 8.** API JSON response viewed directly in the browser
- **Fig 9.** `library_management` database — `books` and `transactions` tables in MySQL Workbench
- **Fig 10.** Directory structure of the project in VS Code

---

## 7.0 Conclusion

TCETLib – Library Management System was successfully designed, implemented and tested as a three-layer web application. The project met all of its stated objectives: centralised issue registration, permanent MySQL storage, automatic availability tracking, complete student records, dual validation, consistent JSON responses and a clear transaction history with visual status indicators.

Through this project the team gained practical, hands-on understanding of modern web development. On the frontend, HTML structure, responsive CSS design, JavaScript events, DOM manipulation, form validation and asynchronous Fetch calls were applied to build an interactive interface. On the backend, PHP was used to construct REST endpoints, PDO prepared statements were used to prevent SQL injection, HTTP status codes were used for precise error signalling, and database transactions with row locking were used to keep multi-table updates consistent. On the database side, relational schema design, primary–foreign key relationships and ENUM-based status handling were implemented and verified with real data.

Several challenges were faced during development. First, the backend response fields initially did not match the frontend table columns, causing `undefined` values — this was resolved by mapping every frontend field to a database column and selecting it in the API. Second, cross-origin requests between the local frontend port and the API port required restricted CORS configuration. Third, preventing simultaneous issue of the last copy required `SELECT ... FOR UPDATE` row locking inside a transaction. Each challenge reinforced the importance of a clearly defined API contract between frontend and backend.

**Future scope** of the system includes a book return workflow that restores stock and sets the return date, an administrative dashboard with library statistics, add/edit/delete book management, search functionality, fine calculation and login-based authentication. Because the frontend and backend are cleanly separated through JSON APIs, these features can be added incrementally without restructuring the existing code.

Overall, the project provided complete exposure to the lifecycle of a web application — requirement analysis, database design, interface development, API implementation, security practices, testing and documentation.

---

*Report prepared for the Web Programming examination, A.Y 2025-26, Department of Information Technology.*
