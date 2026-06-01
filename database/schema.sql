-- Run via database/setup.php in your browser (see README.md)
CREATE DATABASE IF NOT EXISTS law_firm_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE law_firm_db;

DROP TABLE IF EXISTS messages;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'lawyer', 'client') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('Open', 'In Progress', 'Closed') NOT NULL DEFAULT 'Open',
    client_id INT NULL,
    lawyer_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_cases_client_id (client_id),
    INDEX idx_cases_lawyer_id (lawyer_id),
    CONSTRAINT fk_cases_client
    FOREIGN KEY (client_id) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_cases_lawyer
    FOREIGN KEY (lawyer_id) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
