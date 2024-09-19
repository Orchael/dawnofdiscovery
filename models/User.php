<?php
session_start();
require_once 'config/database.php';
require_once 'routes/game.php';
require_once 'routes/economy.php';
require_once 'routes/exploration.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$action = $_GET['action'] ?? '';
$user_id = $_SESSION['user_id'];

switch ($action) {
    case 'get_game_state':
        echo json_encode(get_game_state($user_id));
        break;
    case 'travel':
        echo json_encode(travel($user_id, $_POST['destination']));
        break;
    case 'trade':
        echo json_encode(trade($user_id));
        break;
    case 'explore':
        echo json_encode(explore($user_id));
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}