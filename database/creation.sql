-- Company: UWT GmbH
-- Author: Jonas Fessler
-- Date: 2025-05-03
-- Description: SQL script to create the database and tables for the GradeLens application.
-- Version: 1.0

-- Database "gradelens"
CREATE DATABASE gradelens;
USE gradelens;

-- Table "users"
CREATE TABLE users (
    uID INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(512) NOT NULL UNIQUE, -- E-Mail must be present and cannot be used twice
    password VARCHAR(512) NOT NULL -- Password must be present
);

-- Table "authentication"
CREATE TABLE authentication (
    aID INT(11) AUTO_INCREMENT PRIMARY KEY,
    lkipa VARCHAR(45), -- Last Known IP Address
    fkipa VARCHAR(45) -- First Known IP Address
);

-- Table "userauthentication" (connects users with authentication)
CREATE TABLE userauthentication (
    uID INT(11) NOT NULL,
    aID INT(11) NOT NULL,
    PRIMARY KEY (uID, aID), -- Combined Primary Key
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE, -- CASCADE to delete userauthentication entries when users are deleted
    FOREIGN KEY (aID) REFERENCES authentication(aID) ON DELETE CASCADE -- CASCADE to delete userauthentication entries when authentication values are deleted
);

-- Table "subjects"
CREATE TABLE subjects (
    sID INT(11) AUTO_INCREMENT PRIMARY KEY,
    uID INT(11) NOT NULL, -- FOREIGN KEY linked to users
    subject_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE, -- CASCADE to delete subjects when users are deleted
    UNIQUE (uID, subject_name) -- UNIQUE to ensure a user can only have one subject with the same name
);

-- Table "assessment_types"
CREATE TABLE assessment_types (
    atID INT(11) AUTO_INCREMENT PRIMARY KEY,
    uID INT(11) NOT NULL, -- FOREIGN KEY linked to users
    type_name VARCHAR(64) NOT NULL,
    weight_factor DECIMAL(5, 2) DEFAULT 1.00,
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE, -- CASCADE to delete assessment types when users are deleted
    UNIQUE (uID, type_name) -- UNIQUE to ensure a user can only have one assessment type with the same name
);

-- Table "grades"
CREATE TABLE grades (
    gID INT(11) AUTO_INCREMENT PRIMARY KEY,
    uID INT(11) NOT NULL, -- FOREIGN KEY linked to users
    sID INT(11) NOT NULL, -- FOREIGN KEY linked to subjects
    atID INT(11) NOT NULL, -- FOREIGN KEY linked to assessment types
    grade_value DECIMAL(3, 1) NOT NULL,
    grade_date DATE NOT NULL,
    description VARCHAR(255),
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE, -- CASCADE to delete grades when users are deleted
    FOREIGN KEY (sID) REFERENCES subjects(sID) ON DELETE RESTRICT, -- RESTRICT to prevent deleting a subject if grades exist for it
    FOREIGN KEY (atID) REFERENCES assessment_types(atID) ON DELETE RESTRICT -- RESTRICT to prevent deleting an assessment type if grades exist for it
);