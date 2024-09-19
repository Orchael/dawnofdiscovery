<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserGameState.php';

function trade($user_id) {
    $conn = get_db_connection();
    $game_state = UserGameState::getByUserId($conn, $user_id);
    
    $profit = rand(TRADE_PROFIT_MIN, TRADE_PROFIT_MAX);
    $new_credits = $game_state['credits'] + $profit;
    
    UserGameState::updateCredits($conn, $user_id, $new_credits);
    
    return [
        'message' => 'Trade completed successfully',
        'profit' => $profit,
        'new_credits' => $new_credits
    ];
}