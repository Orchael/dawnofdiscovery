<?php
class UserGameState {
    public static function getByUserId($conn, $user_id) {
        $stmt = $conn->prepare("SELECT * FROM user_game_state WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function updateCredits($conn, $user_id, $credits) {
        $stmt = $conn->prepare("UPDATE user_game_state SET credits = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $credits, $user_id);
        return $stmt->execute();
    }

    public static function updateCurrentSystem($conn, $user_id, $system_name) {
        $stmt = $conn->prepare("UPDATE user_game_state SET current_system = ? WHERE user_id = ?");
        $stmt->bind_param("si", $system_name, $user_id);
        return $stmt->execute();
    }
}