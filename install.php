<?php
require_once 'config/database.php';
require_once 'utils/galaxyGenerator.php';

function create_tables($conn) {
    $tables = [
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS user_game_state (
            user_id INT PRIMARY KEY,
            credits INT DEFAULT 1000,
            current_system VARCHAR(50) DEFAULT 'Sol',
            ship VARCHAR(50) DEFAULT 'Basic Shuttle',
            FOREIGN KEY (user_id) REFERENCES users(id)
        )",
        "CREATE TABLE IF NOT EXISTS star_systems (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) UNIQUE NOT NULL,
            x_coord INT NOT NULL,
            y_coord INT NOT NULL,
            z_coord INT NOT NULL,
            economy_type ENUM('Agriculture', 'Industrial', 'HighTech', 'Extraction', 'Refinery', 'Service') NOT NULL,
            security_level ENUM('Low', 'Medium', 'High') NOT NULL
        )"
    ];

    foreach ($tables as $query) {
        if (!$conn->query($query)) {
            echo "Error creating table: " . $conn->error . "\n";
        }
    }
    
    echo "Tables created successfully.\n";
}

function insert_initial_data($conn) {
    // Insert Sol system
    $sol_data = [
        'name' => 'Sol',
        'x_coord' => 0,
        'y_coord' => 0,
        'z_coord' => 0,
        'economy_type' => 'HighTech',
        'security_level' => 'High'
    ];
    StarSystem::create($conn, $sol_data);
    
    echo "Initial data inserted successfully.\n";
}

// Run installation
$conn = get_db_connection();
create_tables($conn);
insert_initial_data($conn);
generateGalaxy(100); // Generate 100 star systems

echo "Installation complete.\n";