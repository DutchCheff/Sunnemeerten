-- Database voor lokaal ontwikkelen. Wordt aangemaakt door database/seed.php

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin INTEGER NOT NULL DEFAULT 0,
    email_verified_at DATETIME NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    status VARCHAR(10) NOT NULL DEFAULT 'pending',
    is_featured INTEGER NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL
);

CREATE TABLE post_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
    path VARCHAR(255) NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE likes (
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    post_id INTEGER NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (user_id, post_id)
);

CREATE TABLE comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    body TEXT NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE announcements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    title VARCHAR(100) NOT NULL,
    body TEXT NOT NULL,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);

CREATE TABLE volunteers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    motivation TEXT NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE email_tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    token_hash VARCHAR(64) NOT NULL UNIQUE,
    type VARCHAR(20) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE workshops (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL,
    capacity INTEGER NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE workshop_signups (
    workshop_id INTEGER NOT NULL REFERENCES workshops(id) ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (workshop_id, user_id)
);

CREATE TABLE parade_locations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    accuracy INTEGER NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE settings (
    name VARCHAR(50) PRIMARY KEY,
    value TEXT NOT NULL
);

CREATE INDEX posts_status ON posts(status, created_at);
CREATE INDEX parade_locations_created ON parade_locations(created_at);
CREATE INDEX comments_post ON comments(post_id);
CREATE INDEX post_images_post ON post_images(post_id);
