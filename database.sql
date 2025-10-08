CREATE DATABASE IF NOT EXISTS grad_clearance;
USE grad_clearance;

INSERT INTO users (username, password, user_type, role) VALUES 
('admin', 'admin123', 'staff', NULL);

INSERT INTO students (name, student_id, program, total_semesters) VALUES 
('John Doe', '202300001', 'degree', 8),
('Jane Smith', '202300002', 'diploma', 6),
('Alice Johnson', '202300003', 'degree', 8),
('Charlie Brown', '202300004', 'diploma', 6),
('Bob Wilson', '202300005', 'degree', 8);

INSERT INTO courses (student_id, course_name, semester, grade) VALUES 
('202300001', 'Advanced Mathematics', 1, 'A'),
('202300001', 'Introductory Physics', 2, 'B+'),
('202300001', 'Computer Science Fundamentals', 3, 'C'),
('202300001', 'Economics Principles', 4, 'A'),
('202300001', 'Literature Analysis', 5, 'B'),
('202300002', 'Basic Mathematics', 1, 'D'),
('202300002', 'Business English', 1, 'A'),
('202300002', 'Introductory Chemistry', 2, 'C+'),
('202300002', 'Marketing Basics', 3, 'B'),
('202300002', 'Accounting 101', 4, 'F'),
('202300003', 'Calculus I', 1, 'A'),
('202300003', 'Biology I', 2, 'B'),
('202300003', 'History of Science', 3, 'D+'),
('202300003', 'Statistics', 4, 'C'),
('202300003', 'Philosophy', 5, 'D'),
('202300004', 'Diploma Math', 1, 'B+'),
('202300004', 'Communication Skills', 2, 'A'),
('202300004', 'Environmental Science', 3, 'C+'),
('202300004', 'Project Management', 3, 'B'),
('202300004', 'Ethics in Business', 4, 'A'),
('202300005', 'Engineering Physics', 1, 'A'),
('202300005', 'Data Structures', 2, 'B'),
('202300005', 'Organic Chemistry', 3, 'C'),
('202300005', 'International Relations', 4, 'A+'),
('202300005', 'Software Engineering', 5, 'B+');

INSERT INTO payments (student_id, semester, courses_taken, paid_amount) VALUES 
('202300001', 1, 1, 2500),
('202300001', 2, 1, 2500),
('202300001', 3, 1, 2500),
('202300001', 4, 1, 2500),
('202300001', 5, 1, 2500),
('202300002', 1, 2, 2500),
('202300002', 2, 1, 0),
('202300002', 3, 1, 2500),
('202300002', 4, 1, 0),
('202300003', 1, 1, 2500),
('202300003', 2, 1, 2500),
('202300003', 3, 1, 2500),
('202300003', 4, 1, 2500),
('202300003', 5, 1, 2500),
('202300004', 1, 1, 2500),
('202300004', 2, 1, 2500),
('202300004', 3, 2, 5000),
('202300004', 4, 1, 2500),
('202300005', 1, 1, 2500),
('202300005', 2, 1, 2500),
('202300005', 3, 1, 0),
('202300005', 4, 1, 2500),
('202300005', 5, 1, 0);

INSERT INTO books (student_id, book_name, borrow_date, due_date, return_date, cleared) VALUES 
('202300001', 'Quantum Physics Textbook', '2023-10-01', '2023-10-08', '2023-10-07', 'yes'),
('202300002', 'Business Management Guide', '2023-10-01', '2023-10-08', NULL, 'no'),
('202300003', 'Biology Reference', '2023-11-01', '2023-11-08', '2023-11-06', 'yes'),
('202300004', 'Project Management Book', '2023-11-15', '2023-11-22', '2023-11-25', 'no'),
('202300005', 'Software Engineering Manual', '2023-12-01', '2023-12-08', '2023-12-07', 'yes');

INSERT INTO users (username, password, user_type, student_id) VALUES 
('202300001', 'pass123', 'student', '202300001'),
('202300002', 'pass123', 'student', '202300002'),
('202300003', 'pass123', 'student', '202300003'),
('202300004', 'pass123', 'student', '202300004'),
('202300005', 'pass123', 'student', '202300005');

INSERT INTO clearance_status (student_id) VALUES 
('202300001'),
('202300002'),
('202300003'),
('202300004'),
('202300005');