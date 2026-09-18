-- ==========================================================
-- Campus Connect — College Event Management System
-- Database Name: campus_connect
-- Technology: MySQL / phpMyAdmin / WAMPServer
-- ==========================================================

-- 1. Create Database if it does not exist
CREATE DATABASE IF NOT EXISTS `collage_connect` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `collage_connect`;

-- ----------------------------------------------------------
-- Table 1: USERS (Students)
-- Stores registered student credentials and college profile details
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `registrations`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20),
    `course` VARCHAR(100),
    `semester` VARCHAR(50),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- Table 2: ADMINS
-- Stores administrative user credentials for the admin dashboard
-- ----------------------------------------------------------
CREATE TABLE `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- Table 3: EVENTS
-- Stores upcoming and ongoing college events managed by the admin
-- ----------------------------------------------------------
CREATE TABLE `events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT,
    `event_date` DATE NOT NULL,
    `event_time` TIME NOT NULL,
    `location` VARCHAR(200),
    `category` VARCHAR(100),
    `image` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- Table 4: REGISTRATIONS
-- Connects students with events they registered for (Many-to-Many)
-- Features UNIQUE constraint (user_id, event_id) to prevent duplicate registrations
-- Cascades deletion if a user or event is deleted
-- ----------------------------------------------------------
CREATE TABLE `registrations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `event_id` INT NOT NULL,
    `registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` VARCHAR(50) DEFAULT 'Registered',

    FOREIGN KEY (`user_id`)
        REFERENCES `users`(`id`)
        ON DELETE CASCADE,

    FOREIGN KEY (`event_id`)
        REFERENCES `events`(`id`)
        ON DELETE CASCADE,

    UNIQUE KEY `unique_registration` (`user_id`, `event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- SAMPLE DATA INSERTION
-- ==========================================================

-- Admin Account:
-- Username: admin
-- Password: admin123 (hashed using password_hash with BCRYPT)
INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$g6mJpmRB0/UHZxEQWBdenecDIslA5SNYAyGX1RPX9tmw4lvFptlXK');

-- Sample Student Accounts:
-- Passwords for all sample students is: student123
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `course`, `semester`, `created_at`) VALUES
(1, 'Rahul Sharma', 'rahul.sharma@college.edu', '$2y$10$Qy3Vt0LHc6/xEdz7a7Dxl./kG/IlgSxqGZlboRLJDCsX73ypFVv1a', '9876543210', 'BCA', '5th Semester', NOW()),
(2, 'Priya Patel', 'priya.patel@college.edu', '$2y$10$Qy3Vt0LHc6/xEdz7a7Dxl./kG/IlgSxqGZlboRLJDCsX73ypFVv1a', '9812345678', 'BCA', '3rd Semester', NOW()),
(3, 'Aman Verma', 'aman.verma@college.edu', '$2y$10$Qy3Vt0LHc6/xEdz7a7Dxl./kG/IlgSxqGZlboRLJDCsX73ypFVv1a', '9723456789', 'B.Sc Computer Science', '6th Semester', NOW());

-- Realistic College Events (As specified in prompt item 34)
INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `event_time`, `location`, `category`, `image`, `created_at`) VALUES
(1, 'Annual Cultural Fest - Tarang 2026', 'Join us for the most anticipated annual college cultural festival featuring musical performances, dance battles, street play drama, and fashion showcase. Open to all students!', '2026-10-15', '10:00:00', 'Main College Auditorium', 'Cultural', 'fest.jpg', NOW()),

(2, 'Inter-College Coding Competition', 'Test your algorithmic and problem-solving skills in a high-intensity 3-hour competitive programming showdown. Languages supported: C++, Java, Python.', '2026-09-26', '14:00:00', 'Computer Lab 3, IT Block', 'Technical', 'coding.jpg', NOW()),

(3, 'Tech Talk: Future of AI & Cloud Computing', 'Distinguished industry experts from top tech firms discuss recent advances in Large Language Models, Cloud Native Architectures, and career opportunities for BCA graduates.', '2026-09-28', '11:30:00', 'Sir CV Raman Seminar Hall', 'Workshop', 'techtalk.jpg', NOW()),

(4, 'Annual Sports Day & Athletics Meet', 'Compete in track & field races, football, badminton, basketball, and tug-of-war. Medals, trophies, and certificates awarded to all winners and runners-up.', '2026-10-05', '08:30:00', 'Campus Sports Ground', 'Sports', 'sports.jpg', NOW()),

(5, 'Campus Photography Competition', 'Capture the vibrant moments, architecture, and untold stories of our college campus. Submit your top 3 unedited photographs for judging by professional photographers.', '2026-10-08', '09:00:00', 'Art Gallery & Student Center', 'Creative', 'photography.jpg', NOW()),

(6, '24-Hour National Hackathon', 'Form a team of up to 4 students to build real-world digital solutions solving campus problems, sustainability, and smart education. Exciting cash prizes and internship offers!', '2026-10-20', '09:00:00', 'Innovation & Incubation Hub', 'Technical', 'hackathon.jpg', NOW()),

(7, 'Fresher''s Welcome Party 2026', 'A warm and vibrant evening welcome ceremony for the incoming batch with interactive icebreaker games, talent showcase, snacks, and a live DJ night.', '2026-09-30', '16:00:00', 'Central Amphitheatre', 'Cultural', 'freshers.jpg', NOW()),

(8, 'Inter-Department Quiz Championship', 'Put your general awareness, tech trivia, history, and pop culture knowledge to the test. Teams of 2 can register. Preliminary screening followed by a live buzzer round!', '2026-10-12', '13:00:00', 'Conference Room B', 'Academic', 'quiz.jpg', NOW());

-- Sample Registrations connecting students to events
INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `registration_date`, `status`) VALUES
(1, 1, 2, NOW(), 'Registered'),
(2, 1, 3, NOW(), 'Registered'),
(3, 1, 6, NOW(), 'Registered'),
(4, 2, 1, NOW(), 'Registered'),
(5, 2, 7, NOW(), 'Registered'),
(6, 3, 2, NOW(), 'Registered'),
(7, 3, 4, NOW(), 'Registered'),
(8, 3, 8, NOW(), 'Registered');
