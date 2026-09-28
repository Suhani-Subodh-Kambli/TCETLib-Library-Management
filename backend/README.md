# S2 Backend — Library Management System

Simple **PHP + MySQL (mysqli)** backend. No framework, no PDO, no CORS, no login.

Only **3 API files** — exactly what the frontend uses.

## Structure

```text
backend/
│
├── config/
│   └── database.php        MySQL connection ($conn)
│
├── api/
│   ├── books.php           GET  — saari books (dropdown)
│   ├── transactions.php    GET  — saari transactions (table)
│   └── issue.php           POST — book issue karna (form)
│
├── database/
│   ├── library_management.sql   database + tables
│   └── seed.sql                 sample data
│
└── README.md
```

---

## 1. Setup (XAMPP)

### Step 1 — Start XAMPP

XAMPP Control Panel → **Apache** aur **MySQL** start karo.

### Step 2 — Database banao

Browser me `http://localhost/phpmyadmin` kholo → **Import** →
`backend/database/library_management.sql` → **Go**.

Phir `backend/database/seed.sql` bhi import karo (8 books + 6 transactions).

### Step 3 — Credentials check karo

`config/database.php` me:

```php
$host     = "localhost";
$dbname   = "library_management";
$username = "root";
$password = "mahikasql@26";   // XAMPP default password hota hai ""
```

### Step 4 — Project htdocs me

Project ka folder `C:\xampp\htdocs\TCETLib` me hona chahiye
(yaha already junction bana hua hai → `D:\BasicmernProject\TCETLib-Library-Management`).

```text
C:\xampp\htdocs\TCETLib\
├── frontend\   → index.html, css, js
└── backend\    → api, config, database
```

URL:

```text
Frontend → http://localhost/TCETLib/frontend/index.html
API      → http://localhost/TCETLib/backend/api/
```

### Step 5 — Browser me test karo

```text
http://localhost/TCETLib/backend/api/books.php
http://localhost/TCETLib/backend/api/transactions.php
```

JSON data dikhna chahiye.

> Note: Apache start karne ke liye XAMPP Control Panel me **Start** dabana.
> Agar port 80 pehle se busy aaye to Apache already chal raha hai.

---

## 2. Ek request kaise kaam karti hai (viva ke liye)

```text
Frontend → fetch("http://localhost/TCETLib/backend/api/issue.php")
        ↓
Apache (XAMPP) request leta hai aur PHP file chalata hai
        ↓
database.php → mysqli_connect() se MySQL se connection
        ↓
issue.php → JSON padhta hai (form ke values)
        ↓
3 query chalti hain:
   1. SELECT  → book dhoondho
   2. INSERT  → transaction save karo
   3. UPDATE  → available_quantity - 1
        ↓
echo json_encode(...) → JSON bhejta hai
        ↓
Frontend result.success / result.message padhta hai
```

Har `.php` file ek alag page hai. PHP usko top se bottom tak chalata hai
aur `exit;` par ruk jata hai. Koi framework nahi, koi hidden cheez nahi.

---

## 3. Response format

Success:

```json
{ "success": true, "message": "Books retrieved successfully", "data": [] }
```

Error:

```json
{ "success": false, "message": "Book is not available" }
```

---

## 4. API Reference

### `api/books.php` (GET)

```php
$result = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");

while ($row = mysqli_fetch_assoc($result)) {
    $books[] = $row;
}
```

Frontend dropdown sirf `available_quantity > 0` wali books dikhata hai.

### `api/transactions.php` (GET)

`transactions` table me sirf `book_id` hai, isliye **JOIN** se book ka
`title` laate hain (`book_title`). Table me yahi dikhta hai.

### `api/issue.php` (POST)

Frontend se aata hua JSON:

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

File ke 3 steps:

| Step | Query | Kya karta hai |
| ---- | ----- | ------------- |
| 1 | `SELECT * FROM books WHERE id = ?` | Book dhoondhta hai |
| 2 | `INSERT INTO transactions ...` | Issue record save karta hai |
| 3 | `UPDATE books SET available_quantity = available_quantity - 1` | Stock 1 kam karta hai |

Errors:

* Book nahi mili → `"Book not found"`
* `available_quantity = 0` → `"Book is not available"`

---

## 5. Viva questions

**Why PHP?** Server par chalta hai aur MySQL se baat karta hai.

**Why MySQL?** Books aur transactions ko permanent store karta hai.

**Why `mysqli`?** PHP se MySQL connect karne ka basic tarika
(`mysqli_connect`, `mysqli_query`, `mysqli_fetch_assoc`).

**What is `mysqli_connect()`?** Database se connection banata hai —
host, username, password, database name deta hai.

**What is `mysqli_query()`?** SQL query database par bhejta hai.

**What is `mysqli_fetch_assoc()`?** Query ka result ek-ek row array ki
tarah padhta hai (jaise `["id" => 1, "title" => "JavaScript Basics"]`).

**What is `mysqli_real_escape_string()`?** User ke text me se quotes
hata deta hai taaki SQL toote na (example: `O'Brien`).

**What is JSON?** PHP ka woh format jisme data jaata hai — frontend
`success`, `message`, `data` padhta hai.

**Why 3 files?** Backend me sirf 3 API hain jo frontend call karta hai —
koi extra code nahi.

**Why `available_quantity`?** `quantity` = total copies,
`available_quantity` = abhi free copies. Example: 5 total, 3 free → 2 issued.

**Why JOIN?** `transactions` me sirf `book_id` hai; JOIN se book ka
**title** milta hai table dikhane ke liye.

**Where is the password?** Sirf `config/database.php` me, HTML/JS me kabhi nahi.
