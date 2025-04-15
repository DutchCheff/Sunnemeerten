-- Create the database
CREATE DATABASE IF NOT EXISTS my_content_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE my_content_db;

-- Create the table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    images TEXT, -- Store one or more image URLs (e.g. JSON or comma-separated)
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the user table
CREATE TABLE IF NOT EXISTS userPosts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    images TEXT, -- Store one or more image URLs (e.g. JSON or comma-separated)
    content TEXT,
    is_allowed BOOLEAN,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);