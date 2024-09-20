<?php
require_once __DIR__ . '/../config/database.php';

function generateMissions($count = 5) {
    global $conn;
    
    $missionTypes = ['delivery', 'combat', 'exploration'];
    
    for ($i = 0; $i < $count; $i++) {
        $type = $missionTypes[array_rand($missionTypes)];
        $reward = rand(100, 1000);
        $description = generateMissionDescription($type);
        
        $sql = "INSERT INTO missions (type, description, reward, status) VALUES (?, ?, ?, 'available')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $type, $description, $reward);
        $stmt->execute();
    }
}

function generateMissionDescription($type) {
    switch ($type) {
        case 'delivery':
            return "Deliver cargo to a nearby system";
        case 'combat':
            return "Eliminate pirate threats in the sector";
        case 'exploration':
            return "Explore an uncharted region of space";
        default:
            return "Perform a task for the local authorities";
    }
}

function getAvailableMissions() {
    global $conn;
    
    $sql = "SELECT * FROM missions WHERE status = 'available' LIMIT 10";
    $result = $conn->query($sql);
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function acceptMission($userId, $missionId) {
    global $conn;
    
    $sql = "UPDATE missions SET status = 'in-progress', user_id = ? WHERE id = ? AND status = 'available'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $missionId);
    $stmt->execute();
    
    return $stmt->affected_rows > 0;
}

function completeMission($userId, $missionId) {
    global $conn;
    
    $sql = "UPDATE missions SET status = 'completed' WHERE id = ? AND user_id = ? AND status = 'in-progress'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $missionId, $userId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        // Award the mission reward to the user
        $sql = "UPDATE users u
                JOIN missions m ON m.id = ?
                SET u.credits = u.credits + m.reward
                WHERE u.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $missionId, $userId);
        $stmt->execute();
        
        return true;
    }
    
    return false;
}
