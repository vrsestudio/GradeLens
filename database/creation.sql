-- Company: UWT GmbH
-- Author: Jonas Fessler
-- Date: 2025-05-26
-- Description: SQL script to create the database and tables for the GradeLens application, based on the relational schema GradeLens-Database_relational.png.
-- Version: 1.2

-- Database "gradelens"
CREATE DATABASE IF NOT EXISTS gradelens;
USE gradelens;

-- Table "users"
-- Stores user account information.
CREATE TABLE users (
    uID INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(512) NOT NULL UNIQUE, -- User's email address, must be unique.
    password VARCHAR(512) NOT NULL, -- User's hashed password.
    lkipa VARCHAR(45),
    fkipa VARCHAR(45),
    SALT VARCHAR(16),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Timestamp for the last update.
    -- Add other user-specific fields if needed, e.g., first_name, last_name, registration_date
);

-- Table "subjects"
-- Stores a global list of subjects available in the application.
CREATE TABLE subjects (
    sID INT(11) AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL UNIQUE
    -- Add other subject-specific fields if needed, e.g., subject_code, description
);

-- Table "usersubjects"
-- Links users to the subjects they are enrolled in or manage (M:N relationship).
CREATE TABLE usersubjects (
    uID INT(11) NOT NULL,
    sID INT(11) NOT NULL,
    PRIMARY KEY (uID, sID), -- Combined Primary Key
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE, -- If a user is deleted, their subject associations are also deleted.
    FOREIGN KEY (sID) REFERENCES subjects(sID) ON DELETE CASCADE -- If a subject is deleted, user associations to it are also deleted. Consider RESTRICT if subjects with active associations should not be deletable.
);

-- Table "assessmenttype"
-- Stores assessment types defined by each user.
-- In this schema, assessment types are specific to a user (uID is a Foreign Key).
CREATE TABLE assessmenttype (
    aID INT(11) AUTO_INCREMENT PRIMARY KEY,
    uID INT(11) NOT NULL, -- Foreign Key linking to the user who owns this assessment type.
    type_name VARCHAR(64) NOT NULL, -- Name of the assessment type (e.g., "Homework", "Exam").
    description VARCHAR(512), -- Optional description of the assessment type.
    weight_factor DECIMAL(5, 2) DEFAULT 1.00, -- Optional weighting factor for this assessment type.
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE, -- If a user is deleted, their defined assessment types are also deleted.
    UNIQUE (uID, type_name) -- Ensures a user cannot have two assessment types with the same name.
);

-- Table "grades"
-- Stores the grades achieved by users in specific subjects for certain assessment types.
CREATE TABLE grades (
    gID INT(11) AUTO_INCREMENT PRIMARY KEY,
    uID INT(11) NOT NULL, -- Foreign Key linked to the user who received the grade.
    sID INT(11) NOT NULL, -- Foreign Key linked to the global subjects table.
    aID INT(11) NOT NULL, -- Foreign Key linked to the user-specific assessmenttype table.
    grade_value DECIMAL(3, 1) NOT NULL, -- The actual grade value (e.g., 1.0, 2.5). Assumes a grading scale like German one.
    grade_date DATE NOT NULL, -- Date when the grade was achieved or recorded.
    description VARCHAR(255), -- Optional description or notes for the grade.
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE, -- If a user is deleted, their grades are also deleted.
    FOREIGN KEY (sID) REFERENCES subjects(sID) ON DELETE RESTRICT, -- Prevent deleting a subject if grades exist for it.
    FOREIGN KEY (aID) REFERENCES assessmenttype(aID) ON DELETE RESTRICT -- Prevent deleting an assessment type if grades exist for it.
);