# Library Management System

## Project Scope

A web application for managing:

* Books
* Book issue
* Book return
* Issue/return transactions

### Technology

| Part | Technology            | Responsible |
| ---- | --------------------- | ----------- |
| S1   | HTML, CSS, JavaScript | S1          |
| S2   | PHP, MySQL            | S2          |

---

# IMPORTANT: WORK DIVISION

To avoid conflicts, **S1 and S2 must work independently**.

## S1 will ONLY handle:

* HTML
* CSS
* JavaScript
* UI
* DOM manipulation
* Events
* Client-side validation
* API calls using Fetch
* Displaying backend responses

## S2 will ONLY handle:

* PHP
* MySQL
* Database design
* SQL queries
* Backend validation
* CRUD operations
* Issue/return logic
* API endpoints
* JSON responses

### Neither person should modify the other's main files.

The only connection between S1 and S2 will be through the **defined API endpoints and JSON response format**.

---

# S1 — FRONTEND + JAVASCRIPT

## S1 Responsibilities

S1 is responsible for the complete user interface and all JavaScript functionality.

---

## S1 Features

### 1. Dashboard

Create a dashboard showing:

* Total Books
* Available Books
* Issued Books
* Total Transactions

These values will be received from the S2 API.

S1 will **only display these values**.

S1 will NOT calculate them from the database.

---

# 2. Book Management UI

Create a Books section.

Display:

* Book ID
* Book Title
* Author
* Category
* Total Quantity
* Available Quantity
* Status

Example:

```text
----------------------------------------------------------
ID   Title          Author      Quantity   Available
----------------------------------------------------------
1    JavaScript     John        5          3
2    PHP Basics     Alex        3          0
3    DBMS           Smith       4          4
----------------------------------------------------------
```

### S1 will implement:

* Book table
* Search books
* Display book data
* Add Book form
* Edit Book form
* Delete Book button
* Loading states
* Success messages
* Error messages

### S1 will NOT:

* Write SQL
* Connect to MySQL
* Implement database queries

---

# 3. Add Book

Create a form:

```text
Book Title
Author
Category
Quantity

[ Add Book ]
```

### JavaScript validation:

* Title cannot be empty
* Author cannot be empty
* Category cannot be empty
* Quantity must be a positive number

After validation:

```text
JavaScript
   ↓
Fetch POST request
   ↓
PHP API
```

After receiving the response, S1 displays the message.

---

# 4. Edit Book

Create an edit option for each book.

The form should allow changing:

* Title
* Author
* Category
* Quantity

JavaScript validates the data and sends it to the PHP API.

---

# 5. Delete Book

Each book can have a Delete button.

Before deletion, show confirmation:

```text
Are you sure you want to delete this book?

[ Cancel ] [ Delete ]
```

After confirmation:

```text
JavaScript
    ↓
DELETE request
    ↓
PHP API
    ↓
MySQL
```

S1 only handles the UI and request.

---

# 6. Issue Book

Create an Issue Book section.

Fields:

```text
Student Name
Select Book
Issue Date

[ Issue Book ]
```

### JavaScript validation:

* Student name required
* Book required
* Issue date required
* Student name cannot contain only spaces

If the selected book is unavailable, display:

```text
Book is currently unavailable.
```

S1 sends the request to PHP.

S1 does NOT decide whether the book is actually available.

The final availability check is done by S2.

---

# 7. Return Book

Create a Return Book section.

User can enter/select:

```text
Transaction ID

[ Find Transaction ]
```

Display:

```text
Student Name
Book
Issue Date
Status
```

Then:

```text
[ Return Book ]
```

JavaScript validates the input and sends the return request to PHP.

---

# 8. Transaction History

Display all transactions.

Columns:

```text
Transaction ID
Student Name
Book
Issue Date
Return Date
Status
```

Example:

```text
-------------------------------------------------------------
ID   Student   Book            Issue Date   Return Date
-------------------------------------------------------------
1    Mahika    JavaScript      27/09/26     -
2    Suhani    PHP             25/09/26     27/09/26
-------------------------------------------------------------
```

Status:

```text
Issued
Returned
```

---

# 9. Search

JavaScript should provide book search.

Example:

```text
Search: [ JavaScript ]

JavaScript Basics
JavaScript Advanced
```

This should demonstrate JavaScript input events and DOM manipulation.

---

# 10. JavaScript Events

S1 MUST demonstrate JavaScript events.

Required events:

### Form Submit

```javascript
form.addEventListener("submit", ...)
```

### Button Click

```javascript
button.addEventListener("click", ...)
```

