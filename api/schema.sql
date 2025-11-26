-- Minimal schema for Ci-NDA demo
-- Run this in your MySQL server: mysql -u root -p cinda < schema.sql

CREATE DATABASE IF NOT EXISTS cinda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cinda;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  email VARCHAR(191) NOT NULL UNIQUE,
  password_hash VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  instructor VARCHAR(191),
  category VARCHAR(100),
  duration VARCHAR(100),
  level VARCHAR(50),
  description TEXT,
  image_url VARCHAR(512),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  course_id INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS mentors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191),
  title VARCHAR(191),
  bio TEXT,
  specialties TEXT,
  years_mentoring INT DEFAULT 0,
  mentees_count INT DEFAULT 0,
  spots_left INT DEFAULT 0,
  avatar_url VARCHAR(512)
);

CREATE TABLE IF NOT EXISTS opportunities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  org VARCHAR(191),
  type VARCHAR(100),
  description TEXT,
  funding VARCHAR(255),
  location VARCHAR(255),
  deadline DATE
);

CREATE TABLE IF NOT EXISTS portfolios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  owner VARCHAR(191) NOT NULL,
  description TEXT,
  category VARCHAR(100),
  tags VARCHAR(1024),
  views INT DEFAULT 0,
  likes INT DEFAULT 0,
  thumbnail_url VARCHAR(512),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- To add a demo user run the following in PHP to generate a hash, then insert manually:
-- php -r "echo password_hash('filmmaker123', PASSWORD_DEFAULT) . PHP_EOL;"
-- Example after generating a hash:
-- INSERT INTO users (name,email,password_hash) VALUES ('filmmaker','filmmaker@cinda.com','<PASTE_HASH_HERE>');
