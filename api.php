<?php
require_once 'config/database.php';
require_once 'routes/auth.php';
require_once 'routes/game.php';
require_once 'routes/exploration.php';
require_once 'routes/economy.php';
require_once 'routes/missions.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) && $_POST['action'] != 'login' && $_POST['action'] != 'register') {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'login':
        echo json_encode(login($_POST['username'], $_POST['password']));
        break;
    case 'register':
        echo json_encode(register($_POST['username'], $_POST['email'], $_POST['password']));
        break;
    case 'logout':
        echo json_encode(logout());
        break;
    case 'getUserInfo':
        echo json_encode(getUserInfo($_SESSION['user_id']));
        break;
    case 'scanSystem':
        echo json_encode(scanSystem($_SESSION['user_id']));
        break;
    case 'travel':
        echo json_encode(travel($_SESSION['user_id'], $_POST['targetSystemId']));
        break;
    case 'getMarketPrices':
        echo json_encode(getMarketPrices($_SESSION['user_id']));
        break;
    case 'trade':
        echo json_encode(trade($_SESSION['user_id'], $_POST['commodityId'], $_POST['quantity'], $_POST['isBuying']));
        break;
    case 'getAvailableMissions':
        echo json_encode(getAvailableMissionsRoute());
        break;
    case 'acceptMission':
        echo json_encode(acceptMissionRoute($_SESSION['user_id'], $_POST['missionId']));
        break;
    case 'completeMission':
        echo json_encode(completeMissionRoute($_SESSION['user_id'], $_POST['missionId']));
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
