<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserGameState.php';
require_once __DIR__ . '/../models/StarSystem.php';

function explore($user_id) {
    $conn = get_db_connection();
    $game_state = UserGameState::getByUserId($conn, $user_id);
    $current_system = StarSystem::getByName($conn, $game_state['current_system']);
    
    $discovery_chance = rand(1, 100);
    if ($discovery_chance > 70) {
        $credits_reward = rand(100, 500);
        $new_credits = $game_state['credits'] + $credits_reward;
        UserGameState::updateCredits($conn, $user_id, $new_credits);
        
        return [
            'message' => 'You discovered valuable resources!',
            'credits_reward' => $credits_reward,
            'new_credits' => $new_credits
        ];
    } else {
        return [
            'message' => 'Your exploration yielded no significant discoveries.'
        ];
    }
}