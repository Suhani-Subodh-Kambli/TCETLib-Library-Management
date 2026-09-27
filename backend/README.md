# S2 Backend — Library Management System

Plain **PHP + MySQL (PDO)** backend. No frameworks, no authentication, no extra features.

This backend serves **exactly what the frontend uses** — three API files, nothing else.

## Structure

```text
backend/
│
├── config/
│   └── database.php          PDO connection, CORS, JSON + validation helpers
│
├── api/
│   ├── books.php             GET  — list all books (book dropdown)
│   ├── issue.php             POST — issue a book (issue form)
│   └── transactions.php      GET  — all transactions (transactions table)
│
├── database/
│   ├── library_management.sql   database + tables
│   └── seed.sql                 sample data for testing
│
└── README.md
```

---

## 1. Setup

### Step 1 — Create the database

Import `database/library_management.sql` using MySQL Workbench, or run:

```bash
mysql -u root -p < database/library_management.sql
```

This creates:

* database `library_management`
* table `books`
* table `transactions`

### Step 2 — Load sample data (for testing)

```bash
mysql -u root -p < database/seed.sql
```

`database/seed.sql` resets and inserts **8 books** and **6 transactions**
(3 `Issued`, 3 `Returned`) with realistic student details, so every screen has
data to show. Safe to re-run any time — it starts fresh each run.

### Step 3 — Check credentials

Credentials live only in `config/database.php`:

```php
$host     = "localhost";
$dbname   = "library_management";
$username = "root";
$password = "";
```

Change them to match your local MySQL.

### Step 4 — Start PHP

From the `backend/` folder:

```bash
php -S localhost:8000
```

API base URL: `http://localhost:8000/api/`

### Step 5 — Test manually in the browser

Make sure **nothing else is using port 8000**, then open:

```text
http://localhost:8000/api/books.php
http://localhost:8000/api/transactions.php
```

You should see JSON with the seed data. To test the POST issue endpoint use
Postman or `curl` (see the test checklist at the bottom).

### How a request flows (for the viva)

```text
Browser / fetch("http://localhost:8000/api/issue.php")
        ↓
PHP built-in server receives the request
        ↓
api/issue.php runs: read JSON → validate every field
        ↓
config/database.php opens a PDO connection to MySQL
        ↓
Prepared statements run inside a DB transaction
        ↓
PHP echoes JSON: {"success": true, ...}
        ↓
Frontend reads result.success / result.message / result.data
```

Every `.php` file is an independent entry point — PHP executes it from top to
bottom on each request, then exits. There is no framework and no hidden magic:
the file you open in the browser is the file that runs.

---

## 2. CORS

If the frontend runs on a different port (e.g. Live Server on `5500`), its origin must be in the
`$allowedOrigins` array in `config/database.php`:

```php
$allowedOrigins = [
    "http://localhost:5500",
    "http://127.0.0.1:5500",
    "http://localhost:3000",
    "http://localhost:8000",
    "http://127.0.0.1:8000"
];
```

Only listed origins receive `Access-Control-Allow-Origin`. Do **not** use `*`.

If frontend and backend are served from the same origin, CORS is not needed at all.

---

## 3. Response format

Success:

```json
{
    "success": true,
    "message": "Operation successful",
    "data": {}
}
```

Error:

```json
{
    "success": false,
    "message": "Something went wrong"
}
```

Status codes used: `200` `201` `400` `404` `405` `409` `500`.

---

## 4. API Reference

### Books — `api/books.php` (GET)

Returns all books, newest first. Used by the frontend to fill the book dropdown
(shows only books where `available_quantity > 0`).

```json
{
    "success": true,
    "message": "Books retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "JavaScript Basics",
            "author": "John Smith",
            "category": "Programming",
            "quantity": 5,
            "available_quantity": 5,
            "created_at": "2026-09-27 18:33:41"
        }
    ]
}
```

Any method other than GET → `405 Method not allowed`.

### Issue — `api/issue.php` (POST)

```json
{
    "student_name": "Mahika",
    "academic_year": "TE",
    "department": "Information Technology",
    "division": "A",
    "contact": "9876543210",
    "book_id": 1,
    "issue_date": "2026-09-27",
    "due_date": "2026-10-04",
    "remarks": "Library card verified"
}
```

All 9 fields above come straight from the frontend issue form.

Flow: validate → book exists (`404`) → `available_quantity > 0` else `409 "Book is not available"`
→ insert transaction (with all student details) → `available_quantity - 1`.

