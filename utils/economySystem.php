<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/gameSettings.php';

function updateMarketPrices() {
    global $conn;
    
    $sql = "SELECT * FROM market_prices";
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        $newPrice = calculateNewPrice($row['price'], $row['supply'], $row['demand']);
        
        $updateSql = "UPDATE market_prices SET price = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("di", $newPrice, $row['id']);
        $stmt->execute();
    }
}

function calculateNewPrice($currentPrice, $supply, $demand) {
    $fluctuation = (rand(-PRICE_FLUCTUATION_PERCENT, PRICE_FLUCTUATION_PERCENT) / 100) + 1;
    $supplyDemandImpact = ($demand - $supply) * SUPPLY_DEMAND_IMPACT;
    
    $newPrice = $currentPrice * $fluctuation * (1 + $supplyDemandImpact);
    return max(1, round($newPrice, 2)); // Ensure price is never below 1
}
