const API = "http://localhost:8000/api";

const studentName = document.getElementById("studentName");
const academicYear = document.getElementById("academicYear");
const department = document.getElementById("department");
const division = document.getElementById("division");
const contact = document.getElementById("contact");

const book = document.getElementById("book");
const issueDate = document.getElementById("issueDate");
const dueDate = document.getElementById("dueDate");
const remarks = document.getElementById("remarks");

const submitBtn = document.getElementById("submitBtn");
const resetBtn = document.getElementById("resetBtn");

const message = document.getElementById("message");
const transactionBody = document.getElementById("transactionBody");
const recordCount = document.getElementById("recordCount");

// Set today's date
function setDates() {
const today = new Date();

```
issueDate.value = today.toISOString().split("T")[0];

const due = new Date(today);
due.setDate(due.getDate() + 7);

dueDate.value = due.toISOString().split("T")[0];
```

}

// Show message
function showMessage(text, type) {
message.textContent = text;
message.className = "message " + type;
}

// Contact number: only digits
contact.addEventListener("input", function () {
contact.value = contact.value.replace(/\D/g, "").slice(0, 10);
});

// Change due date when issue date changes
issueDate.addEventListener("change", function () {

```
if (!issueDate.value) {
    dueDate.value = "";
    return;
}

const date = new Date(issueDate.value);
date.setDate(date.getDate() + 7);

dueDate.value = date.toISOString().split("T")[0];
```

});

// Load books from database
async function loadBooks() {

```
try {
    const response = await fetch(API + "/books.php");
    const result = await response.json();

    if (!result.success) {
        throw new Error(result.message);
    }

    book.innerHTML = '<option value="">Select a book</option>';

    result.data.forEach(function (item) {

        if (Number(item.available_quantity) > 0) {

            const option = document.createElement("option");

            // Actual MySQL book ID
            option.value = item.id;

            option.textContent =
                item.title + " — " +
                item.available_quantity + " available";

            book.appendChild(option);
        }
    });

} catch (error) {

    console.error(error);

    book.innerHTML =
        '<option value="">Unable to load books</option>';

    showMessage("Could not load books.", "error");
}
```

}

// Load transactions from database
async function loadTransactions() {

```
try {
    const response = await fetch(API + "/transactions.php");
    const result = await response.json();

    if (!result.success) {
        throw new Error(result.message);
    }

    transactionBody.innerHTML = "";

    if (result.data.length === 0) {

        transactionBody.innerHTML = `
            <tr>
                <td colspan="7" class="empty-state">
                    No transactions yet
                </td>
            </tr>
        `;

        recordCount.textContent = "0";
        return;
    }

    result.data.forEach(function (transaction) {

        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${transaction.student_name}</td>
            <td>${transaction.department}</td>
            <td>${transaction.academic_year}</td>
            <td>${transaction.book_title}</td>
            <td>${transaction.issue_date}</td>
            <td>${transaction.due_date}</td>
            <td>
                <span class="status ${transaction.status.toLowerCase()}">
                    ${transaction.status}
                </span>
            </td>
        `;

        transactionBody.appendChild(row);
    });

    recordCount.textContent = result.data.length;

} catch (error) {

    console.error(error);

    transactionBody.innerHTML = `
        <tr>
            <td colspan="7" class="empty-state">
                Unable to load transactions
            </td>
        </tr>
    `;

    recordCount.textContent = "0";
}
```

}

// Validate form
function validateForm() {

```
if (!studentName.value.trim()) {
    showMessage("Please enter the student's name.", "error");
    return false;
}

if (!academicYear.value) {
    showMessage("Please select the academic year.", "error");
    return false;
}

if (!department.value) {
    showMessage("Please select the department.", "error");
    return false;
}

if (!division.value) {
    showMessage("Please select the division.", "error");
    return false;
}

if (!/^\d{10}$/.test(contact.value)) {
    showMessage("Contact number must contain 10 digits.", "error");
    return false;
}

if (!book.value) {
    showMessage("Please select a book.", "error");
    return false;
}

if (!issueDate.value || !dueDate.value) {
    showMessage("Please select the issue and due dates.", "error");
    return false;
}

if (dueDate.value < issueDate.value) {
    showMessage("Due date cannot be before issue date.", "error");
    return false;
}

return true;
```

}

// Issue book
async function issueBook() {

```
if (!validateForm()) {
    return;
}

submitBtn.disabled = true;
submitBtn.textContent = "Issuing...";

const data = {
    student_name: studentName.value.trim(),
    academic_year: academicYear.value,
    department: department.value,
    division: division.value,
    contact: contact.value,
    book_id: Number(book.value),
    issue_date: issueDate.value,
    due_date: dueDate.value,
    remarks: remarks.value.trim()
};

try {

    const response = await fetch(API + "/issue.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    });

    const result = await response.json();

    if (!result.success) {
        throw new Error(result.message);
    }

    showMessage(
        result.message || "Book issued successfully.",
        "success"
    );

    // Get updated data from database
    await loadBooks();
    await loadTransactions();

    clearForm(false);

} catch (error) {

    console.error(error);

    showMessage(
        error.message || "Unable to issue book.",
        "error"
    );

} finally {

    submitBtn.disabled = false;
    submitBtn.textContent = "Issue Book →";
}
```

}

// Clear form
function clearForm(showMessageText = true) {

```
studentName.value = "";
academicYear.value = "";
department.value = "";
division.value = "";
contact.value = "";

book.value = "";
remarks.value = "";

setDates();

if (showMessageText) {
    showMessage("Form cleared.", "success");
}
```

}

// Button events
submitBtn.addEventListener("click", issueBook);

resetBtn.addEventListener("click", function () {
clearForm(true);
});

// Page load
setDates();
loadBooks();
loadTransactions();