Both writes run inside a PDO transaction (`beginTransaction` / `commit` / `rollBack`), with the
book row locked using `SELECT ... FOR UPDATE`, so the last copy can never be issued twice.

Success → `201`:

```json
{
    "success": true,
    "message": "Book issued successfully",
    "data": { "transaction_id": 7 }
}
```

### Transactions — `api/transactions.php` (GET)

All transactions joined with `books`, newest first. Powers the transactions table.

Each row returns every field the frontend displays:

```json
{
    "id": 3,
    "student_name": "Mahika",
    "academic_year": "TE",
    "department": "Information Technology",
    "division": "A",
    "contact": "9876543210",
    "book_id": 1,
    "book_title": "JavaScript Basics",
    "issue_date": "2026-09-27",
    "due_date": "2026-10-04",
    "remarks": "Library card verified",
    "return_date": null,
    "status": "Issued"
}
```

Any method other than GET → `405 Method not allowed`.

---

## 5. Validation

Every field is validated on the backend, even though the frontend also validates:

* Student name required (max 255)
* Academic year required (max 20)
* Department required (max 100)
* Division required (max 10)
* Contact required, exactly 10 digits
* Book ID required, book must exist
* `available_quantity` must be greater than 0
* Issue date required and must match `YYYY-MM-DD`
* Due date required, valid `YYYY-MM-DD`, and not before the issue date
* Remarks optional (max 255)

Frontend validation is only for user experience — PHP validation protects the database.

---

## 6. Security

* **Prepared statements** everywhere user input is used (prevents SQL injection).
* **Backend validation** on every request; frontend JS can be bypassed.
* **DB credentials** exist only in `config/database.php`, never in JavaScript.
* **No raw errors**: PDO exceptions are caught and replaced with clean JSON messages.
* **Restricted CORS**: only origins listed in `$allowedOrigins`.

---

## 7. Manual test checklist

| # | Test | Expected |
| - | ---- | -------- |
| 1 | `GET /api/books.php` | `200`, all 8 seed books returned |
| 2 | `GET /api/transactions.php` | `200`, 6 rows, all student fields + `book_title` present |
| 3 | `POST /api/issue.php` with all 9 fields | `201`, transaction created, `available_quantity - 1` |
| 4 | `POST /api/issue.php` when `available_quantity = 0` | `409` `"Book is not available"`, no transaction |
| 5 | `POST /api/issue.php` with missing department | `400` `"Department is required"` |
| 6 | `POST /api/issue.php` with `due_date < issue_date` | `400` `"Due date cannot be before issue date"` |
| 7 | `POST /api/issue.php` with contact not 10 digits | `400` `"Contact number must contain exactly 10 digits"` |
| 8 | `POST /api/books.php` (method not allowed) | `405` |
| 9 | `GET /api/dashboard.php` (file removed) | `404` |

### Example curl commands

```bash
curl http://localhost:8000/api/books.php

curl http://localhost:8000/api/transactions.php

curl -X POST http://localhost:8000/api/issue.php -H "Content-Type: application/json" -d "{\"student_name\":\"Mahika\",\"academic_year\":\"TE\",\"department\":\"Information Technology\",\"division\":\"A\",\"contact\":\"9876543210\",\"book_id\":1,\"issue_date\":\"2026-09-27\",\"due_date\":\"2026-10-04\",\"remarks\":\"Library card verified\"}"
```

---

## 8. Viva explanation

**Why PHP?** It handles server-side logic and talks to MySQL.

**Why MySQL?** It stores book and transaction records permanently.

**Why PDO?** It gives a simple database interface and supports prepared statements.

**Why prepared statements?** They prevent SQL injection by separating SQL logic from user data.

**Why transactions?** Issuing a book changes two tables (`transactions` and `books`);
`beginTransaction()` keeps them consistent — if one query fails, `rollBack()` undoes the other.

**Why `available_quantity`?** `quantity` = total copies owned, `available_quantity` = copies free
right now. Example: total 5, available 3 → issued 2.

**Why a foreign key?** `transactions.book_id` references `books(id)`, so a transaction can never
point to a non-existent book (`ON DELETE RESTRICT` protects the data too).

**Why validate on the backend if the frontend already validates?** Frontend validation improves
user experience, but JavaScript can be bypassed (browser dev tools, direct curl/Postman calls).
Backend validation protects the database and data integrity.

**Why `FOR UPDATE`?** It locks the row inside the transaction so two simultaneous requests
cannot both issue the last copy.

**Why only three API files?** The backend implements exactly what the frontend consumes —
no unused endpoints, no dead code.
