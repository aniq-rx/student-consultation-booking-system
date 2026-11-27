-- MySQL Schema for Student Consultation Booking System (STIWK2014)
-- Supports Student, Lecturer, and Admin roles + all compulsory modifications

CREATE DATABASE IF NOT EXISTS consultation_system;
USE consultation_system;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    matric_or_staff_id VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'lecturer', 'admin') NOT NULL,
    department VARCHAR(100) DEFAULT NULL,
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE consultation_slots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    lecturer_id INT NOT NULL,
    course_code VARCHAR(20) NOT NULL,
    slot_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    -- Modification 3: Consultation Type
    consultation_type ENUM('Face-to-Face', 'Online') NOT NULL DEFAULT 'Face-to-Face',
    location VARCHAR(150) DEFAULT NULL,
    meeting_link VARCHAR(255) DEFAULT NULL,
    -- Modification 2: Prevent Double Booking
    is_booked TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lecturer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_lecturer_timeslot (lecturer_id, slot_date, start_time)
);

CREATE TABLE consultation_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    slot_id INT NOT NULL,
    student_id INT NOT NULL,
    course_code VARCHAR(20) NOT NULL,
    topic VARCHAR(200) NOT NULL,
    notes TEXT DEFAULT NULL,
    -- Modification 1: Consultation Status
    status ENUM('Pending', 'Approved', 'Completed', 'Rejected') NOT NULL DEFAULT 'Pending',
    lecturer_remark TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (slot_id) REFERENCES consultation_slots(slot_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE
);
