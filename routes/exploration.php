<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/StarSystem.php';
require_once __DIR__ . '/../models/User.php';

function travel($userId, $targetSystemId) {
    global $conn;
    
    $user = new User($conn, $userId);
    $currentShip = $user->getCurrentShip();
    
    if (!$currentShip) {
        return ['success' => false, 'message' => 'No ship available for travel'];
    }
    
    $currentSystem = new StarSystem($conn, $currentShip['system_id']);
    $targetSystem = new StarSystem($conn, $targetSystemId);
    
    $distance = $currentSystem->distanceTo($targetSystem);
    $fuelRequired = calculateFuelRequired($distance);
    
    if ($currentShip['current_fuel'] < $fuelRequired) {
        return ['success' => false, 'message' => 'Not enough fuel for this journey'];
    }
    
    // Update ship location and fuel
    $sql = "UPDATE ships SET system_id = ?, x = ?, y = ?, z = ?, current_fuel = current_fuel - ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iddddi", $targetSystemId, $targetSystem->x, $targetSystem->y, $targetSystem->z, $fuelRequired, $currentShip['id']);
    $stmt->execute();
    
    return [
        'success' => true,
        'message' => 'Travel completed successfully',
        'newSystem' => $targetSystem->getInfo()
    ];
}

function calculateFuelRequired($distance) {
    // Simple fuel calculation, can be made more complex
    return ceil($distance * 10);
}

function scanSystem($userId) {
    global $conn;
    
    $user = new User($conn, $userId);
    $currentShip = $user->getCurrentShip();
    
    if (!$currentShip) {
        return ['success' => false, 'message' => 'No ship available for scanning'];
    }
    
    $system = new StarSystem($conn, $currentShip['system_id']);
    return [
        'success' => true,
        'systemInfo' => $system->getDetailedInfo()
    ];
}
