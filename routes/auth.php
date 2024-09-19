<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

function login($username, $password) {
    $conn = get_db_connection();
    $user = User::getByUsername($conn, $username);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

function register($username, $password) {
    $conn = get_db_connection();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    return User::create($conn, $username, $hashed_password);
}