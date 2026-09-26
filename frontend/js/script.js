/* =========================================
   TCETLib
   Library Management System

   S1 - JavaScript

   Implements:
   1. Transaction validation
   2. DOM manipulation
   3. Event handling
   ========================================= */


/* -----------------------------------------
   Get HTML Elements
----------------------------------------- */

const studentName =
    document.getElementById("studentName");

const academicYear =
    document.getElementById("academicYear");

const department =
    document.getElementById("department");

const division =
    document.getElementById("division");

const contact =
    document.getElementById("contact");

const book =
    document.getElementById("book");

const bookId =
    document.getElementById("bookId");

const issueDate =
    document.getElementById("issueDate");

const dueDate =
    document.getElementById("dueDate");

const remarks =
    document.getElementById("remarks");

const submitBtn =
    document.getElementById("submitBtn");

const resetBtn =
    document.getElementById("resetBtn");

const message =
    document.getElementById("message");

const transactionBody =
    document.getElementById("transactionBody");

const recordCount =
    document.getElementById("recordCount");

const emptyRow =
    document.getElementById("emptyRow");


/* -----------------------------------------
   Set Today's Date
----------------------------------------- */

function setTodayDate() {

    const today = new Date();

    const year =
        today.getFullYear();

    const month =
        String(today.getMonth() + 1)
        .padStart(2, "0");

    const day =
        String(today.getDate())
        .padStart(2, "0");

    const todayDate =
        `${year}-${month}-${day}`;

    issueDate.value = todayDate;

    setDefaultDueDate(todayDate);
}


/* -----------------------------------------
   Set Due Date
   Default = 7 days after issue date
----------------------------------------- */

function setDefaultDueDate(dateString) {

    const date =
        new Date(dateString);

    date.setDate(
        date.getDate() + 7
    );

    const year =
        date.getFullYear();

    const month =
        String(date.getMonth() + 1)
        .padStart(2, "0");

    const day =
        String(date.getDate())
        .padStart(2, "0");

    dueDate.value =
        `${year}-${month}-${day}`;
}


/* -----------------------------------------
   Book Selection Event
----------------------------------------- */

book.addEventListener(
    "change",
    function () {

        /*
            When the user selects a book,
            automatically display its Book ID.
        */

        bookId.value = book.value;

    }
);


/* -----------------------------------------
   Issue Date Change Event
----------------------------------------- */

issueDate.addEventListener(
    "change",
    function () {

        if (issueDate.value) {

            setDefaultDueDate(
                issueDate.value
            );

        }

    }
);


/* -----------------------------------------
   Contact Number Input Event
----------------------------------------- */

contact.addEventListener(
    "input",
    function () {

        /*
            Remove all non-numeric
            characters.
        */

        contact.value =
            contact.value.replace(
                /\D/g,
                ""
            );

    }
);


/* -----------------------------------------
   Display Message
----------------------------------------- */

function showMessage(text, type) {

    message.textContent = text;

    message.className =
        "message " + type;

}


/* -----------------------------------------
   Validate Form
----------------------------------------- */

function validateForm() {


    /* Name */

    if (studentName.value.trim() === "") {

        showMessage(
            "Please enter your full name.",
            "error"
        );

        studentName.focus();

        return false;
    }


    /* Academic Year */

    if (academicYear.value === "") {

        showMessage(
            "Please select your academic year.",
            "error"
        );

        academicYear.focus();

        return false;
    }


    /* Department */

    if (department.value === "") {

        showMessage(
            "Please select your department.",
            "error"
        );

        department.focus();

        return false;
    }


    /* Division */

    if (division.value === "") {

        showMessage(
            "Please select your division.",
            "error"
        );

        division.focus();

        return false;
    }


    /* Contact Number */

    if (
        !/^[0-9]{10}$/.test(
            contact.value
        )
    ) {

        showMessage(
            "Please enter a valid 10-digit contact number.",
            "error"
        );

        contact.focus();

        return false;
    }


    /* Book */

    if (book.value === "") {

        showMessage(
            "Please select a book.",
            "error"
        );

        book.focus();

        return false;
    }


    /* Issue Date */

    if (issueDate.value === "") {

        showMessage(
            "Issue date is required.",
            "error"
        );

        issueDate.focus();

        return false;
    }


    /* Due Date */

    if (dueDate.value === "") {

        showMessage(
            "Due date is required.",
            "error"
        );

        dueDate.focus();

        return false;
    }


    /* Date Validation */

    const issue =
        new Date(issueDate.value);

    const due =
        new Date(dueDate.value);


    if (due < issue) {

        showMessage(
            "Due date cannot be before the issue date.",
            "error"
        );

        dueDate.focus();

        return false;
    }


    return true;
}