### Search Input

```javascript
searchInput.addEventListener("input", ...)
```

### Select Change

```javascript
bookSelect.addEventListener("change", ...)
```

---

# 11. DOM Manipulation

S1 MUST use JavaScript DOM manipulation for:

* Adding table rows
* Updating table rows
* Removing deleted books
* Updating book status
* Showing success messages
* Showing error messages
* Updating dashboard values
* Showing/hiding forms
* Updating transaction status

---

# 12. API Communication

S1 will use Fetch API.

Example:

```javascript
fetch("/api/books.php")
```

S1 does not implement the PHP API.

S1 only consumes it.

---

# S1 FILE OWNERSHIP

S1 can create/modify:

```text
frontend/
│
├── index.html
│
├── css/
│   └── style.css
│
├── js/
│   ├── app.js
│   ├── books.js
│   ├── transactions.js
│   └── validation.js
│
└── assets/
```

S1 should NOT modify:

```text
backend/
api/
config/
database/
*.php
```

---

# S2 — PHP + MYSQL BACKEND

## S2 Responsibilities

S2 is responsible for:

* MySQL database
* Database tables
* PHP backend
* API endpoints
* SQL queries
* CRUD operations
* Backend validation
* Issue logic
* Return logic
* JSON responses
* Basic security

---

# S2 Database

Database name:

```text
library_management
```

---

# 1. Books Table

Table:

```text
books
```

Columns:

| Column             | Type      | Purpose             |
| ------------------ | --------- | ------------------- |
| id                 | INT       | Primary key         |
| title              | VARCHAR   | Book title          |
| author             | VARCHAR   | Author name         |
| category           | VARCHAR   | Book category       |
| quantity           | INT       | Total quantity      |
| available_quantity | INT       | Currently available |
| created_at         | TIMESTAMP | Creation time       |

---

# 2. Transactions Table

Table:

```text
transactions
```

Columns:

| Column       | Type      | Purpose           |
| ------------ | --------- | ----------------- |
| id           | INT       | Primary key       |
| student_name | VARCHAR   | Student name      |
| book_id      | INT       | Related book      |
| issue_date   | DATE      | Issue date        |
| return_date  | DATE      | Return date       |
| status       | VARCHAR   | Issued / Returned |
| created_at   | TIMESTAMP | Creation time     |

Relationship:

```text
books.id
    ↓
transactions.book_id
```

---

# S2 BOOK APIs

## GET Books

```text
GET /api/books.php
```

Purpose:

Return all books.

Response:

```json
{
    "success": true,
    "data": []
}
```

---

# POST Add Book

```text
POST /api/books.php
```

Data:

```json
{
    "title": "JavaScript Basics",
    "author": "John Smith",
    "category": "Programming",
    "quantity": 5
}
```

PHP will:

1. Validate data
2. Insert book
3. Set available quantity
4. Return JSON response

---

# PUT Update Book

```text
PUT /api/books.php
```

PHP will:

1. Check book ID
2. Validate data
3. Update database
4. Return response

---

# DELETE Book

```text
DELETE /api/books.php
```

PHP will:

1. Check book ID
2. Check whether deletion is allowed
3. Delete book
4. Return response

---

# S2 TRANSACTION APIs

## Issue Book

```text
POST /api/issue.php
```

Data:

```json
{
    "student_name": "Mahika",
    "book_id": 1,
    "issue_date": "2026-09-27"
}
```

PHP must:

1. Validate input
2. Check whether book exists
3. Check available quantity
4. Create transaction
5. Decrease available quantity
6. Return JSON response

Example:

```json
{
    "success": true,
    "message": "Book issued successfully"
}
```

---

# Return Book

```text
POST /api/return.php
```

Data:

```json
{
    "transaction_id": 1
}
```

PHP must:

1. Find transaction
2. Check transaction status
3. Set return date
4. Change status to Returned
5. Increase available quantity
6. Return JSON response

---

# GET Transactions

```text
GET /api/transactions.php
```

Returns:

```text
Transaction ID
Student Name
Book
Issue Date
Return Date
Status
```

---

# S2 BACKEND VALIDATION

Backend validation is mandatory.

Even if S1 validates the form, S2 MUST validate again.

For example:

```text
S1:
"Book ID is required"

        ↓

S2:
"Does this book actually exist?"

        ↓

S2:
"Is available_quantity > 0?"

        ↓

S2:
Create transaction
```

---

# S2 SECURITY

Keep security simple and understandable.

## 1. Prepared Statements

Use PDO prepared statements.

Example:

