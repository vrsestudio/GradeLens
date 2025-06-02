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
    email VARCHAR(512) NOT NULL UNIQUE
    password VARCHAR(512) NOT NULL,
    lkipa VARCHAR(45),
    fkipa VARCHAR(45),
    SALT VARCHAR(16),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table "subjects"
-- Stores a global list of subjects available in the application.
CREATE TABLE subjects (
    sID INT(11) AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL UNIQUE
);

-- Table "usersubjects"
-- Links users to the subjects they are enrolled in or manage (M:N relationship).
CREATE TABLE usersubjects (
    uID INT(11) NOT NULL,
    sID INT(11) NOT NULL,
    PRIMARY KEY (uID, sID),
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE,
    FOREIGN KEY (sID) REFERENCES subjects(sID) ON DELETE CASCADE
);

-- Table "assessmenttype"
-- Stores assessment types defined by each user.
-- In this schema, assessment types are specific to a user (uID is a Foreign Key).
CREATE TABLE assessmenttype (
    aID INT(11) AUTO_INCREMENT PRIMARY KEY,
    uID INT(11) NOT NULL,
    type_name VARCHAR(64) NOT NULL,
    description VARCHAR(512),
    weight_factor DECIMAL(5, 2) DEFAULT 1.00,
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE,
    UNIQUE (uID, type_name)
);

-- Table "grades"
-- Stores the grades achieved by users in specific subjects for certain assessment types.
CREATE TABLE grades (
    gID INT(11) AUTO_INCREMENT PRIMARY KEY,
    uID INT(11) NOT NULL,
    sID INT(11) NOT NULL,
    aID INT(11) NOT NULL,
    grade_value DECIMAL(3, 1) NOT NULL,
    grade_date DATE NOT NULL,
    description VARCHAR(255),
    FOREIGN KEY (uID) REFERENCES users(uID) ON DELETE CASCADE,
    FOREIGN KEY (sID) REFERENCES subjects(sID) ON DELETE RESTRICT,
    FOREIGN KEY (aID) REFERENCES assessmenttype(aID) ON DELETE RESTRICT
);