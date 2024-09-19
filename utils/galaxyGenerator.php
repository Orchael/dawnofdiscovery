<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/StarSystem.php';
require_once __DIR__ . '/../data/starTypes.php';
require_once __DIR__ . '/../data/economyTypes.php';
require_once __DIR__ . '/../data/securityLevels.php';

function generateGalaxy($num_systems) {
    $conn = get_db_connection();
    $existing_systems = StarSystem::getAll($conn);
    $existing_count = count($existing_systems);
    
    for ($i = $existing_count; $i < $num_systems; $i++) {
        $system_data = [
            'name' => 'System-' . uniqid(),
            'x_coord' => rand(-1000, 1000),
            'y_coord' => rand(-1000, 1000),
            'z_coord' => rand(-1000, 1000),
            'economy_type' => $economy_types[array_rand($economy_types)],
            'security_level' => $security_levels[array_rand($security_levels)]
        ];
        StarSystem::create($conn, $system_data);
    }
    
    echo "Galaxy generation complete. Total systems: $num_systems\n";
}