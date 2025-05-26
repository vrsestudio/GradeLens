-- Company: UWT GmbH
-- Author: Jonas Fessler
-- Date: 2025-05-03
-- Description: SQL script to create the database and tables for the GradeLens application, aligned with the ERD.
-- Version: 1.1

-- Database "gradelens"
CREATE DATABASE IF NOT EXISTS gradelens;
USE gradelens;

-- Table "users"
-- Stores user account information.
CREATE TABLE users (
    uID INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(512) NOT NULL UNIQUE, -- E-Mail must be present and cannot be used twice
    password VARCHAR(512) NOT NULL -- Password must be present
);

-- Table "authentication"
-- Stores authentication-related information like IP addresses.
CREATE TABLE authentication (
    aID INT(11) AUTO_INCREMENT PRIMARY KEY,
    lkipa VARCHAR(45), -- Last Known IP Address
    fkipa VARCHAR(45) -- First Known IP Address
);

-- Table "userauthentication"
-- Links users with their authentication records (M:N, though likely 1 user to many auth records over time).
CREATE TABLE userauthentication (
    uID INT(11) NOT NULL,
    aID INT(11) NOT NULL,
    PRIMARY KEY (uID, aID), -- Combined Primary Key
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE, -- CASCADE to delete userauthentication entries when users are deleted
    FOREIGN KEY (aID) REFERENCES authentication(aID) ON DELETE CASCADE -- CASCADE to delete userauthentication entries when authentication values are deleted
);

-- Table "subjects"
-- Stores a global list of subjects.
CREATE TABLE subjects (
    sID INT(11) AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL UNIQUE -- Assuming subject names are globally unique
);

-- Table "usersubjects"
-- Links users to the subjects they are associated with (M:N relationship).
CREATE TABLE usersubjects (
    uID INT(11) NOT NULL,
    sID INT(11) NOT NULL,
    PRIMARY KEY (uID, sID), -- Combined Primary Key
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE,
    FOREIGN KEY (sID) REFERENCES subjects(sID) ON DELETE CASCADE -- If a subject is deleted, remove user associations.
);

-- Table "assessmenttype"
-- Stores a global list of assessment types (e.g., Exam, Homework, Presentation).
-- Note: ERD shows "assesmenttype", using standard spelling "assessmenttype".
CREATE TABLE assessmenttype (
    atID INT(11) AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(64) NOT NULL UNIQUE, -- Name of the assessment type
    description VARCHAR(512), -- Optional description of the assessment type
    weight_factor DECIMAL(5, 2) DEFAULT 1.00 -- Default weight for this type of assessment
);

-- Table "userassessments"
-- Links users to the global assessment types that are relevant for them (M:N relationship).
CREATE TABLE userassessments (
    uID INT(11) NOT NULL,
    atID INT(11) NOT NULL,
    PRIMARY KEY (uID, atID), -- Combined Primary Key
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE,
    FOREIGN KEY (atID) REFERENCES assessmenttype(atID) ON DELETE CASCADE -- If an assessment type is deleted, remove user associations.
);

-- Table "grades"
-- Stores the grades achieved by users in specific subjects for certain assessment types.
CREATE TABLE grades (
    gID INT(11) AUTO_INCREMENT PRIMARY KEY,
    uID INT(11) NOT NULL, -- FOREIGN KEY linked to users
    sID INT(11) NOT NULL, -- FOREIGN KEY linked to global subjects table
    atID INT(11) NOT NULL, -- FOREIGN KEY linked to global assessmenttype table
    grade_value DECIMAL(3, 1) NOT NULL, -- The actual grade value
    grade_date DATE NOT NULL, -- Date when the grade was achieved/recorded
    description VARCHAR(255), -- Optional description or notes for the grade
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE, -- CASCADE to delete grades when users are deleted
    FOREIGN KEY (sID) REFERENCES subjects(sID) ON DELETE RESTRICT, -- RESTRICT to prevent deleting a subject if grades exist for it
    FOREIGN KEY (atID) REFERENCES assessmenttype(atID) ON DELETE RESTRICT -- RESTRICT to prevent deleting an assessment type if grades exist for it
);