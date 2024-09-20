<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/missionSystem.php';

function getAvailableMissionsRoute() {
    $missions = getAvailableMissions();
    return ['success' => true, 'missions' => $missions];
}

function acceptMissionRoute($userId, $missionId) {
    $result = acceptMission($userId, $missionId);
    if ($result) {
        return ['success' => true, 'message' => 'Mission accepted successfully'];
    } else {
        return ['success' => false, 'message' => 'Failed to accept mission'];
    }
}

function completeMissionRoute($userId, $missionId) {
    $result = completeMission($userId, $missionId);
    if ($result) {
        return ['success' => true, 'message' => 'Mission completed successfully'];
    } else {
        return ['success' => false, 'message' => 'Failed to complete mission'];
    }
}
