<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserGameState.php';
require_once __DIR__ . '/../models/StarSystem.php';

function get_game_state($user_id) {
    $conn = get_db_connection();
    $game_state = UserGameState::getByUserId($conn, $user_id);
    $current_system = StarSystem::getByName($conn, $game_state['current_system']);
    return array_merge($game_state, $current_system);
}

function travel($user_id, $destination) {
    $conn = get_db_connection();
    $game_state = UserGameState::getByUserId($conn, $user_id);
    $current_system = StarSystem::getByName($conn, $game_state['current_system']);
    $destination_system = StarSystem::getByName($conn, $destination);

    if (!$destination_system) {
        return ['error' => 'Invalid destination'];
    }

    $distance = sqrt(
        pow($destination_system['x_coord'] - $current_system['x_coord'], 2) +
        pow($destination_system['y_coord'] - $current_system['y_coord'], 2) +
        pow($destination_system['z_coord'] - $current_system['z_coord'], 2)
    );

    if ($distance > MAX_TRAVEL_DISTANCE) {
        return ['error' => 'Destination too far'];
    }

    $travel_cost = ceil($distance * TRAVEL_COST_PER_LIGHT_YEAR);

    if ($game_state['credits'] < $travel_cost) {
        return ['error' => 'Insufficient credits'];
    }

    UserGameState::updateCredits($conn, $user_id, $game_state['credits'] - $travel_cost);
    UserGameState::updateCurrentSystem($conn, $user_id, $destination);

    return [
        'message' => "Traveled to $destination",
        'cost' => $travel_cost,
        'new_credits' => $game_state['credits'] - $travel_cost
    ];
}