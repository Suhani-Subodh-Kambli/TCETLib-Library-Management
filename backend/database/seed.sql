USE library_management;

DELETE FROM transactions;
DELETE FROM books;
ALTER TABLE books AUTO_INCREMENT = 1;
ALTER TABLE transactions AUTO_INCREMENT = 1;

INSERT INTO books (id, title, author, category, quantity, available_quantity) VALUES
(1, 'JavaScript Basics', 'John Smith', 'Programming', 5, 5),
(2, 'PHP and MySQL Foundations', 'Alex Johnson', 'Programming', 4, 3),
(3, 'Introduction to DBMS', 'Smith William', 'Databases', 6, 6),
(4, 'Data Structures and Algorithms', 'Narasimha Karumanchi', 'Computer Science', 3, 3),
(5, 'Operating System Concepts', 'Silberschatz', 'Computer Science', 4, 4),
(6, 'Artificial Intelligence: A Modern Approach', 'Stuart Russell', 'AI and Data Science', 5, 4),
(7, 'Electronics Devices and Circuits', 'Salivahanan', 'Electronics', 3, 2),
(8, 'Technical Writing Skills', 'Raman Mehta', 'General', 2, 2);

INSERT INTO transactions
(id, student_name, academic_year, department, division, contact, book_id,
 issue_date, due_date, remarks, return_date, status) VALUES
(1, 'Mahika Patil', 'TE', 'Information Technology', 'A', '9876543210', 1,
 '2026-09-20', '2026-09-27', 'Collected from counter 1', '2026-09-26', 'Returned'),
(2, 'Suhani Kambli', 'BE', 'Computer Engineering', 'B', '9876543211', 2,
 '2026-09-22', '2026-09-29', 'Project reference book', NULL, 'Issued'),
(3, 'Rahul Sharma', 'SE', 'AI and Data Science', 'A', '9876543212', 6,
 '2026-09-25', '2026-10-02', '', NULL, 'Issued'),
(4, 'Priya Deshmukh', 'TE', 'Information Technology', 'C', '9876543213', 3,
 '2026-09-10', '2026-09-17', 'Semester exam preparation', '2026-09-16', 'Returned'),
(5, 'Amit Joshi', 'FE', 'Electronics and Telecommunication', 'D', '9876543214', 7,
 '2026-09-27', '2026-10-04', 'Lab practical help', NULL, 'Issued'),
(6, 'Sneha Iyer', 'BE', 'Computer Engineering', 'A', '9876543215', 4,
 '2026-09-15', '2026-09-22', 'Returned on time', '2026-09-21', 'Returned');