```php
$stmt = $pdo->prepare(
    "SELECT * FROM books WHERE id = ?"
);

$stmt->execute([$bookId]);
```

Do NOT directly concatenate user input into SQL queries.

---

## 2. Backend Validation

Validate:

* Required fields
* Correct data types
* Positive quantity
* Valid book ID
* Valid transaction ID
* Book availability
* Transaction status

---

## 3. Database Credentials

Database credentials must remain inside PHP backend configuration.

Never put database credentials inside JavaScript.

---

# S2 FILE OWNERSHIP

S2 can create/modify:

```text
backend/
│
├── config/
│   └── database.php
│
└── api/
    ├── books.php
    ├── issue.php
    ├── return.php
    └── transactions.php
```

S2 should NOT modify:

```text
frontend/index.html
frontend/css/
frontend/js/
frontend/assets/
```

---

# SHARED API CONTRACT

This is the ONLY major point where S1 and S2 need to coordinate.

S1 expects PHP to return JSON.

S2 must follow the agreed response format.

---

## Success Response

```json
{
    "success": true,
    "message": "Operation successful",
    "data": {}
}
```

---

## Error Response

```json
{
    "success": false,
    "message": "Book is not available"
}
```

S1 will display the `message`.

S1 should NOT interpret SQL errors or database logic.

---

# COMPLETE FEATURE LIST

Only the following features will be implemented.

## Books

* [ ] View all books
* [ ] Add book
* [ ] Edit book
* [ ] Delete book
* [ ] Search books
* [ ] Show available quantity

## Issue

* [ ] Issue book
* [ ] Student name
* [ ] Book selection
* [ ] Issue date
* [ ] Availability check
* [ ] Reduce available quantity

## Return

* [ ] Find transaction
* [ ] Return book
* [ ] Return date
* [ ] Change status
* [ ] Increase available quantity

## Transactions

* [ ] View transaction history
* [ ] Show issue date
* [ ] Show return date
* [ ] Show transaction status

## S1 JavaScript Requirements

* [ ] Form validation
* [ ] DOM manipulation
* [ ] Events
* [ ] Fetch API
* [ ] Dynamic UI updates
* [ ] Error messages
* [ ] Success messages

## S2 PHP + MySQL Requirements

* [ ] Database creation
* [ ] Books table
* [ ] Transactions table
* [ ] CRUD operations
* [ ] Issue logic
* [ ] Return logic
* [ ] Backend validation
* [ ] Prepared statements
* [ ] JSON API responses

---

# FEATURES WE WILL NOT ADD

To keep the project within the given requirements, DO NOT add:

* Login/signup
* User authentication
* Admin roles
* Email notifications
* SMS
* Payment
* Fine calculation
* Book reservation
* AI
* Chatbot
* Advanced analytics
* Charts
* Notifications
* Mobile app
* External APIs
* Cloud services

The project will contain **only the features listed above**.

---

# WORK CONFLICT RULE

If a feature requires both frontend and backend:

### S1 handles:

```text
UI
Form
Validation
Events
Fetch request
Displaying response
DOM update
```

### S2 handles:

```text
PHP
Validation
Database
SQL
Business logic
JSON response
```

Example: **Issue Book**

```text
S1                              S2
│                               │
│ User fills form               │
│                               │
│ JS validates                  │
│                               │
│ POST /api/issue.php ─────────>│
│                               │ PHP validates
│                               │ Check book
│                               │ Check availability
│                               │ Create transaction
│                               │ Update quantity
│                               │
│ <──────── JSON response ──────│
│                               │
│ Update DOM                    │
│ Show message                  │
```

Neither side should implement the other's responsibility.

---

# FINAL DIVISION

## S1 — Suhani / Frontend

**HTML + CSS + JavaScript**

Responsible for:

```text
Dashboard UI
Books UI
Add/Edit/Delete UI
Search
Issue UI
Return UI
Transaction UI

JavaScript:
Validation
DOM
Events
Fetch API
UI updates
```

---

## S2 — Mahika / Backend 


**PHP + MySQL**

Responsible for:

```text
Database
Books table
Transactions table

PHP:
Books API
Issue API
Return API
Transactions API

SQL:
INSERT
SELECT
UPDATE
DELETE

Backend validation
Prepared statements
Issue/return business logic
JSON responses
```

---

# FINAL RULE

**S1 does not write PHP or SQL.**

**S2 does not write frontend HTML/CSS/JavaScript.**

Both members communicate only through the agreed API endpoints and JSON response format.

This division ensures that both S1 and S2 can develop their modules independently without overwriting or conflicting with each other's work.
