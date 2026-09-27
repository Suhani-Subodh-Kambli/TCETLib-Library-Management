# S2 Backend — Library Management System

Plain **PHP + MySQL (PDO)** backend. No frameworks, no authentication, no extra features.

## Structure

```text
backend/
│
├── config/
│   └── database.php          PDO connection, CORS, JSON helpers
│
├── api/
│   ├── books.php             GET / POST / PUT / DELETE
│   ├── issue.php             POST
│   ├── return.php            POST
│   ├── transactions.php      GET (all + single by id)
│   └── dashboard.php         GET
│
├── database/
│   └── library_management.sql
│
└── README.md
```

---

## 1. Setup

### Step 1 — Create the database

Import `database/library_management.sql` using phpMyAdmin, or run:

```bash
mysql -u root -p < database/library_management.sql
```

This creates:

* database `library_management`
* table `books`
* table `transactions`

### Step 2 — Check credentials

Credentials live only in `config/database.php`:

```php
$host     = "localhost";
$dbname   = "library_management";
$username = "root";
$password = "";
```

Change them if your MySQL setup is different (e.g. XAMPP default is `root` with an empty password).

### Step 3 — Start PHP

From the `backend/` folder:

```bash
php -S localhost:8000
```

API base URL: `http://localhost:8000/api/`

---

## 2. CORS

If the frontend runs on a different port (e.g. Live Server on `5500`), add its origin to the
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

### Books — `api/books.php`

| Method | Purpose | Body / Query |
| ------ | ------- | ------------ |
| GET    | List all books | — |
| POST   | Add book | `title`, `author`, `category`, `quantity` |
| PUT    | Update book | `id`, `title`, `author`, `category`, `quantity` |
| DELETE | Delete book | `?id=1` |

**POST** sets `available_quantity = quantity`.

**PUT** preserves already issued copies:

```text
Old quantity = 5, old available = 2  →  issued = 3
New quantity = 10                    →  new available = 10 - 3 = 7
```

If the new quantity is smaller than the currently issued count, the update is rejected.

**DELETE** is rejected (`409`) while any copy of the book is still issued.

### Issue — `api/issue.php` (POST)

```json
{
    "student_name": "Mahika",
    "book_id": 1,
    "issue_date": "2026-09-27"
}
```

Flow: validate → book exists (`404`) → `available_quantity > 0` else `409 "Book is not available"`
→ insert transaction → `available_quantity - 1`.

Both writes run inside a PDO transaction (`beginTransaction` / `commit` / `rollBack`), with the
book row locked using `SELECT ... FOR UPDATE`.

### Return — `api/return.php` (POST)

```json
{
    "transaction_id": 1
}
```

Flow: validate → transaction exists (`404`) → status must be `Issued` else
`409 "This book has already been returned"` → set `return_date = CURDATE()`, `status = 'Returned'`
→ `available_quantity + 1`.

The `UPDATE ... WHERE status = 'Issued'` plus `rowCount()` check guarantees the availability
counter can never be increased twice for the same transaction. Runs inside a PDO transaction.

### Transactions — `api/transactions.php` (GET)

* `GET /api/transactions.php` — all transactions (JOIN with `books`)
* `GET /api/transactions.php?id=1` — single transaction (`404` if not found)

### Dashboard — `api/dashboard.php` (GET)

```json
{
    "success": true,
    "message": "Dashboard statistics retrieved successfully",
    "data": {
        "total_books": 10,
        "available_books": 7,
        "issued_books": 3,
        "total_transactions": 15
    }
}
```

* **total_books** — `COUNT(*)` of book records
* **available_books** — `SUM(available_quantity)`
* **issued_books** — `COUNT(*)` of transactions with `status = 'Issued'`
* **total_transactions** — `COUNT(*)` of transaction records

---

## 5. Validation

### Book
* Title / Author / Category — required, cannot be empty
* Quantity — required, integer, greater than 0

### Issue
* Student name required
* Book ID required, book must exist
* `available_quantity` must be greater than 0
* Issue date required and must match `YYYY-MM-DD`

### Return
* Transaction ID required
* Transaction must exist
* Status must be `Issued`

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
| 1 | `POST /api/books.php` with valid data | `201`, book inserted, `available_quantity = quantity` |
| 2 | `GET /api/books.php` | `200`, all books returned |
| 3 | `PUT /api/books.php` with new quantity | `200`, issued copies preserved in `available_quantity` |
| 4 | `POST /api/issue.php` for an available book | `201`, `available_quantity - 1`, transaction `Issued` |
| 5 | `POST /api/issue.php` when `available_quantity = 0` | `409` `"Book is not available"`, no transaction created |
| 6 | `POST /api/return.php` for an issued transaction | `200`, status `Returned`, `return_date` set, `available_quantity + 1` |
| 7 | `POST /api/return.php` for the same transaction again | `409` `"This book has already been returned"`, quantity unchanged |
| 8 | `DELETE /api/books.php?id=1` while copies are issued | `409` `"Cannot delete a book that is currently issued"` |
| 9 | `GET /api/transactions.php` | `200`, JOIN results with `book_title` |
| 10 | `GET /api/dashboard.php` | `200`, four statistic values |

### Example curl commands

```bash
curl -X POST http://localhost:8000/api/books.php -H "Content-Type: application/json" -d "{\"title\":\"JavaScript Basics\",\"author\":\"John Smith\",\"category\":\"Programming\",\"quantity\":5}"

curl http://localhost:8000/api/books.php

curl -X PUT http://localhost:8000/api/books.php -H "Content-Type: application/json" -d "{\"id\":1,\"title\":\"JavaScript Basics\",\"author\":\"John Smith\",\"category\":\"Programming\",\"quantity\":10}"

curl -X DELETE "http://localhost:8000/api/books.php?id=1"

curl -X POST http://localhost:8000/api/issue.php -H "Content-Type: application/json" -d "{\"student_name\":\"Mahika\",\"book_id\":1,\"issue_date\":\"2026-09-27\"}"

curl -X POST http://localhost:8000/api/return.php -H "Content-Type: application/json" -d "{\"transaction_id\":1}"

curl http://localhost:8000/api/transactions.php
curl "http://localhost:8000/api/transactions.php?id=1"
curl http://localhost:8000/api/dashboard.php
```

---

## 8. Viva explanation

**Why PHP?** It handles server-side logic and talks to MySQL.

**Why MySQL?** It stores book and transaction records permanently.

**Why PDO?** It gives a simple database interface and supports prepared statements.

**Why prepared statements?** They prevent SQL injection by separating SQL logic from user data.

**Why transactions?** Issuing/returning a book changes two tables (`transactions` and `books`);
`beginTransaction()` keeps them consistent — if one query fails, `rollBack()` undoes the other.

**Why `available_quantity`?** `quantity` = total copies owned, `available_quantity` = copies free
right now. Example: total 5, available 3 → issued 2.

**Why a foreign key?** `transactions.book_id` references `books(id)`, so a transaction can never
point to a non-existent book (`ON DELETE RESTRICT` also blocks deleting books that are referenced).

**Why validate on the backend if the frontend already validates?** Frontend validation improves
user experience, but JavaScript can be bypassed (browser dev tools, direct curl/Postman calls).
Backend validation protects the database and data integrity.

**Why `FOR UPDATE` in issue/return?** It locks the row inside the transaction so two simultaneous
requests cannot both issue the last copy or return the same book twice.
