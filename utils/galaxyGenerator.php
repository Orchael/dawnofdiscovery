<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../data/starTypes.php';
require_once __DIR__ . '/../data/planetTypes.php';
require_once __DIR__ . '/../data/economyTypes.php';
require_once __DIR__ . '/../data/securityLevels.php';

function generateGalaxy($numSystems = 1000) {
    global $conn;
    
    for ($i = 0; $i < $numSystems; $i++) {
        $x = rand(-1000, 1000) / 10;
        $y = rand(-1000, 1000) / 10;
        $z = rand(-1000, 1000) / 10;
        generateStarSystem($x, $y, $z);
    }
}

function generateStarSystem($x, $y, $z) {
    global $conn, $starTypes, $economyTypes, $securityLevels;
    
    $name = generateStarName();
    $type = array_rand($starTypes);
    $size = rand(1, 10);
    $temperature = $starTypes[$type]['temperature'][0] + rand(0, $starTypes[$type]['temperature'][1] - $starTypes[$type]['temperature'][0]);
    $economyType = array_rand($economyTypes);
    $securityLevel = array_rand($securityLevels);
    $population = rand(0, 1000000000);
    
    $sql = "INSERT INTO star_systems (name, x, y, z, type, size, temperature, economy_type, security_level, population) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdddsiissi", $name, $x, $y, $z, $type, $size, $temperature, $economyType, $securityLevel, $population);
    $stmt->execute();
    
    $systemId = $stmt->insert_id;
    generatePlanets($systemId);
    return $systemId;
}

function generatePlanets($systemId) {
    global $conn, $planetTypes;
    
    $planetCount = rand(0, 8);
    for ($i = 0; $i < $planetCount; $i++) {
        $name = generatePlanetName();
        $type = array_rand($planetTypes);
        $size = rand(1, 10);
        $orbit = $i + 1;
        $habitable = in_array($type, ['terrestrial', 'super-earth']) ? rand(0, 1) : 0;
        
        $sql = "INSERT INTO planets (system_id, name, type, size, orbit, habitable) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issiii", $systemId, $name, $type, $size, $orbit, $habitable);
        $stmt->execute();
    }
}

function generateStarName() {
    $prefixes = ['Alpha', 'Beta', 'Gamma', 'Delta', 'Epsilon'];
    $suffixes = ['Centauri', 'Cygni', 'Eridani', 'Draconis', 'Aquilae'];
    return $prefixes[array_rand($prefixes)] . ' ' . $suffixes[array_rand($suffixes)] . '-' . rand(1, 999);
}

function generatePlanetName() {
    $prefixes = ['New', 'Old', 'Nova', 'Terra', 'Gaia'];
    $suffixes = ['Prime', 'Minor', 'Major', 'Secundus', 'Tertius'];
    return $prefixes[array_rand($prefixes)] . ' ' . $suffixes[array_rand($suffixes)] . ' ' . chr(rand(65, 90));
}
