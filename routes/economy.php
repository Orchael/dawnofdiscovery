<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/StarSystem.php';

function trade($userId, $commodityId, $quantity, $isBuying) {
    global $conn;
    
    $user = new User($conn, $userId);
    $currentShip = $user->getCurrentShip();
    
    if (!$currentShip) {
        return ['success' => false, 'message' => 'No ship available for trading'];
    }
    
    $system = new StarSystem($conn, $currentShip['system_id']);
    $price = $system->getCommodityPrice($commodityId);
    
    $totalCost = $price * $quantity;
    
    if ($isBuying) {
        if ($user->getCredits() < $totalCost) {
            return ['success' => false, 'message' => 'Not enough credits'];
        }
        if ($currentShip['cargo_capacity'] < $quantity) {
            return ['success' => false, 'message' => 'Not enough cargo space'];
        }
        $user->adjustCredits(-$totalCost);
        $user->adjustCargo($commodityId, $quantity);
    } else {
        if ($user->getCargoQuantity($commodityId) < $quantity) {
            return ['success' => false, 'message' => 'Not enough commodity in cargo'];
        }
        $user->adjustCredits($totalCost);
        $user->adjustCargo($commodityId, -$quantity);
    }
    
    return ['success' => true, 'message' => 'Trade completed successfully'];
}

function getMarketPrices($userId) {
    global $conn;
    
    $user = new User($conn, $userId);
    $currentShip = $user->getCurrentShip();
    
    if (!$currentShip) {
        return ['success' => false, 'message' => 'No ship available to check market'];
    }
    
    $system = new StarSystem($conn, $currentShip['system_id']);
    return [
        'success' => true,
        'prices' => $system->getAllCommodityPrices()
    ];
}