/* -----------------------------------------
   Add Transaction to Table
   DOM MANIPULATION
----------------------------------------- */

function addTransactionToTable() {

    /*
        Remove "No transactions yet"
        message when the first
        transaction is added.
    */

    if (emptyRow) {
        emptyRow.remove();
    }


    /* Create Table Row */

    const row =
        document.createElement("tr");


    /* Student Name */

    const nameCell =
        document.createElement("td");

    nameCell.textContent =
        studentName.value;


    /* Department */

    const departmentCell =
        document.createElement("td");

    departmentCell.textContent =
        department.value;


    /* Academic Year */

    const yearCell =
        document.createElement("td");

    yearCell.textContent =
        academicYear.value;


    /* Book */

    const bookCell =
        document.createElement("td");

    bookCell.textContent =
        book.options[
            book.selectedIndex
        ].text;


    /* Issue Date */

    const issueDateCell =
        document.createElement("td");

    issueDateCell.textContent =
        issueDate.value;


    /* Due Date */

    const dueDateCell =
        document.createElement("td");

    dueDateCell.textContent =
        dueDate.value;


    /* Status */

    const statusCell =
        document.createElement("td");

    const status =
        document.createElement("span");

    status.textContent =
        "Issued";

    status.classList.add(
        "status"
    );

    statusCell.appendChild(
        status
    );


    /* Add cells to row */

    row.appendChild(
        nameCell
    );

    row.appendChild(
        departmentCell
    );

    row.appendChild(
        yearCell
    );

    row.appendChild(
        bookCell
    );

    row.appendChild(
        issueDateCell
    );

    row.appendChild(
        dueDateCell
    );

    row.appendChild(
        statusCell
    );


    /* Add row to table */

    transactionBody.appendChild(
        row
    );


    /* Update record count */

    updateRecordCount();

}


/* -----------------------------------------
   Update Record Count
----------------------------------------- */

function updateRecordCount() {

    const rows =
        transactionBody.querySelectorAll(
            "tr"
        );

    recordCount.textContent =
        rows.length;

}


/* -----------------------------------------
   Issue Book Button Event
----------------------------------------- */

submitBtn.addEventListener(
    "click",
    function (event) {

        event.preventDefault();


        /*
            Step 1:
            Validate the transaction.
        */

        const isValid =
            validateForm();


        if (!isValid) {
            return;
        }


        /*
            Step 2:
            Add transaction to DOM.
        */

        addTransactionToTable();


        /*
            Step 3:
            Display success message.
        */

        showMessage(
            "Book issued successfully!",
            "success"
        );


        /*
            Step 4:
            Clear form fields.
        */

        studentName.value = "";

        academicYear.value = "";

        department.value = "";

        division.value = "";

        contact.value = "";

        book.value = "";

        bookId.value = "";

        remarks.value = "";


        /*
            Keep today's date
            after submission.
        */

        setTodayDate();

    }
);


/* -----------------------------------------
   Clear Form Button Event
----------------------------------------- */

resetBtn.addEventListener(
    "click",
    function () {

        studentName.value = "";

        academicYear.value = "";

        department.value = "";

        division.value = "";

        contact.value = "";

        book.value = "";

        bookId.value = "";

        remarks.value = "";

        message.textContent = "";

        message.className =
            "message";

        setTodayDate();

    }
);


/* -----------------------------------------
   Initial Page Setup
----------------------------------------- */

setTodayDate();