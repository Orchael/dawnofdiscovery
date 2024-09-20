<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/gameSettings.php';

function initiateCombat($attackerId, $defenderId) {
    global $conn;
    
    $attacker = getShipStats($attackerId);
    $defender = getShipStats($defenderId);
    
    $rounds = 0;
    $combatLog = [];
    
    while ($attacker['health'] > 0 && $defender['health'] > 0 && $rounds < 10) {
        $attackerDamage = calculateDamage($attacker['attack'], $defender['defense']);
        $defenderDamage = calculateDamage($defender['attack'], $attacker['defense']);
        
        $defender['health'] -= $attackerDamage;
        $attacker['health'] -= $defenderDamage;
        
        $combatLog[] = [
            'round' => $rounds + 1,
            'attackerDamage' => $attackerDamage,
            'defenderDamage' => $defenderDamage,
            'attackerHealth' => max(0, $attacker['health']),
            'defenderHealth' => max(0, $defender['health'])
        ];
        
        $rounds++;
    }
    
    $winner = ($attacker['health'] > $defender['health']) ? $attackerId : $defenderId;
    
    return [
        'winner' => $winner,
        'combatLog' => $combatLog
    ];
}

function getShipStats($shipId) {
    global $conn;
    
    $sql = "SELECT * FROM ships WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shipId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ship = $result->fetch_assoc();
    
    return [
        'attack' => $ship['attack'],
        'defense' => $ship['defense'],
        'health' => $ship['health']
    ];
}

function calculateDamage($attack, $defense) {
    $baseDamage = BASE_DAMAGE + $attack;
    $damageReduction = $defense / ($defense + 100); // Defense formula
    $finalDamage = $baseDamage * (1 - $damageReduction);
    return max(0, round($finalDamage));
}
