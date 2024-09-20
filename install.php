<?php
require_once 'config/database.php';

// Create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        credits BIGINT DEFAULT 1000,
        experience INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS star_systems (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        x FLOAT NOT NULL,
        y FLOAT NOT NULL,
        z FLOAT NOT NULL,
        type VARCHAR(50) NOT NULL,
        size INT NOT NULL,
        temperature INT NOT NULL,
        economy_type VARCHAR(50),
        security_level VARCHAR(50),
        population BIGINT
    )",
    "CREATE TABLE IF NOT EXISTS planets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        system_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        type VARCHAR(50) NOT NULL,
        size INT NOT NULL,
        orbit INT NOT NULL,
        habitable BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (system_id) REFERENCES star_systems(id)
    )",
    "CREATE TABLE IF NOT EXISTS ships (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        type VARCHAR(50) NOT NULL,
        cargo_capacity INT NOT NULL,
        fuel_capacity INT NOT NULL,
        current_fuel INT NOT NULL,
        x FLOAT NOT NULL,
        y FLOAT NOT NULL,
        z FLOAT NOT NULL,
        system_id INT,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (system_id) REFERENCES star_systems(id)
    )"
];

foreach ($tables as $sql) {
    if (!$conn->query($sql)) {
        echo "Error creating table: " . $conn->error . "\n";
    }
}

echo "Database setup completed.\n";