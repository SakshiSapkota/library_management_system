-- Create the database (optional if already created)
CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Table: books
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    author VARCHAR(100),
    year INT,
    status VARCHAR(20) DEFAULT 'Available'
);

-- Table: issued_books
CREATE TABLE IF NOT EXISTS issued_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    issued_to VARCHAR(100) NOT NULL,
    issue_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);
