CREATE DATABASE student_management;

USE student_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'founder', 'student') NOT NULL,
    name VARCHAR(100),
    class_code VARCHAR(50),
    approved BOOLEAN DEFAULT FALSE
);

CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(50) NOT NULL,
    class_code VARCHAR(50) UNIQUE NOT NULL,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE dues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    due_title VARCHAR(100),
    due_description TEXT,
    due_date DATETIME,
    created_by INT,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    user_id INT,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    exam_name VARCHAR(100),
    result_pdf VARCHAR(255),
    uploaded_by INT,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    due_id INT,
    student_id INT,
    submission_file VARCHAR(255),
    FOREIGN KEY (due_id) REFERENCES dues(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);

INSERT INTO users (username, password, role, approved) VALUES
('saleh-sir', '$2y$10$yg9tpPVPz3677NZmMtx0t..fHtlJIyG3YMsOCmyJK93qnNOjH4ivi', 'admin', TRUE);  -- Password: 8-D (hashed)
